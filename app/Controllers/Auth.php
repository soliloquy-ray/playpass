<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Config\OAuth;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class Auth
 *
 * Handles user authentication: registration, login and logout. Input
 * validation and security best practices are applied according to
 * CodeIgniter recommendations. Passwords are hashed automatically by
 * the UserModel callback defined in app/Models/UserModel.php.
 */
class Auth extends BaseController
{
    protected OAuth $oauthConfig;

    public function __construct()
    {
        $this->oauthConfig = new OAuth();
    }

    /**
     * Show the registration form.
     *
     * @return string
     */
    public function showRegister(): string
    {
        return view('auth/register');
    }

    /**
     * Process the registration form submission. Validates input based on
     * business rules and inserts a new record into the users table. On
     * success the user is redirected to the login page with a success
     * message; on failure the form is redisplayed with validation errors.
     */
    public function register(): RedirectResponse
    {
        $validationRules = [
            'name'              => 'required|min_length[2]|max_length[200]',
            'birthdate'         => 'permit_empty|valid_date[Y-m-d]',
            'email'             => 'required|valid_email|is_unique[users.email]',
            'phone'             => 'permit_empty|min_length[7]|max_length[20]',
            'password'          => 'required|min_length[8]|max_length[255]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\da-zA-Z]).+$/]',
            'password_confirm'  => 'required|matches[password]',
            'interests'         => 'permit_empty'
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $data = [
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'password' => $this->request->getPost('password'),
            'role'     => 'customer',
            'status'   => 'active',
        ];

        // Insert user and retrieve ID
        $userId = $userModel->insert($data);

        // Save additional profile data (name, birthdate, interests) into a separate table
        // or into the user model if extended. For demonstration we store it in session.
        session()->setFlashdata('success', 'Registration successful! Please log in.');
        return redirect()->to('/app/login');
    }

    /**
     * Show the login form.
     *
     * @return string
     */
    public function showLogin(): string
    {
        return view('auth/login');
    }

    /**
     * Authenticate the user by email or phone and password. Sets session
     * variables on success and redirects to the home page. On failure
     * redisplays the login form with an error message.
     */
    public function login(): RedirectResponse
    {
        $rules = [
            'identifier' => 'required',
            'password'   => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $identifier = $this->request->getPost('identifier');
        $password   = $this->request->getPost('password');

        $userModel = new UserModel();

        // Attempt to find by email or phone
        $user = $userModel->where('email', $identifier)
                          ->orWhere('phone', $identifier)
                          ->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('errors', ['Invalid credentials provided.']);
        }

        // Store basic user info in session
        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        if (empty($name)) {
            $name = $user['email'];
        }
        
        session()->set([
            'id'        => $user['id'],
            'user_id'   => $user['id'],
            'user_uuid' => $user['uuid'] ?? null,
            'user_email'=> $user['email'],
            'name'      => $name,
            'role'      => $user['role'] ?? 'customer',
            'logged_in' => true,
            'isLoggedIn' => true, // For compatibility
        ]);

        // Update last login timestamp
        $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/app');
    }

    /**
     * Log the user out by destroying the session and redirecting to the
     * homepage.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/app');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword(): string
    {
        return view('auth/forgot_password');
    }

    /**
     * Redirect to Google OAuth consent screen.
     */
    public function googleRedirect(): RedirectResponse
    {
        $params = [
            'client_id'     => $this->oauthConfig->googleClientId,
            'redirect_uri'  => $this->oauthConfig->googleRedirectUri,
            'response_type' => 'code',
            'scope'         => 'email profile',
            'access_type'   => 'online',
            'state'         => bin2hex(random_bytes(16))
        ];

        session()->set('oauth_state', $params['state']);

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        return redirect()->to($url);
    }

    /**
     * Handle the Google OAuth callback.
     */
    public function googleCallback(): RedirectResponse
    {
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        // Verify state to prevent CSRF
        if ($state !== session()->get('oauth_state')) {
            return redirect()->to('/login')->with('errors', ['Invalid OAuth state. Please try again.']);
        }

        if (!$code) {
            return redirect()->to('/login')->with('errors', ['Google authentication failed. Please try again.']);
        }

        // Exchange code for access token
        $tokenResponse = $this->getGoogleAccessToken($code);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            return redirect()->to('/login')->with('errors', ['Failed to get access token from Google.']);
        }

        // Get user info from Google
        $userInfo = $this->getGoogleUserInfo($tokenResponse['access_token']);
        if (!$userInfo || !isset($userInfo['id'])) {
            return redirect()->to('/login')->with('errors', ['Failed to get user info from Google.']);
        }

        // Find or create user
        return $this->handleSocialLogin('google', $userInfo['id'], $userInfo['email'], $userInfo['name'] ?? '', $userInfo['picture'] ?? '');
    }

