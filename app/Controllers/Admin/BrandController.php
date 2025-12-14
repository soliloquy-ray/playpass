<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BrandModel;

class BrandController extends BaseController
{
    protected $brandModel;

    public function __construct()
    {
        $this->brandModel = new BrandModel();
    }

    /**
     * List all brands
     */
    public function index()
    {
        $data = [
            'title' => 'Brands',
            'pageTitle' => 'Manage Brands',
            'brands' => $this->brandModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/brands/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Brand',
            'pageTitle' => 'Add New Brand',
        ];

        return view('admin/brands/form', $data);
    }

    /**
     * Create new brand
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'is_enabled' => $this->request->getPost('is_enabled') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/brands', $newName);
            $data['logo'] = '/uploads/brands/' . $newName;
        } elseif ($this->request->getPost('logo_url')) {
            $data['logo'] = $this->request->getPost('logo_url');
        }

        $this->brandModel->insert($data);

        return redirect()->to('/admin/brands')->with('success', 'Brand created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $brand = $this->brandModel->find($id);
        
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        $data = [
            'title' => 'Edit Brand',
            'pageTitle' => 'Edit Brand',
            'brand' => $brand,
        ];

        return view('admin/brands/form', $data);
    }

    /**
     * Update brand
     */
    public function update($id)
    {
        $brand = $this->brandModel->find($id);
        
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'is_enabled' => $this->request->getPost('is_enabled') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/brands', $newName);
            $data['logo'] = '/uploads/brands/' . $newName;
        } elseif ($this->request->getPost('logo_url')) {
            $data['logo'] = $this->request->getPost('logo_url');
        }

        $this->brandModel->update($id, $data);

        return redirect()->to('/admin/brands')->with('success', 'Brand updated successfully!');
    }

    /**
     * Delete brand
     */
    public function delete($id)
    {
        $brand = $this->brandModel->find($id);
        
        if (!$brand) {
            return redirect()->to('/admin/brands')->with('error', 'Brand not found.');
        }

        $this->brandModel->delete($id);

        return redirect()->to('/admin/brands')->with('success', 'Brand deleted successfully!');
    }
}

