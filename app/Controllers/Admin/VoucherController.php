<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VoucherCampaignModel;
use App\Models\VoucherCodeModel;
use App\Models\ProductModel;

class VoucherController extends BaseController
{
    protected $campaignModel;
    protected $codeModel;
    protected $productModel;

    public function __construct()
    {
        $this->campaignModel = new VoucherCampaignModel();
        $this->codeModel = new VoucherCodeModel();
        $this->productModel = new ProductModel();
    }

    /**
     * List all voucher campaigns
     */
    public function index()
    {
        $campaigns = $this->campaignModel->findAll();
        
        // Get usage counts for each campaign
        $db = \Config\Database::connect();
        foreach ($campaigns as &$campaign) {
            // For unique batch: count redeemed codes
            if ($campaign['code_type'] === 'unique_batch') {
                $campaign['used_count'] = $this->codeModel
                    ->where('campaign_id', $campaign['id'])
                    ->where('is_redeemed', 1)
                    ->countAllResults();
                $campaign['total_codes'] = $this->codeModel
                    ->where('campaign_id', $campaign['id'])
                    ->countAllResults();
            } else {
                // For universal: count from usage log
                $campaign['used_count'] = $db->table('voucher_usage_log')
                    ->where('campaign_id', $campaign['id'])
                    ->countAllResults();
                $campaign['total_codes'] = 1; // Universal has 1 code
            }
        }

        $data = [
            'title' => 'Vouchers',
            'pageTitle' => 'Manage Voucher Campaigns',
            'campaigns' => $campaigns,
        ];

        return view('admin/vouchers/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        
        $data = [
            'title' => 'Add Voucher Campaign',
            'pageTitle' => 'Create Voucher Campaign',
            'products' => $products,
        ];

        return view('admin/vouchers/form', $data);
    }

    /**
     * Create new campaign
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'code_type' => 'required|in_list[universal,unique_batch]',
            'discount_type' => 'required|in_list[fixed_amount,percentage]',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $campaignData = [
            'name' => $this->request->getPost('name'),
            'label' => $this->request->getPost('label') ?: $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'code_type' => $this->request->getPost('code_type'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_spend_amount' => $this->request->getPost('min_spend_amount') ?: 0,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') ?: null,
            'usage_limit_per_user' => $this->request->getPost('usage_limit_per_user') ?: 1,
            'total_usage_limit' => $this->request->getPost('total_usage_limit') ?: null,
            'is_stackable' => $this->request->getPost('is_stackable') ? 1 : 0,
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
        ];

        $campaignId = $this->campaignModel->insert($campaignData);

        // Handle product applicability
        $applicableProducts = $this->request->getPost('applicable_products');
        if ($applicableProducts && is_array($applicableProducts)) {
            $this->campaignModel->setApplicableProducts($campaignId, $applicableProducts);
        }

        // Handle voucher codes
        $codeType = $this->request->getPost('code_type');
        
        if ($codeType === 'universal') {
            // Create single universal code
            $code = strtoupper($this->request->getPost('voucher_code'));
            if ($code) {
                $this->codeModel->insert([
                    'campaign_id' => $campaignId,
                    'code' => $code,
                ]);
            }
        } else {
            // Generate batch of unique codes
            $batchSize = (int)$this->request->getPost('batch_size') ?: 100;
            $prefix = strtoupper($this->request->getPost('code_prefix')) ?: 'PLAY';
            
            $codes = [];
            for ($i = 0; $i < $batchSize; $i++) {
                $codes[] = [
                    'campaign_id' => $campaignId,
                    'code' => $prefix . '-' . strtoupper(bin2hex(random_bytes(4))),
                ];
            }
            $this->codeModel->insertBatch($codes);
        }

        return redirect()->to(site_url('admin/vouchers'))->with('success', 'Voucher campaign created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $campaign = $this->campaignModel->find($id);
        
        if (!$campaign) {
            return redirect()->to(site_url('admin/vouchers'))->with('error', 'Campaign not found.');
        }

        $codes = $this->codeModel->where('campaign_id', $id)->findAll(100);
        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $applicableProductIds = $this->campaignModel->getApplicableProducts($id);

        $data = [
            'title' => 'Edit Campaign',
            'pageTitle' => 'Edit Voucher Campaign',
            'campaign' => $campaign,
            'codes' => $codes,
            'products' => $products,
            'applicableProductIds' => $applicableProductIds,
        ];

        return view('admin/vouchers/form', $data);
    }

    /**
     * Update campaign
     */
    public function update($id)
    {
        $campaign = $this->campaignModel->find($id);
        
        if (!$campaign) {
            return redirect()->to(site_url('admin/vouchers'))->with('error', 'Campaign not found.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'discount_type' => 'required|in_list[fixed_amount,percentage]',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $campaignData = [
            'name' => $this->request->getPost('name'),
            'label' => $this->request->getPost('label') ?: $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_spend_amount' => $this->request->getPost('min_spend_amount') ?: 0,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') ?: null,
            'usage_limit_per_user' => $this->request->getPost('usage_limit_per_user') ?: 1,
            'total_usage_limit' => $this->request->getPost('total_usage_limit') ?: null,
            'is_stackable' => $this->request->getPost('is_stackable') ? 1 : 0,
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
        ];

        $this->campaignModel->update($id, $campaignData);

        // Handle product applicability
        $applicableProducts = $this->request->getPost('applicable_products');
        if ($applicableProducts !== null) {
            $this->campaignModel->setApplicableProducts($id, is_array($applicableProducts) ? $applicableProducts : []);
        }

        return redirect()->to(site_url('admin/vouchers'))->with('success', 'Voucher campaign updated successfully!');
    }

    /**
     * Delete campaign
     */
    public function delete($id)
    {
        $campaign = $this->campaignModel->find($id);
        
        if (!$campaign) {
            return redirect()->to(site_url('admin/vouchers'))->with('error', 'Campaign not found.');
        }

        // Delete associated codes first
        $this->codeModel->where('campaign_id', $id)->delete();
        $this->campaignModel->delete($id);

        return redirect()->to(site_url('admin/vouchers'))->with('success', 'Voucher campaign deleted successfully!');
    }

    /**
     * View campaign codes
     */
    public function codes($campaignId)
    {
        $campaign = $this->campaignModel->find($campaignId);
        
        if (!$campaign) {
            return redirect()->to(site_url('admin/vouchers'))->with('error', 'Campaign not found.');
        }

        $data = [
            'title' => 'Voucher Codes',
            'pageTitle' => 'Voucher Codes: ' . $campaign['name'],
            'campaign' => $campaign,
            'codes' => $this->codeModel->where('campaign_id', $campaignId)->orderBy('id', 'DESC')->findAll(),
        ];

        return view('admin/vouchers/codes', $data);
    }

    /**
     * Generate more codes for a campaign
     */
    public function generateCodes($campaignId)
    {
        $campaign = $this->campaignModel->find($campaignId);
        
        if (!$campaign || $campaign['code_type'] !== 'unique_batch') {
            return redirect()->back()->with('error', 'Cannot generate codes for this campaign.');
        }

        $batchSize = (int)$this->request->getPost('batch_size') ?: 100;
        $prefix = strtoupper($this->request->getPost('code_prefix')) ?: 'PLAY';
        
        $codes = [];
        for ($i = 0; $i < $batchSize; $i++) {
            $codes[] = [
                'campaign_id' => $campaignId,
                'code' => $prefix . '-' . strtoupper(bin2hex(random_bytes(4))),
            ];
        }
        $this->codeModel->insertBatch($codes);

        return redirect()->back()->with('success', "{$batchSize} codes generated successfully!");
    }
}

