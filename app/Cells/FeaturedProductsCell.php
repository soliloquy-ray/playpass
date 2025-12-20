<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class FeaturedProductsCell extends Cell
{
    // These properties match the keys you pass in view_cell()
    public $title = 'Featured Products';
    public $subtitle = 'Handpicked selections just for you';
    public $products = [];

    public function render(): string
    {
        // Ensure products is always an array
        if (!is_array($this->products)) {
            $this->products = [];
        }

        return view('cells/featured_products', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'products' => $this->products
        ]);
    }
}
