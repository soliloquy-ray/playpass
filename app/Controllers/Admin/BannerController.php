<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TopBannerModel;

class BannerController extends BaseController
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new TopBannerModel();
    }

    /**
     * List all banners (or show/edit the single active one)
     */
    public function index()
    {
        $banner = $this->bannerModel->getActiveBanner();
        
        // If no banner exists, create an empty one for editing
        if (!$banner) {
            $banner = [
                'id' => null,
                'text' => '',
                'icon' => 'fa-bolt',
                'is_active' => 0
            ];
        }

        $data = [
            'title' => 'Top Banner',
            'pageTitle' => 'Manage Top Banner',
            'banner' => $banner,
        ];

        return view('admin/banner/index', $data);
    }

    /**
     * Create or update banner
     */
    public function save()
    {
        $rules = [
            'text' => 'required|min_length[1]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->request->getPost('id');
        $data = [
            'text' => $this->request->getPost('text'),
            'icon' => $this->request->getPost('icon') ?: 'fa-bolt',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // If there's an existing active banner and we're activating a new one, deactivate the old one
        if ($data['is_active'] == 1) {
            $existingActive = $this->bannerModel->getActiveBanner();
            if ($existingActive && $existingActive['id'] != $id) {
                $this->bannerModel->update($existingActive['id'], ['is_active' => 0]);
            }
        }

        if ($id) {
            // Update existing
            $banner = $this->bannerModel->find($id);
            if (!$banner) {
                return redirect()->to(site_url('admin/banner'))->with('error', 'Banner not found.');
            }
            $this->bannerModel->update($id, $data);
            $message = 'Banner updated successfully!';
        } else {
            // Create new
            $this->bannerModel->insert($data);
            $message = 'Banner created successfully!';
        }

        return redirect()->to(site_url('admin/banner'))->with('success', $message);
    }
}
