<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StoryModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $storyModel = new StoryModel();
        $orderModel = new OrderModel();
        $userModel = new UserModel();

        // Get stats
        $revenueResult = $orderModel->selectSum('grand_total')
            ->where('payment_status', 'paid')
            ->first();
        $totalRevenue = $revenueResult['grand_total'] ?? 0;
        
        $data = [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $orderModel->countAll(),
                'totalUsers' => $userModel->countAll(),
                'totalProducts' => $productModel->countAll(),
            ],
            'recentOrders' => $orderModel->orderBy('created_at', 'DESC')->findAll(5),
            'recentStories' => $storyModel->orderBy('created_at', 'DESC')->findAll(5),
        ];

        return view('admin/dashboard/index', $data);
    }
}

