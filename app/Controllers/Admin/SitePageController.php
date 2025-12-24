<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SitePageModel;

/**
 * Admin controller for managing CMS site pages
 * (Terms & Conditions, Privacy Policy, FAQ)
 */
class SitePageController extends BaseController
{
    protected $pageModel;

    public function __construct()
    {
        $this->pageModel = new SitePageModel();
    }

    /**
     * List all site pages
     */
    public function index()
    {
        $data = [
            'title'     => 'Site Pages',
            'pageTitle' => 'Manage Site Pages',
            'pages'     => $this->pageModel->getAllPages(),
        ];

        return view('admin/site_pages/index', $data);
    }

    /**
     * Show edit form for a page
     */
    public function edit($slug)
    {
        $page = $this->pageModel->where('slug', $slug)->first();
        
        if (!$page) {
            return redirect()->to(site_url('admin/site-pages'))->with('error', 'Page not found.');
        }

        $data = [
            'title'     => 'Edit ' . $page['title'],
            'pageTitle' => 'Edit ' . $page['title'],
            'page'      => $page,
        ];

        return view('admin/site_pages/edit', $data);
    }

    /**
     * Update a page
     */
    public function update($slug)
    {
        $page = $this->pageModel->where('slug', $slug)->first();
        
        if (!$page) {
            return redirect()->to(site_url('admin/site-pages'))->with('error', 'Page not found.');
        }

        $rules = [
            'title' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title'            => $this->request->getPost('title'),
            'content'          => $this->request->getPost('content'),
            'meta_title'       => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->pageModel->update($page['id'], $data);

        return redirect()->to(site_url('admin/site-pages'))->with('success', 'Page updated successfully!');
    }
}
