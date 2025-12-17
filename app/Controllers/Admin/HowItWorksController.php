<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Model;

class HowItWorksController extends BaseController
{
    protected $model;

    public function __construct()
    {
        // Create a simple model for how_it_works table
        $this->model = new class extends Model {
            protected $table = 'how_it_works';
            protected $primaryKey = 'id';
            protected $allowedFields = ['title', 'description', 'icon', 'sort_order', 'is_active'];
            protected $useTimestamps = true;
        };
    }

    /**
     * List all steps
     */
    public function index()
    {
        $data = [
            'title' => 'How It Works',
            'pageTitle' => 'Manage How It Works Steps',
            'steps' => $this->model->orderBy('sort_order', 'ASC')->findAll(),
        ];

        return view('admin/how-it-works/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Step',
            'pageTitle' => 'Add New Step',
        ];

        return view('admin/how-it-works/form', $data);
    }

    /**
     * Create new step
     */
    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[100]',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->model->insert($data);

        return redirect()->to(site_url('admin/how-it-works'))->with('success', 'Step created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $step = $this->model->find($id);
        
        if (!$step) {
            return redirect()->to(site_url('admin/how-it-works'))->with('error', 'Step not found.');
        }

        $data = [
            'title' => 'Edit Step',
            'pageTitle' => 'Edit Step',
            'step' => $step,
        ];

        return view('admin/how-it-works/form', $data);
    }

    /**
     * Update step
     */
    public function update($id)
    {
        $step = $this->model->find($id);
        
        if (!$step) {
            return redirect()->to(site_url('admin/how-it-works'))->with('error', 'Step not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[100]',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->model->update($id, $data);

        return redirect()->to(site_url('admin/how-it-works'))->with('success', 'Step updated successfully!');
    }

    /**
     * Delete step
     */
    public function delete($id)
    {
        $step = $this->model->find($id);
        
        if (!$step) {
            return redirect()->to(site_url('admin/how-it-works'))->with('error', 'Step not found.');
        }

        $this->model->delete($id);

        return redirect()->to(site_url('admin/how-it-works'))->with('success', 'Step deleted successfully!');
    }

    /**
     * Update sort order via AJAX
     */
    public function updateOrder()
    {
        $order = $this->request->getJSON(true);
        
        if ($order && isset($order['items'])) {
            foreach ($order['items'] as $index => $id) {
                $this->model->update($id, ['sort_order' => $index]);
            }
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }
}