    /**
     * Redirect to Facebook OAuth consent screen.
     */
    public function facebookRedirect(): RedirectResponse
    {
        $params = [
            'client_id'     => $this->oauthConfig->facebookAppId,
            'redirect_uri'  => $this->oauthConfig->facebookRedirectUri,
            'response_type' => 'code',
            'scope'         => 'email,public_profile',
            'state'         => bin2hex(random_bytes(16))
        ];

        session()->set('oauth_state', $params['state']);

        $url = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query($params);
        return redirect()->to($url);
    }

    /**
     * Handle the Facebook OAuth callback.
     */
    public function facebookCallback(): RedirectResponse
    {
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        // Verify state to prevent CSRF
        if ($state !== session()->get('oauth_state')) {
            return redirect()->to('/login')->with('errors', ['Invalid OAuth state. Please try again.']);
        }

        if (!$code) {
            return redirect()->to('/login')->with('errors', ['Facebook authentication failed. Please try again.']);
        }

        // Exchange code for access token
        $tokenResponse = $this->getFacebookAccessToken($code);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            return redirect()->to('/login')->with('errors', ['Failed to get access token from Facebook.']);
        }

        // Get user info from Facebook
        $userInfo = $this->getFacebookUserInfo($tokenResponse['access_token']);
        if (!$userInfo || !isset($userInfo['id'])) {
            return redirect()->to('/login')->with('errors', ['Failed to get user info from Facebook.']);
        }

        // Find or create user
        return $this->handleSocialLogin('facebook', $userInfo['id'], $userInfo['email'] ?? '', $userInfo['name'] ?? '', $userInfo['picture']['data']['url'] ?? '');
    }

    /**
     * Exchange authorization code for Google access token.
     */
    private function getGoogleAccessToken(string $code): ?array
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'code'          => $code,
                    'client_id'     => $this->oauthConfig->googleClientId,
                    'client_secret' => $this->oauthConfig->googleClientSecret,
                    'redirect_uri'  => $this->oauthConfig->googleRedirectUri,
                    'grant_type'    => 'authorization_code'
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth token error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user info from Google using access token.
     */
    private function getGoogleUserInfo(string $accessToken): ?array
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'Google user info error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Exchange authorization code for Facebook access token.
     */
    private function getFacebookAccessToken(string $code): ?array
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://graph.facebook.com/v18.0/oauth/access_token', [
                'query' => [
                    'code'          => $code,
                    'client_id'     => $this->oauthConfig->facebookAppId,
                    'client_secret' => $this->oauthConfig->facebookAppSecret,
                    'redirect_uri'  => $this->oauthConfig->facebookRedirectUri
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'Facebook OAuth token error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user info from Facebook using access token.
     */
    private function getFacebookUserInfo(string $accessToken): ?array
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://graph.facebook.com/v18.0/me', [
                'query' => [
                    'access_token' => $accessToken,
                    'fields'       => 'id,name,email,picture.type(large)'
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'Facebook user info error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle social login - find existing user or create new one.
     */
    private function handleSocialLogin(string $provider, string $socialId, string $email, string $name, string $avatar): RedirectResponse
    {
        $userModel = new UserModel();

        // First, try to find user by social ID
        if ($provider === 'google') {
            $user = $userModel->findByGoogleId($socialId);
        } else {
            $user = $userModel->findByFacebookId($socialId);
        }

        // If not found by social ID, try to find by email
        if (!$user && !empty($email)) {
            $user = $userModel->where('email', $email)->first();

            // Link social account to existing user
            if ($user) {
                $updateData = $provider === 'google' 
                    ? ['google_id' => $socialId] 
                    : ['facebook_id' => $socialId];
                
                if (!empty($avatar) && empty($user['avatar_url'])) {
                    $updateData['avatar_url'] = $avatar;
                }

                $userModel->update($user['id'], $updateData);
            }
        }

        // If still no user, create a new one
        if (!$user) {
            if (empty($email)) {
                return redirect()->to('/login')->with('errors', ['Email is required for registration. Please allow email access.']);
            }

            $data = [
                'email'      => $email,
                'role'       => 'customer',
                'status'     => 'active',
                'avatar_url' => $avatar,
            ];

            if ($provider === 'google') {
                $data['google_id'] = $socialId;
            } else {
                $data['facebook_id'] = $socialId;
            }

            $userId = $userModel->insert($data);
            $user = $userModel->find($userId);
        }

        // Log the user in
        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        if (empty($name)) {
            $name = $user['email'];
        }
        
        session()->set([
            'id'        => $user['id'],
            'user_id'   => $user['id'],
            'user_uuid' => $user['uuid'] ?? null,
            'user_email'=> $user['email'],
            'name'      => $name,
            'role'      => $user['role'] ?? 'customer',
            'logged_in' => true,
            'isLoggedIn' => true, // For compatibility
        ]);

        // Update last login timestamp
        $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/')->with('success', 'Welcome back!');
    }
}