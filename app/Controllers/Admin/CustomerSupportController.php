<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerSupportModel;

class CustomerSupportController extends BaseController
{
    protected $supportModel;

    public function __construct()
    {
        $this->supportModel = new CustomerSupportModel();
    }

    /**
     * List all customer support channels
     */
    public function index()
    {
        $data = [
            'title' => 'Customer Support',
            'pageTitle' => 'Manage Customer Support Channels',
            'channels' => $this->supportModel->orderBy('sort_order', 'ASC')->findAll(),
        ];

        return view('admin/customer-support/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Support Channel',
            'pageTitle' => 'Add New Support Channel',
        ];

        return view('admin/customer-support/form', $data);
    }

    /**
     * Create new support channel
     */
    public function create()
    {
        $rules = [
            'label' => 'required|min_length[2]|max_length[100]',
            'link' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'label' => $this->request->getPost('label'),
            'link' => $this->request->getPost('link'),
            'icon' => $this->request->getPost('icon') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->supportModel->insert($data);

        return redirect()->to('/admin/customer-support')->with('success', 'Support channel created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $channel = $this->supportModel->find($id);
        
        if (!$channel) {
            return redirect()->to('/admin/customer-support')->with('error', 'Support channel not found.');
        }

        $data = [
            'title' => 'Edit Support Channel',
            'pageTitle' => 'Edit Support Channel',
            'channel' => $channel,
        ];

        return view('admin/customer-support/form', $data);
    }

    /**
     * Update support channel
     */
    public function update($id)
    {
        $channel = $this->supportModel->find($id);
        
        if (!$channel) {
            return redirect()->to('/admin/customer-support')->with('error', 'Support channel not found.');
        }

        $rules = [
            'label' => 'required|min_length[2]|max_length[100]',
            'link' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'label' => $this->request->getPost('label'),
            'link' => $this->request->getPost('link'),
            'icon' => $this->request->getPost('icon') ?: null,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->supportModel->update($id, $data);

        return redirect()->to('/admin/customer-support')->with('success', 'Support channel updated successfully!');
    }

    /**
     * Delete support channel
     */
    public function delete($id)
    {
        $channel = $this->supportModel->find($id);
        
        if (!$channel) {
            return redirect()->to('/admin/customer-support')->with('error', 'Support channel not found.');
        }

        $this->supportModel->delete($id);

        return redirect()->to('/admin/customer-support')->with('success', 'Support channel deleted successfully!');
    }
}
