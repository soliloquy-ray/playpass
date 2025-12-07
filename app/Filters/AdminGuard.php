public function before(RequestInterface $request, $arguments = null)
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }
    if (session()->get('role') !== 'admin') {
        // Log this attempt for security audit
        log_message('warning', 'Unauthorized admin access attempt by User ' . session()->get('id'));
        return redirect()->to('/'); // Kick them back to home
    }
}