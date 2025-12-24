<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CompanyInfoModel;

/**
 * Admin controller for managing company information
 * (address, phone, email, copyright)
 */
class CompanyInfoController extends BaseController
{
    protected $infoModel;

    public function __construct()
    {
        $this->infoModel = new CompanyInfoModel();
    }

    /**
     * Show company info edit form
     */
    public function index()
    {
        $data = [
            'title'     => 'Company Information',
            'pageTitle' => 'Manage Company Information',
            'info'      => $this->infoModel->getAllWithLabels(),
        ];

        return view('admin/company_info/index', $data);
    }

    /**
     * Save company info
     */
    public function save()
    {
        $info = $this->infoModel->getAllWithLabels();
        
        foreach ($info as $item) {
            $value = $this->request->getPost($item['key']);
            if ($value !== null) {
                $this->infoModel->updateByKey($item['key'], $value);
            }
        }

        return redirect()->to(site_url('admin/company-info'))->with('success', 'Company information updated successfully!');
    }
}
