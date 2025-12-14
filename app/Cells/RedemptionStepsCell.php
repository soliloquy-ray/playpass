<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class RedemptionStepsCell extends Cell
{
    public $brandName = 'Brand'; // Default value

    public function render(): string
    {
        return view('App\Cells\redemption_steps', [
            'brandName' => $this->brandName
        ]);
    }
}