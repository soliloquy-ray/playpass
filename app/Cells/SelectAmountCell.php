<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class SelectAmountCell extends Cell
{
    public $products = [];

    public function render(): string
    {
        return view('cells/select_amount', [
            'products' => $this->products
        ]);
    }
}