<?php

namespace App\Cells;

class HeroBannerCell
{
    /**
     * Renders a hero banner with title, subtitle, image, and CTA button
     */
    public function renderBanner(array $data = []): string
    {
        $defaultData = [
            'title' => 'Welcome to Playpass',
            'subtitle' => 'Discover amazing digital products',
            'image_gradient' => 'linear-gradient(135deg, #d8369f 0%, #051429 100%)',
            'button_text' => 'Explore Now',
            'button_url' => '/products',
            'button_class' => 'btn-primary'
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\hero_banner', $data);
    }
}
