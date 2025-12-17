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

        // Ensure upload directory exists
        $uploadPath = FCPATH . 'uploads/brands';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle file upload using raw $_FILES array
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK && $_FILES['logo']['size'] > 0) {
            $tmpName = $_FILES['logo']['tmp_name'];
            $originalName = $_FILES['logo']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid() . '_' . time() . '.' . $extension;
            $destination = $uploadPath . '/' . $newName;
            
            if (move_uploaded_file($tmpName, $destination)) {
                $data['logo'] = '/uploads/brands/' . $newName;
            }
        }
        
        // Only use logo_url if no file was uploaded and URL is provided
        if (empty($data['logo']) && ($logoUrl = $this->request->getPost('logo_url'))) {
            $logoUrl = trim($logoUrl);
            if (!empty($logoUrl)) {
                $data['logo'] = $logoUrl;
            }
        }

        $this->brandModel->insert($data);

        return redirect()->to(site_url('admin/brands'))->with('success', 'Brand created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $brand = $this->brandModel->find($id);
        
        if (!$brand) {
            return redirect()->to(site_url('admin/brands'))->with('error', 'Brand not found.');
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
            return redirect()->to(site_url('admin/brands'))->with('error', 'Brand not found.');
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

        // Ensure upload directory exists
        $uploadPath = FCPATH . 'uploads/brands';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle file upload using raw $_FILES array
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK && $_FILES['logo']['size'] > 0) {
            $tmpName = $_FILES['logo']['tmp_name'];
            $originalName = $_FILES['logo']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid() . '_' . time() . '.' . $extension;
            $destination = $uploadPath . '/' . $newName;
            
            if (move_uploaded_file($tmpName, $destination)) {
                $data['logo'] = '/uploads/brands/' . $newName;
            }
        }
        
        // Only use logo_url if no file was uploaded and URL is provided
        // Also preserve existing logo if no new file and no URL provided
        if (empty($data['logo'])) {
            if ($logoUrl = $this->request->getPost('logo_url')) {
                $logoUrl = trim($logoUrl);
                if (!empty($logoUrl)) {
                    $data['logo'] = $logoUrl;
                }
            } elseif ($brand['logo']) {
                // Keep existing logo if no new file and no URL provided
                $data['logo'] = $brand['logo'];
            }
        }

        $this->brandModel->update($id, $data);

        return redirect()->to(site_url('admin/brands'))->with('success', 'Brand updated successfully!');
    }

    /**
     * Delete brand
     */
    public function delete($id)
    {
        $brand = $this->brandModel->find($id);
        
        if (!$brand) {
            return redirect()->to(site_url('admin/brands'))->with('error', 'Brand not found.');
        }

        $this->brandModel->delete($id);

        return redirect()->to(site_url('admin/brands'))->with('success', 'Brand deleted successfully!');
    }
}

