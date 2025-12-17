<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\PointLedgerModel;
use App\Models\VoucherCampaignModel;
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
            return redirect()->to(site_url('app/login'));
        }

        $userModel = new UserModel();
        $orderModel = class_exists(OrderModel::class) ? new OrderModel() : null;
        $pointLedgerModel = class_exists(PointLedgerModel::class) ? new PointLedgerModel() : null;
        $voucherCampaignModel = new VoucherCampaignModel();

        $userId = session()->get('user_id');
        $user   = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('app/login'))->with('errors', ['User not found.']);
        }

        // Get filter parameters
        $filterType = $this->request->getGet('filter') ?? 'all';
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Fetch real points balance
        $points = 0;
        if ($pointLedgerModel) {
            $points = $pointLedgerModel->getBalance($userId);
        } else {
            // Fallback to cached value if available
            $points = $user['current_points_balance'] ?? 0;
        }

        // Fetch available vouchers
        $availableCampaigns = $voucherCampaignModel->getAvailableVouchers();
        $vouchers = [];
        foreach ($availableCampaigns as $campaign) {
            $vouchers[] = $voucherCampaignModel->formatVoucherForDisplay($campaign);
        }

        // Fetch transactions - combine orders and point ledger entries
        $transactions = [];

        // Add orders as transactions
        if ($orderModel) {
            $ordersQuery = $orderModel->where('user_id', $userId);
            
            if ($dateFrom) {
                $ordersQuery->where('created_at >=', $dateFrom . ' 00:00:00');
            }
            if ($dateTo) {
                $ordersQuery->where('created_at <=', $dateTo . ' 23:59:59');
            }
            
            $orders = $ordersQuery->orderBy('created_at', 'DESC')->limit(50)->findAll();
            
            foreach ($orders as $order) {
                $transactions[] = [
                    'id' => 'order_' . $order['id'],
                    'date' => $order['created_at'],
                    'amount' => $order['grand_total'],
                    'type' => 'transaction',
                    'description' => 'Order #' . $order['id'] . ' - Purchase total: PHP ' . number_format($order['grand_total'], 2),
                ];
            }
        }

        // Add point ledger entries
        if ($pointLedgerModel) {
            $pointTransactions = $pointLedgerModel->getUserTransactions($userId, $filterType, $dateFrom, $dateTo);
            
            foreach ($pointTransactions as $pt) {
                $type = 'earned';
                $description = '';
                
                switch ($pt['transaction_type']) {
                    case 'purchase_reward':
                        $type = 'earned';
                        $description = 'Earned ' . abs($pt['amount']) . ' points from purchase';
                        break;
                    case 'redemption':
                        $type = 'redeemed';
                        $description = 'Redeemed ' . abs($pt['amount']) . ' points';
                        break;
                    case 'referral_bonus':
                        $type = 'earned';
                        $description = 'Earned ' . abs($pt['amount']) . ' points from referral bonus';
                        break;
                    case 'adjustment':
                        $description = 'Points adjustment: ' . ($pt['amount'] >= 0 ? '+' : '') . $pt['amount'] . ' points';
                        $type = $pt['amount'] >= 0 ? 'earned' : 'redeemed';
                        break;
                }
                
                if ($pt['reference_id']) {
                    $description .= ' (Ref: ' . $pt['reference_id'] . ')';
                }
                
                $transactions[] = [
                    'id' => 'points_' . $pt['id'],
                    'date' => $pt['created_at'],
                    'amount' => abs($pt['amount']), // Show positive for display
                    'type' => $type,
                    'description' => $description,
                    'points_amount' => $pt['amount'], // Keep original for filtering
                ];
            }
        }

        // Filter transactions by type if not 'all'
        if ($filterType !== 'all') {
            $transactions = array_filter($transactions, function($transaction) use ($filterType) {
                return ($transaction['type'] ?? 'transaction') === $filterType;
            });
        }

        // Sort all transactions by date (newest first)
        usort($transactions, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Limit to 50 most recent
        $transactions = array_slice($transactions, 0, 50);

        // Prepare user display name
        $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        if (empty($userName)) {
            $userName = $user['email'];
        }

        $data = [
            'user'         => $user,
            'userName'     => $userName,
            'points'       => $points,
            'vouchers'     => $vouchers,
            'transactions' => $transactions,
            'filterType'   => $filterType,
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
        ];

        return view('user/index', $data);
    }

    /**
     * Show edit profile page
     */
    public function editProfile(): string|RedirectResponse
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('app/login'));
        }

        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('app/login'))->with('errors', ['User not found.']);
        }

        return view('user/edit_profile', ['user' => $user]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(): RedirectResponse
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('app/login'));
        }

        $userModel = new UserModel();
        $userId = session()->get('user_id');

        $validationRules = [
            'first_name' => 'permit_empty|min_length[2]|max_length[100]',
            'last_name'  => 'permit_empty|min_length[2]|max_length[100]',
            'phone'      => 'permit_empty|min_length[7]|max_length[20]',
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [];
        
        if ($this->request->getPost('first_name')) {
            $updateData['first_name'] = $this->request->getPost('first_name');
        }
        
        if ($this->request->getPost('last_name')) {
            $updateData['last_name'] = $this->request->getPost('last_name');
        }
        
        if ($this->request->getPost('phone')) {
            $updateData['phone'] = $this->request->getPost('phone');
        }

        if (!empty($updateData)) {
            $userModel->update($userId, $updateData);
            
            // Update session
            $user = $userModel->find($userId);
            $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            if (empty($name)) {
                $name = $user['email'];
            }
            session()->set('name', $name);
        }

        return redirect()->to(site_url('app/account'))->with('success', 'Profile updated successfully!');
    }

    /**
     * Use voucher - redirect to checkout with voucher pre-filled
     */
    public function useVoucher($campaignId): RedirectResponse
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('app/login'));
        }

        // For now, just redirect to buy-now page
        // In future, could pre-fill voucher code in checkout
        return redirect()->to(site_url('app/buy-now'))->with('voucher_campaign_id', $campaignId);
    }
}