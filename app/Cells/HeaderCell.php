<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\TopBannerModel;
use App\Models\UserModel;

class HeaderCell extends Cell
{
    public function render(): string
    {
        // Fetch active banner
        $bannerModel = new TopBannerModel();
        $banner = $bannerModel->getActiveBanner();

        // Fetch logged-in user data including avatar
        $user = null;
        if (session()->get('logged_in')) {
            $userId = session()->get('id') ?? session()->get('user_id');
            if ($userId) {
                $userModel = new UserModel();
                $user = $userModel->find($userId);
            }
        }

        return view('cells/header', [
            'banner' => $banner,
            'user' => $user
        ]);
    }
}

