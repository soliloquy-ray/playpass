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

    /**
     * Display a single story by slug
     */
    public function show($slug)
    {
        $model = new StoryModel();
        $story = $model->getBySlug($slug);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        // Get related stories (same category, excluding current)
        $relatedStories = $model->getRelatedStories(
            $story['category'] ?? 'story',
            $story['id'],
            6
        );

        // If not enough related stories in same category, fill with other published stories
        if (count($relatedStories) < 6) {
            $remaining = 6 - count($relatedStories);
            $excludeIds = array_merge([$story['id']], array_column($relatedStories, 'id'));
            
            $additionalStories = $model->where('status', 'published')
                                     ->whereNotIn('id', $excludeIds)
                                     ->orderBy('published_at', 'DESC')
                                     ->findAll($remaining);
            
            $relatedStories = array_merge($relatedStories, $additionalStories);
        }

        return view('stories/show', [
            'title' => $story['title'] . ' - Playpass',
            'story' => $story,
            'relatedStories' => $relatedStories
        ]);
    }

    // AJAX Endpoint for Infinite Scroll
    public function fetch()
    {
        $request = service('request');
        $model   = new StoryModel();

        $category = $request->getGet('category') ?? 'all';
        $offset   = (int)($request->getGet('offset') ?? 0);
        $limit    = 8; // Load 8 at a time (4 columns desktop, 2 mobile)

        // If category is 'all', fetch all published stories regardless of category
        if ($category === 'all') {
            $stories = $model->where('status', 'published')
                           ->orderBy('published_at', 'DESC')
                           ->findAll($limit, $offset);
        } else {
            $stories = $model->getStories($category, $limit, $offset);
        }

        // We return the HTML for the cards directly to simplify JS
        $html = '';
        foreach ($stories as $story) {
            // Ensure all required fields are present
            $story['category'] = $story['category'] ?? 'story';
            $story['image'] = $story['image'] ?? base_url('assets/images/placeholder.jpg');
            $story['is_trailer'] = isset($story['is_trailer']) ? (bool)$story['is_trailer'] : false;
            $story['excerpt'] = $story['excerpt'] ?? '';
            $story['published_at'] = $story['published_at'] ?? date('Y-m-d H:i:s');
            
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