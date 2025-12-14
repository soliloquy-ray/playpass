<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class NewProductsCell extends Cell
{
    // These properties match the keys you pass in view_cell()
    public $title = 'New Arrivals';
    public $subtitle = 'Fresh additions to our store';
    public $products = [];

    public function render(): string
    {
        // Ensure products is always an array
        if (!is_array($this->products)) {
            $this->products = [];
        }

        return view('App\Cells\new_products', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'products' => $this->products
        ]);
    }
}
