<?php

namespace App\Cells;

class CtaButtonCell
{
    /**
     * Renders a prominent CTA button/banner
     */
    public function renderBanner(array $data = []): string
    {
        $defaultData = [
            'title' => 'Ready to Get Started?',
            'subtitle' => 'Join millions of users enjoying Playpass',
            'button_text' => 'Sign Up Now',
            'button_url' => site_url('app/register'),
            'background' => '#051429',
            'icon' => '🚀'
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/cta_banner', $data);
    }
}
