<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class StoriesCell extends Cell
{
    public function render(array $data = []): string
    {
        // Default dummy data matching the "News/Trailer" design
        $defaultData = [
            'title' => 'STORIES',
            'subtitle' => 'Latest updates from the world of entertainment',
            'stories' => [ // Changed key from 'testimonials' to 'stories'
                [
                    'title' => 'Queen of Divorce: Now Streaming',
                    'image' => '/assets/images/story1.jpg',
                    'time'  => '10:00 AM',
                    'is_trailer' => false
                ],
                [
                    'title' => 'Official Trailer: Love You Since 1892',
                    'image' => '/assets/images/story2.jpg',
                    'time'  => '11:30 AM',
                    'is_trailer' => true // This adds the yellow badge
                ]
            ],
        ];

        // Merge user data with defaults
        $data = array_merge($defaultData, $data);

        return view('App\Cells\stories', $data);
    }
}