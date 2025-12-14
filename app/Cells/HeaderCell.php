<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\TopBannerModel;

class HeaderCell extends Cell
{
    public function render(): string
    {
        // Fetch active banner
        $bannerModel = new TopBannerModel();
        $banner = $bannerModel->getActiveBanner();

        return view('App\Cells\header', [
            'banner' => $banner
        ]);
    }
}
