<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoryModel;

class StoryController extends BaseController
{
    protected $storyModel;

    public function __construct()
    {
        $this->storyModel = new StoryModel();
    }

    /**
     * List all stories
     */
    public function index()
    {
        $data = [
            'title' => 'Stories',
            'pageTitle' => 'Manage Stories',
            'stories' => $this->storyModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/stories/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Story',
            'pageTitle' => 'Add New Story',
            'categories' => ['news', 'trailer', 'review', 'gaming', 'entertainment'],
        ];

        return view('admin/stories/form', $data);
    }

    /**
     * Create new story
     */
    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => 'required|is_unique[stories.slug]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('slug'), '-', true),
            'category' => $this->request->getPost('category'),
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'is_trailer' => $this->request->getPost('is_trailer') ? 1 : 0,
            'status' => $this->request->getPost('status') ?: 'draft',
            'published_at' => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null,
        ];

        // Handle file upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/stories', $newName);
            $data['image'] = '/uploads/stories/' . $newName;
        } elseif ($this->request->getPost('image_url')) {
            $data['image'] = $this->request->getPost('image_url');
        }

        $this->storyModel->insert($data);

        return redirect()->to(site_url('admin/stories'))->with('success', 'Story created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $story = $this->storyModel->find($id);
        
        if (!$story) {
            return redirect()->to(site_url('admin/stories'))->with('error', 'Story not found.');
        }

        $data = [
            'title' => 'Edit Story',
            'pageTitle' => 'Edit Story',
            'story' => $story,
            'categories' => ['news', 'trailer', 'review', 'gaming', 'entertainment'],
        ];

        return view('admin/stories/form', $data);
    }

    /**
     * Update story
     */
    public function update($id)
    {
        $story = $this->storyModel->find($id);
        
        if (!$story) {
            return redirect()->to(site_url('admin/stories'))->with('error', 'Story not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => "required|is_unique[stories.slug,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('slug'), '-', true),
            'category' => $this->request->getPost('category'),
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'is_trailer' => $this->request->getPost('is_trailer') ? 1 : 0,
            'status' => $this->request->getPost('status') ?: 'draft',
        ];

        // Set published_at when publishing for the first time
        if ($this->request->getPost('status') === 'published' && $story['status'] !== 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Handle file upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/stories', $newName);
            $data['image'] = '/uploads/stories/' . $newName;
        } elseif ($this->request->getPost('image_url')) {
            $data['image'] = $this->request->getPost('image_url');
        }

        $this->storyModel->update($id, $data);

        return redirect()->to(site_url('admin/stories'))->with('success', 'Story updated successfully!');
    }

    /**
     * Delete story
     */
    public function delete($id)
    {
        $story = $this->storyModel->find($id);
        
        if (!$story) {
            return redirect()->to(site_url('admin/stories'))->with('error', 'Story not found.');
        }

        $this->storyModel->delete($id);

        return redirect()->to(site_url('admin/stories'))->with('success', 'Story deleted successfully!');
    }
}

