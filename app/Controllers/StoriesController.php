<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoryModel;

class StoriesController extends BaseController
{
    public function index()
    {
        // Initial load (render the view)
        return view('stories/index', [
            'title' => 'Stories - Playpass'
        ]);
    }

    // AJAX Endpoint for Infinite Scroll
    public function fetch()
    {
        $request = service('request');
        $model   = new StoryModel();

        $category = $request->getGet('category') ?? 'all';
        $offset   = $request->getGet('offset') ?? 0;
        $limit    = 6; // Load 6 at a time

        $stories = $model->getStories($category, $limit, $offset);

        // We return the HTML for the cards directly to simplify JS
        $html = '';
        foreach ($stories as $story) {
            // Use the Cell to render each item to ensure consistency
            $html .= view_cell('App\Cells\StoryItemCell::render', ['story' => $story]);
        }

        return $this->response->setJSON([
            'html' => $html,
            'count' => count($stories),
            'hasMore' => count($stories) >= $limit
        ]);
    }
}