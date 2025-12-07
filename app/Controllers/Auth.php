<?php

namespace App\Controllers;

use App\Models\UserModel;
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
        return redirect()->to('login');
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
        session()->set([
            'user_id'   => $user['id'],
            'user_uuid' => $user['uuid'],
            'user_email'=> $user['email'],
            'logged_in' => true,
        ]);

        // Update last login timestamp
        $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/');
    }

    /**
     * Log the user out by destroying the session and redirecting to the
     * homepage.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/');
    }
}