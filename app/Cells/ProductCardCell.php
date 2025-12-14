<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class ProductCardCell extends Cell
{
    public $product;

    public function render(): string
    {
        return view('App\Cells\product_list_card', ['product' => $this->product]);
    }
}