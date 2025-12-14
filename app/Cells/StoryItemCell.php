<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class StoryItemCell extends Cell
{
    public $story;

    public function render(): string
    {
        // Map categories to colors for the badge
        $colors = [
            'trailer' => 'text-yellow-500', // Yellow
            'promo'   => 'text-red-500',    // Red
            'event'   => 'text-purple-500', // Purple
            'story'   => 'text-blue-500',   // Blue
        ];
        
        $badgeColor = $colors[$this->story['category']] ?? 'text-white';
        
        return view('App\Cells\story_item', [
            'story' => $this->story,
            'badgeColor' => $badgeColor
        ]);
    }
}