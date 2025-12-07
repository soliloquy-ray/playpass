<?php

namespace App\Cells;

class CategoryBadgeCell
{
    /**
     * Renders category badges/pills
     */
    public function renderBadge(array $data = []): string
    {
        $defaultData = [
            'category' => 'Category',
            'icon' => '📱',
            'count' => null,
            'url' => '#'
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\category_badge', $data);
    }
}
