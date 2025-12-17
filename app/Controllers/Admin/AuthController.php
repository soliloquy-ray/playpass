<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // If already logged in as admin, redirect to dashboard
        if (session()->get('admin_logged_in') && session()->get('role') === 'admin') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        return view('admin/auth/login');
    }

    /**
     * Authenticate admin user
     */
    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Find admin by email
        $admin = $this->adminModel->where('email', $email)->first();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Check if admin is active
        if ($admin['status'] !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is not active. Please contact support.');
        }

        // Store admin info in session
        $name = trim(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? ''));
        if (empty($name)) {
            $name = $admin['email'];
        }

        session()->set([
            'id'            => $admin['id'],
            'admin_id'      => $admin['id'],
            'admin_uuid'    => $admin['uuid'] ?? null,
            'admin_email'   => $admin['email'],
            'name'          => $name,
            'role'          => $admin['role'] ?? 'admin',
            'admin_logged_in' => true,
            'logged_in'     => true, // For compatibility
            'isLoggedIn'    => true, // For compatibility
        ]);

        // Update last login timestamp
        $this->adminModel->update($admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect()->to(site_url('admin/dashboard'))->with('success', 'Welcome back, ' . $name . '!');
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('admin/login'))->with('success', 'You have been logged out successfully.');
    }
}

