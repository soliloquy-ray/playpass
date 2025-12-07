<?php

namespace App\Cells;

class StatCardCell
{
    /**
     * Renders a statistic card with icon, number, and label
     */
    public function renderCard(array $stat = []): string
    {
        $data = [
            'icon' => $stat['icon'] ?? '📊',
            'number' => $stat['number'] ?? 0,
            'label' => $stat['label'] ?? 'Stat',
            'color' => $stat['color'] ?? 'primary',
            'unit' => $stat['unit'] ?? ''
        ];

        return view('App\Cells\stat_card', $data);
    }
}
