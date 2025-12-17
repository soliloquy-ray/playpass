<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromosModel;

class PromoController extends BaseController
{
    protected $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromosModel();
    }

    /**
     * List all promos
     */
    public function index()
    {
        $data = [
            'title' => 'Promos',
            'pageTitle' => 'Manage Promos',
            'promos' => $this->promoModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/promos/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Promo',
            'pageTitle' => 'Add New Promo',
        ];

        return view('admin/promos/form', $data);
    }

    /**
     * Create new promo
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload using raw $_FILES array
        $uploadPath = FCPATH . 'uploads/promos';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Use raw $_FILES directly
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK && $_FILES['icon']['size'] > 0) {
            $tmpName = $_FILES['icon']['tmp_name'];
            $originalName = $_FILES['icon']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid() . '_' . time() . '.' . $extension;
            $destination = $uploadPath . '/' . $newName;
            
            if (move_uploaded_file($tmpName, $destination)) {
                $data['icon'] = '/uploads/promos/' . $newName;
            }
        }
        
        // Only use icon_url if no file was uploaded and URL is provided
        if (empty($data['icon']) && ($iconUrl = $this->request->getPost('icon_url'))) {
            $iconUrl = trim($iconUrl);
            if (!empty($iconUrl)) {
                $data['icon'] = $iconUrl;
            }
        }

        $this->promoModel->insert($data);

        return redirect()->to(site_url('admin/promos'))->with('success', 'Promo created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/promos'))->with('error', 'Promo not found.');
        }

        $data = [
            'title' => 'Edit Promo',
            'pageTitle' => 'Edit Promo',
            'promo' => $promo,
        ];

        return view('admin/promos/form', $data);
    }

    /**
     * Update promo
     */
    public function update($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/promos'))->with('error', 'Promo not found.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload using raw $_FILES array
        $uploadPath = FCPATH . 'uploads/promos';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Use raw $_FILES directly
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK && $_FILES['icon']['size'] > 0) {
            $tmpName = $_FILES['icon']['tmp_name'];
            $originalName = $_FILES['icon']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = uniqid() . '_' . time() . '.' . $extension;
            $destination = $uploadPath . '/' . $newName;
            
            if (move_uploaded_file($tmpName, $destination)) {
                $data['icon'] = '/uploads/promos/' . $newName;
            }
        }
        
        // Only use icon_url if no file was uploaded and URL is provided
        // Also preserve existing icon if no new file and no URL provided
        if (empty($data['icon'])) {
            if ($iconUrl = $this->request->getPost('icon_url')) {
                $iconUrl = trim($iconUrl);
                if (!empty($iconUrl)) {
                    $data['icon'] = $iconUrl;
                }
            } elseif ($promo['icon']) {
                // Keep existing icon if no new file and no URL provided
                $data['icon'] = $promo['icon'];
            }
        }

        $this->promoModel->update($id, $data);

        return redirect()->to(site_url('admin/promos'))->with('success', 'Promo updated successfully!');
    }

    /**
     * Delete promo
     */
    public function delete($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/promos'))->with('error', 'Promo not found.');
        }

        $this->promoModel->delete($id);

        return redirect()->to(site_url('admin/promos'))->with('success', 'Promo deleted successfully!');
    }
}

