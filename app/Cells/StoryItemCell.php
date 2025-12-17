<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class StoryItemCell extends Cell
{
    public $story;

    public function render(): string
    {
        // Ensure story has all required fields
        $story = $this->story;
        
        // Set defaults if missing
        $story['category'] = $story['category'] ?? 'story';
        $story['image'] = $story['image'] ?? base_url('assets/images/placeholder.jpg');
        $story['is_trailer'] = isset($story['is_trailer']) ? (bool)$story['is_trailer'] : false;
        $story['excerpt'] = $story['excerpt'] ?? '';
        $story['published_at'] = $story['published_at'] ?? date('Y-m-d H:i:s');
        
        // Map categories to colors for the badge
        $colors = [
            'trailer' => 'text-yellow-500', // Yellow
            'promo'   => 'text-red-500',    // Red/Pink
            'event'   => 'text-purple-500', // Purple
            'story'   => 'text-blue-500',   // Blue
        ];
        
        $badgeColor = $colors[$story['category']] ?? 'text-white';
        
        return view('App\Cells\story_item', [
            'story' => $story,
            'badgeColor' => $badgeColor
        ]);
    }
}