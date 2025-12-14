<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CarouselSlideModel;

class CarouselController extends BaseController
{
    protected $carouselModel;

    public function __construct()
    {
        $this->carouselModel = new CarouselSlideModel();
    }

    /**
     * List all carousel slides
     */
    public function index()
    {
        $data = [
            'title' => 'Carousel',
            'pageTitle' => 'Manage Carousel Slides',
            'slides' => $this->carouselModel->orderBy('sort_order', 'ASC')->findAll(),
        ];

        return view('admin/carousel/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Slide',
            'pageTitle' => 'Add New Carousel Slide',
        ];

        return view('admin/carousel/form', $data);
    }

    /**
     * Create new slide
     */
    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'cta_text' => $this->request->getPost('cta_text'),
            'cta_link' => $this->request->getPost('cta_link'),
            'bg_gradient_start' => $this->request->getPost('bg_gradient_start') ?: '#d8369f',
            'bg_gradient_end' => $this->request->getPost('bg_gradient_end') ?: '#051429',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/carousel', $newName);
            $data['image_url'] = '/uploads/carousel/' . $newName;
        } elseif ($this->request->getPost('image_url')) {
            $data['image_url'] = $this->request->getPost('image_url');
        }

        $this->carouselModel->insert($data);

        return redirect()->to('/admin/carousel')->with('success', 'Carousel slide created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $slide = $this->carouselModel->find($id);
        
        if (!$slide) {
            return redirect()->to('/admin/carousel')->with('error', 'Slide not found.');
        }

        $data = [
            'title' => 'Edit Slide',
            'pageTitle' => 'Edit Carousel Slide',
            'slide' => $slide,
        ];

        return view('admin/carousel/form', $data);
    }

    /**
     * Update slide
     */
    public function update($id)
    {
        $slide = $this->carouselModel->find($id);
        
        if (!$slide) {
            return redirect()->to('/admin/carousel')->with('error', 'Slide not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'cta_text' => $this->request->getPost('cta_text'),
            'cta_link' => $this->request->getPost('cta_link'),
            'bg_gradient_start' => $this->request->getPost('bg_gradient_start') ?: '#d8369f',
            'bg_gradient_end' => $this->request->getPost('bg_gradient_end') ?: '#051429',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/carousel', $newName);
            $data['image_url'] = '/uploads/carousel/' . $newName;
        } elseif ($this->request->getPost('image_url')) {
            $data['image_url'] = $this->request->getPost('image_url');
        }

        $this->carouselModel->update($id, $data);

        return redirect()->to('/admin/carousel')->with('success', 'Carousel slide updated successfully!');
    }

    /**
     * Delete slide
     */
    public function delete($id)
    {
        $slide = $this->carouselModel->find($id);
        
        if (!$slide) {
            return redirect()->to('/admin/carousel')->with('error', 'Slide not found.');
        }

        $this->carouselModel->delete($id);

        return redirect()->to('/admin/carousel')->with('success', 'Carousel slide deleted successfully!');
    }

    /**
     * Update sort order via AJAX
     */
    public function updateOrder()
    {
        $order = $this->request->getJSON(true);
        
        if ($order && isset($order['items'])) {
            foreach ($order['items'] as $index => $id) {
                $this->carouselModel->update($id, ['sort_order' => $index]);
            }
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }
}

