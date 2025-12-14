<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class SelectAmountCell extends Cell
{
    public $products = [];

    public function render(): string
    {
        return view('App\Cells\select_amount', [
            'products' => $this->products
        ]);
    }
}