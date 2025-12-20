<?php

namespace App\Cells;

class ProductShowcaseCell
{
    /**
     * Renders a horizontal product showcase/slider
     */
    public function renderShowcase(array $data = []): string
    {
        $defaultData = [
            'title' => 'Trending Products',
            'products' => [],
            'view_all_url' => site_url('app/buy-now')
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/product_showcase', $data);
    }
}
