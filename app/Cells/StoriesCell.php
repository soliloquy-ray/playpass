<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class StoriesCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'Customer Stories',
            'subtitle' => 'What our customers are saying',
            'testimonials' => [
                [
                    'name' => 'John Doe',
                    'role' => 'Gamer',
                    'avatar' => '/assets/images/placeholder.jpg',
                    'quote' => 'Amazing service and great products!',
                    'rating' => 5,
                    'badge' => 'Verified Purchase',
                    'date' => 'Dec 5, 2025',
                ]
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\stories', $data);
    }
}
