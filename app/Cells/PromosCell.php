<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class PromosCell extends Cell
{
    // These properties match the keys you pass in view_cell()
    public $title = 'PROMOS';
    public $promos = [];

    public function render(): string
    {
        // Ensure promos is always an array
        if (!is_array($this->promos)) {
            $this->promos = [];
        }

        return view('cells/promos', [
            'title' => $this->title,
            'promos' => $this->promos
        ]);
    }
}
