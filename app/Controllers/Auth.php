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

        // Parse name into first_name and last_name
        $name = $this->request->getPost('name') ?? '';
        $nameParts = explode(' ', trim($name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Handle interests - convert array to JSON
        $interests = $this->request->getPost('interests');
        if (is_array($interests)) {
            $interests = json_encode(array_values($interests)); // Store as JSON array
        } elseif (!empty($interests)) {
            $interests = json_encode([$interests]);
        } else {
            $interests = null;
        }

        // Generate email verification token (6-digit OTP)
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $verificationToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP expires in 15 minutes

        $data = [
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'password'  => $this->request->getPost('password'),
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'interests'  => $interests,
            'role'      => 'customer',
            'status'    => 'pending', // Account is pending until email is verified
            'email_verification_token' => $verificationToken,
            'email_verification_expires_at' => $expiresAt,
        ];

        // Insert user and retrieve ID
        $userId = $userModel->insert($data);

        if (!$userId) {
            return redirect()->back()->withInput()->with('errors', ['Registration failed. Please try again.']);
        }

        // Store OTP in cache for verification (keyed by token) BEFORE sending email
        // This ensures the token is available even if email sending fails/times out
        cache()->save('email_otp_' . $verificationToken, $otp, 900); // 15 minutes
        cache()->save('email_token_user_' . $verificationToken, $userId, 900);

        // Send OTP email (non-blocking - don't wait too long)
        try {
            log_message('info', "Starting OTP email send for user: {$data['email']}");
            $emailService = \Config\Services::emailService();
            $userName = trim($firstName . ' ' . $lastName) ?: $data['email'];
            
            $result = $emailService->sendOTP($data['email'], $otp);
            
            log_message('info', "OTP email send result: " . json_encode([
                'success' => $result['success'] ?? false,
                'http_code' => $result['http_code'] ?? 'N/A',
                'duration_ms' => $result['duration_ms'] ?? 'N/A',
                'message' => $result['message'] ?? 'No message'
            ]));

            if ($result['success']) {
                // Redirect to verification page with token
                return redirect()->to(site_url('app/verify-email?token=' . $verificationToken))
                    ->with('success', 'Registration successful! Please check your email for the verification code.');
            } else {
                log_message('error', 'Failed to send OTP email. Full result: ' . json_encode($result));
                // Still redirect to verification page, user can request resend
                return redirect()->to(site_url('app/verify-email?token=' . $verificationToken))
                    ->with('error', 'Registration successful, but we could not send the verification email. Please use the "Resend Code" button below.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during OTP email send: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            // Don't fail registration if email fails - user can resend
            return redirect()->to(site_url('app/verify-email?token=' . $verificationToken))
                ->with('error', 'Registration successful, but we could not send the verification email. Please use the "Resend Code" button below.');
        }
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

        // Check if account is verified
        if (($user['status'] ?? 'pending') === 'pending') {
            // Generate new verification token if expired
            if (empty($user['email_verification_token']) || 
                (isset($user['email_verification_expires_at']) && strtotime($user['email_verification_expires_at']) < time())) {
                
                $verificationToken = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                $userModel->update($user['id'], [
                    'email_verification_token' => $verificationToken,
                    'email_verification_expires_at' => $expiresAt
                ]);
                
                // Send new OTP
                try {
                    $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
                    $emailService = \Config\Services::emailService();
                    $emailService->sendOTP($user['email'], $otp);
                    
                    cache()->save('email_otp_' . $verificationToken, $otp, 900);
                    cache()->save('email_token_user_' . $verificationToken, $user['id'], 900);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send OTP: ' . $e->getMessage());
                }
                
                return redirect()->to(site_url('app/verify-email?token=' . $verificationToken))
                    ->with('error', 'Please verify your email address before logging in. A new verification code has been sent to your email.');
            } else {
                return redirect()->to(site_url('app/verify-email?token=' . ($user['email_verification_token'] ?? '')))
                    ->with('error', 'Please verify your email address before logging in.');
            }
        }

        // Check if account is active
        if (($user['status'] ?? 'pending') !== 'active') {
            return redirect()->back()->withInput()->with('errors', ['Your account is not active. Please contact support.']);
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

        return redirect()->to(site_url('app'));
    }

    /**
     * Log the user out by destroying the session and redirecting to the
     * homepage.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to(site_url('app'));
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword(): string
    {
        return view('auth/forgot_password');
    }

    /**
     * Show the email verification page.
     * 
     * @return string|RedirectResponse
     */
    public function showVerifyEmail()
    {
        $token = $this->request->getGet('token');
        
        if (empty($token)) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid verification link.']);
        }

        // Check if token is valid
        $userModel = new UserModel();
        $user = $userModel->where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid or expired verification token.']);
        }

        // Check if token is expired
        if (isset($user['email_verification_expires_at']) && strtotime($user['email_verification_expires_at']) < time()) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Verification token has expired. Please register again.']);
        }

        return view('auth/verify_email', ['token' => $token, 'email' => $user['email']]);
    }

    /**
     * Verify email with OTP code.
     */
    public function verifyEmail(): RedirectResponse
    {
        $token = $this->request->getPost('token');
        $otp = $this->request->getPost('otp');

        if (empty($token) || empty($otp)) {
            return redirect()->back()->withInput()->with('errors', ['Please enter the verification code.']);
        }

        // Get stored OTP from cache
        $storedOtp = cache()->get('email_otp_' . $token);
        $userId = cache()->get('email_token_user_' . $token);

        if (!$storedOtp || !$userId || $storedOtp !== $otp) {
            return redirect()->back()->withInput()->with('errors', ['Invalid verification code. Please try again.']);
        }

        // Verify user exists and token matches
        $userModel = new UserModel();
        $user = $userModel->where('id', $userId)
                          ->where('email_verification_token', $token)
                          ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('errors', ['Invalid verification token.']);
        }

        // Check if token is expired
        if (isset($user['email_verification_expires_at']) && strtotime($user['email_verification_expires_at']) < time()) {
            return redirect()->back()->withInput()->with('errors', ['Verification code has expired. Please request a new one.']);
        }

        // Activate account
        $userModel->update($user['id'], [
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        // Clear cache
        cache()->delete('email_otp_' . $token);
        cache()->delete('email_token_user_' . $token);

        // Send welcome email
        try {
            $emailService = \Config\Services::emailService();
            $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['email'];
            $emailService->sendWelcomeEmail($user['email'], $userName);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->to(site_url('app/login'))->with('success', 'Email verified successfully! You can now log in.');
    }

    /**
     * Resend verification OTP.
     */
    public function resendVerification(): RedirectResponse
    {
        $token = $this->request->getPost('token') ?? $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid verification link.']);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid verification token.']);
        }

        // Generate new OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Update expiry
        $userModel->update($user['id'], [
            'email_verification_expires_at' => $expiresAt
        ]);

        // Send OTP email
        try {
            $emailService = \Config\Services::emailService();
            $result = $emailService->sendOTP($user['email'], $otp);

            if ($result['success']) {
                cache()->save('email_otp_' . $token, $otp, 900);
                cache()->save('email_token_user_' . $token, $user['id'], 900);
                
                return redirect()->back()->with('success', 'A new verification code has been sent to your email.');
            } else {
                return redirect()->back()->with('errors', ['Failed to send verification email. Please try again.']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to resend OTP: ' . $e->getMessage());
            return redirect()->back()->with('errors', ['Failed to send verification email. Please try again.']);
        }
    }

    /**
     * Process forgot password form submission.
     * Sends a password reset email to the user.
     */
    public function forgotPassword(): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        
        // Find user by email
        $user = $userModel->where('email', $email)->first();

        // Always show success message for security (don't reveal if email exists)
        if ($user) {
            // Generate a secure reset token
            $resetToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token in session (in production, store in database)
            // For now, we'll use a simple approach with encoded token
            $resetData = [
                'user_id' => $user['id'],
                'token' => $resetToken,
                'expires_at' => $expiresAt
            ];
            
            // Store in cache/session for retrieval
            cache()->save('password_reset_' . $resetToken, $resetData, 3600); // 1 hour
            
            // Create reset link
            $resetLink = base_url('app/reset-password?token=' . $resetToken);
            
            // Send email using EmailService
            $emailService = \Config\Services::emailService();
            $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            if (empty($userName)) {
                $userName = null;
            }
            
            $result = $emailService->sendPasswordReset($email, $resetLink, $userName);
            
            if (!$result['success']) {
                log_message('error', 'Failed to send password reset email: ' . ($result['message'] ?? 'Unknown error'));
                // Still show success to user for security
            }
        }

        // Always return success message (security best practice)
        return redirect()->back()->with('success', 'If an account with that email exists, a password reset link has been sent.');
    }

    /**
     * Redirect to Google OAuth consent screen.
     */
    public function googleRedirect(): RedirectResponse
    {
        // Check if Google OAuth is configured
        if (empty($this->oauthConfig->googleClientId)) {
            return redirect()->to(site_url('app/login'))
                ->with('errors', ['Google login is not configured. Please contact support or use email registration.']);
        }

        $params = [
            'client_id'     => $this->oauthConfig->googleClientId,
            'redirect_uri'  => $this->oauthConfig->googleRedirectUri,
            'response_type' => 'code',
            'scope'         => 'email profile',
            'access_type'   => 'online',
            'state'         => bin2hex(random_bytes(16))
        ];

        log_message('debug', 'Google OAuth redirect - redirect_uri: ' . $this->oauthConfig->googleRedirectUri);
        log_message('debug', 'Google OAuth redirect - client_id: ' . substr($this->oauthConfig->googleClientId, 0, 10) . '...');

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
        $error = $this->request->getGet('error');

        // Check for OAuth errors from Google
        if ($error) {
            $errorDescription = $this->request->getGet('error_description') ?? 'Unknown error';
            log_message('error', 'Google OAuth callback error: ' . $error . ' - ' . $errorDescription);
            return redirect()->to(site_url('app/login'))->with('errors', ['Google authentication failed: ' . $errorDescription]);
        }

        // Verify state to prevent CSRF
        $sessionState = session()->get('oauth_state');
        if ($state !== $sessionState) {
            log_message('error', 'Google OAuth state mismatch - session: ' . ($sessionState ?? 'null') . ', received: ' . ($state ?? 'null'));
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid OAuth state. Please try again.']);
        }

        if (!$code) {
            log_message('error', 'Google OAuth callback received without code');
            return redirect()->to(site_url('app/login'))->with('errors', ['Google authentication failed. Please try again.']);
        }

        log_message('debug', 'Google OAuth callback received code and state');

        // Exchange code for access token
        $tokenResponse = $this->getGoogleAccessToken($code);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            log_message('error', 'Failed to get access token from Google - tokenResponse: ' . json_encode($tokenResponse));
            return redirect()->to(site_url('app/login'))->with('errors', ['Failed to get access token from Google. Please check the logs or try again.']);
        }

        // Get user info from Google
        $userInfo = $this->getGoogleUserInfo($tokenResponse['access_token']);
        if (!$userInfo || !isset($userInfo['id'])) {
            log_message('error', 'Failed to get user info from Google - userInfo: ' . json_encode($userInfo));
            return redirect()->to(site_url('app/login'))->with('errors', ['Failed to get user info from Google.']);
        }

        // Find or create user
        return $this->handleSocialLogin('google', $userInfo['id'], $userInfo['email'], $userInfo['name'] ?? '', $userInfo['picture'] ?? '');
    }

    /**
     * Redirect to Facebook OAuth consent screen.
     */
    public function facebookRedirect(): RedirectResponse
    {
        // Check if Facebook OAuth is configured
        if (empty($this->oauthConfig->facebookAppId)) {
            return redirect()->to(site_url('app/login'))
                ->with('errors', ['Facebook login is not configured. Please contact support or use email registration.']);
        }

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
            return redirect()->to(site_url('app/login'))->with('errors', ['Invalid OAuth state. Please try again.']);
        }

        if (!$code) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Facebook authentication failed. Please try again.']);
        }

        // Exchange code for access token
        $tokenResponse = $this->getFacebookAccessToken($code);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Failed to get access token from Facebook.']);
        }

        // Get user info from Facebook
        $userInfo = $this->getFacebookUserInfo($tokenResponse['access_token']);
        if (!$userInfo || !isset($userInfo['id'])) {
            return redirect()->to(site_url('app/login'))->with('errors', ['Failed to get user info from Facebook.']);
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
            $requestData = [
                    'code'          => $code,
                    'client_id'     => $this->oauthConfig->googleClientId,
                    'client_secret' => $this->oauthConfig->googleClientSecret,
                    'redirect_uri'  => $this->oauthConfig->googleRedirectUri,
                    'grant_type'    => 'authorization_code'
            ];

            log_message('debug', 'Google OAuth token request: ' . json_encode([
                'redirect_uri' => $this->oauthConfig->googleRedirectUri,
                'client_id' => substr($this->oauthConfig->googleClientId, 0, 10) . '...',
                'has_code' => !empty($code)
            ]));

            // Configure SSL for local development (Laragon sometimes has SSL cert issues)
            $options = [
                'form_params' => $requestData,
            ];

            // Only disable SSL verification in development to avoid certificate issues
            if (ENVIRONMENT !== 'production') {
                $options['verify'] = false;
            }

            $response = $client->post('https://oauth2.googleapis.com/token', $options);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $data = json_decode($body, true);

            log_message('debug', 'Google OAuth token response: ' . json_encode([
                'status_code' => $statusCode,
                'has_access_token' => isset($data['access_token']),
                'error' => $data['error'] ?? null,
                'error_description' => $data['error_description'] ?? null
            ]));

            // Check for errors in response
            if ($statusCode !== 200) {
                log_message('error', 'Google OAuth token error - HTTP ' . $statusCode . ': ' . $body);
                return null;
            }

            if (isset($data['error'])) {
                log_message('error', 'Google OAuth token error: ' . $data['error'] . ' - ' . ($data['error_description'] ?? 'No description'));
                return null;
            }

            if (!isset($data['access_token'])) {
                log_message('error', 'Google OAuth token response missing access_token: ' . $body);
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth token exception: ' . $e->getMessage());
            log_message('error', 'Google OAuth token exception trace: ' . $e->getTraceAsString());
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
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ];

            // Only disable SSL verification in development to avoid certificate issues
            if (ENVIRONMENT !== 'production') {
                $options['verify'] = false;
            }

            $response = $client->get('https://www.googleapis.com/oauth2/v2/userinfo', $options);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode !== 200) {
                log_message('error', 'Google user info error - HTTP ' . $statusCode . ': ' . $body);
                return null;
            }

            if (isset($data['error'])) {
                log_message('error', 'Google user info error: ' . $data['error'] . ' - ' . ($data['error_description'] ?? 'No description'));
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', 'Google user info exception: ' . $e->getMessage());
            log_message('error', 'Google user info exception trace: ' . $e->getTraceAsString());
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
            $options = [
                'query' => [
                    'code'          => $code,
                    'client_id'     => $this->oauthConfig->facebookAppId,
                    'client_secret' => $this->oauthConfig->facebookAppSecret,
                    'redirect_uri'  => $this->oauthConfig->facebookRedirectUri
                ]
            ];

            // Only disable SSL verification in development to avoid certificate issues
            if (ENVIRONMENT !== 'production') {
                $options['verify'] = false;
            }

            $response = $client->get('https://graph.facebook.com/v18.0/oauth/access_token', $options);

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
            $options = [
                'query' => [
                    'access_token' => $accessToken,
                    'fields'       => 'id,name,email,picture.type(large)'
                ]
            ];

            // Only disable SSL verification in development to avoid certificate issues
            if (ENVIRONMENT !== 'production') {
                $options['verify'] = false;
            }

            $response = $client->get('https://graph.facebook.com/v18.0/me', $options);

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

            // Link social account to existing user and auto-verify
            if ($user) {
                $updateData = $provider === 'google' 
                    ? ['google_id' => $socialId] 
                    : ['facebook_id' => $socialId];
                
                if (!empty($avatar) && empty($user['avatar_url'])) {
                    $updateData['avatar_url'] = $avatar;
                }

                // Auto-verify and activate if not already verified
                if (($user['status'] ?? 'pending') === 'pending' || empty($user['email_verified_at'])) {
                    $updateData['status'] = 'active';
                    $updateData['email_verified_at'] = date('Y-m-d H:i:s');
                    $updateData['email_verification_token'] = null;
                    $updateData['email_verification_expires_at'] = null;
                }

                $userModel->update($user['id'], $updateData);
                // Refresh user data
                $user = $userModel->find($user['id']);
            }
        }

        // If still no user, create a new one
        if (!$user) {
            if (empty($email)) {
                return redirect()->to(site_url('app/login'))->with('errors', ['Email is required for registration. Please allow email access.']);
            }

            $data = [
                'email'      => $email,
                'role'       => 'customer',
                'status'     => 'active', // Social logins are auto-verified
                'email_verified_at' => date('Y-m-d H:i:s'), // Auto-verify email from social provider
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

        return redirect()->to(site_url('app'))->with('success', 'Welcome back!');
    }
}