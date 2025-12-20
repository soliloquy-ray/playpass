<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class CategoryBadgeCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'Category',
            'icon' => '📱',
            'count' => null,
            'url' => '#'
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/category_badge', $data);
    }
}
