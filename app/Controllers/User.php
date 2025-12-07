<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class User
 *
 * Handles the customer account page, which displays profile information,
 * points earned, available vouchers and purchase history. Access is
 * restricted to logged-in users; unauthenticated visitors will be
 * redirected to the login page.
 */
class User extends BaseController
{
    /**
     * Display the user dashboard.
     */
    public function index(): string|RedirectResponse
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('login');
        }

        $userModel = new UserModel();
        $orderModel = class_exists(OrderModel::class) ? new OrderModel() : null;

        $userId = session()->get('user_id');
        $user   = $userModel->find($userId);

        // Example points and vouchers; in a full implementation these
        // would be calculated based on transactions and promo models.
        $points = 42; // placeholder value
        $vouchers = [
            [
                'title'       => '5% Cashback for every P500 purchase',
                'description' => 'Earn 5% back on every order over ₱500.',
            ],
            [
                'title'       => '₱10 off for a minimum purchase of P100',
                'description' => 'Get ₱10 off when your cart total exceeds ₱100.',
            ],
            [
                'title'       => '5% Cashback for every P500 purchase',
                'description' => 'Repeat offer for demonstration purposes.',
            ],
        ];

        // Fetch purchase history (latest 10 orders)
        $orders = [];
        if ($orderModel) {
            $orders = $orderModel
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->find();
        }

        $data = [
            'user'     => $user,
            'points'   => $points,
            'vouchers' => $vouchers,
            'orders'   => $orders,
        ];

        return view('user/index', $data);
    }
}