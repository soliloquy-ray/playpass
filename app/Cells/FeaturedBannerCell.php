<?php

namespace App\Cells;

class FeaturedBannerCell
{
    /**
     * Renders a featured product/promotion banner
     */
    public function renderBanner(array $data = []): string
    {
        $defaultData = [
            'title' => 'Featured Product',
            'description' => 'Check out our latest featured item',
            'image_url' => null,
            'button_text' => 'View Now',
            'button_url' => '#',
            'badge' => 'FEATURED',
            'background_gradient' => 'linear-gradient(135deg, #d8369f 0%, #051429 100%)'
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\featured_banner', $data);
    }
}
