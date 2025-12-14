<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\StoryModel;

class StoriesCell extends Cell
{
    public function render(array $data = []): string
    {
        // If stories are provided in data, use them; otherwise fetch from database
        if (isset($data['stories']) && !empty($data['stories'])) {
            // Use provided stories, but ensure they have all required fields
            $formattedStories = [];
            foreach ($data['stories'] as $story) {
                $formattedStories[] = [
                    'id' => $story['id'] ?? null,
                    'title' => $story['title'] ?? '',
                    'image' => $story['image'] ?? '/assets/images/placeholder.jpg',
                    'time' => $story['time'] ?? ($story['published_at'] ? date('h:i A', strtotime($story['published_at'])) : date('h:i A')),
                    'is_trailer' => isset($story['is_trailer']) ? (bool)$story['is_trailer'] : false,
                    'category' => $story['category'] ?? 'story',
                    'excerpt' => $story['excerpt'] ?? '',
                ];
            }
        } else {
            // Fetch from database
            $model = new StoryModel();
            $limit = $data['limit'] ?? 4;
            $stories = $model->getLatestStories($limit);
            
            // Format stories data for the view
            $formattedStories = [];
            foreach ($stories as $story) {
                $formattedStories[] = [
                    'id' => $story['id'],
                    'title' => $story['title'],
                    'image' => $story['image'] ?? '/assets/images/placeholder.jpg',
                    'time' => $story['published_at'] ? date('h:i A', strtotime($story['published_at'])) : date('h:i A'),
                    'is_trailer' => (bool)($story['is_trailer'] ?? false),
                    'category' => $story['category'] ?? 'story',
                    'excerpt' => $story['excerpt'] ?? '',
                ];
            }
        }

        $defaultData = [
            'title' => $data['title'] ?? 'STORIES',
            'stories' => $formattedStories,
        ];

        // Merge user data with defaults
        $data = array_merge($defaultData, $data);

        return view('App\Cells\stories', $data);
    }
}