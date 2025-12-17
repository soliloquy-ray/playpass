<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminGuard implements FilterInterface
{
    /**
     * Check if admin is logged in via the admins table
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if admin is logged in
        $isAdminLoggedIn = session()->get('admin_logged_in');
        
        if (!$isAdminLoggedIn) {
            return redirect()->to(site_url('admin/login'))->with('error', 'Please login to access admin area.');
        }
        
        // Get admin role from session
        $role = session()->get('role');
        $adminId = session()->get('admin_id') ?? session()->get('id');
        
        // If role is not in session, try to fetch from database
        if (!$role && $adminId) {
            $adminModel = new \App\Models\AdminModel();
            $admin = $adminModel->find($adminId);
            if ($admin) {
                $role = $admin['role'] ?? 'admin';
                session()->set('role', $role); // Cache it in session
                
                // Check if admin is still active
                if ($admin['status'] !== 'active') {
                    session()->destroy();
                    return redirect()->to(site_url('admin/login'))->with('error', 'Your admin account is not active.');
                }
            }
        }
        
        // Check if admin has valid role
        if ($role !== 'admin' && $role !== 'super_admin') {
            // Log this attempt for security audit
            log_message('warning', 'Unauthorized admin access attempt by Admin ID: ' . $adminId . ' (Role: ' . ($role ?? 'none') . ')');
            session()->destroy();
            return redirect()->to(site_url('admin/login'))->with('error', 'You do not have permission to access the admin area.');
        }
        
        return null;
    }

    /**
     * We don't have anything to do here
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
