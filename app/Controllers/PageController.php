<?php

namespace App\Controllers;

use App\Models\SitePageModel;
use App\Models\CompanyInfoModel;

/**
 * Public controller for displaying site pages
 * (Terms & Conditions, Privacy Policy, FAQ)
 */
class PageController extends BaseController
{
    protected $pageModel;
    protected $companyModel;

    public function __construct()
    {
        $this->pageModel = new SitePageModel();
        $this->companyModel = new CompanyInfoModel();
    }

    /**
     * Display a site page by slug
     */
    public function show($slug)
    {
        $page = $this->pageModel->getBySlug($slug);
        
        if (!$page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'       => $page['meta_title'] ?: $page['title'],
            'description' => $page['meta_description'] ?: '',
            'page'        => $page,
            'companyInfo' => $this->companyModel->getAll(),
        ];

        return view('page', $data);
    }

    /**
     * Terms and Conditions page
     */
    public function terms()
    {
        return $this->show('terms');
    }

    /**
     * Privacy Policy page
     */
    public function privacy()
    {
        return $this->show('privacy');
    }

    /**
     * FAQ page
     */
    public function faq()
    {
        return $this->show('faq');
    }
}
