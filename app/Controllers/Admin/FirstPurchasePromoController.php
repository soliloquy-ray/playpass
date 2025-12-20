<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FirstPurchasePromoModel;
use App\Models\ProductModel;

/**
 * Admin controller for managing First Purchase Promos
 */
class FirstPurchasePromoController extends BaseController
{
    protected $promoModel;
    protected $productModel;

    public function __construct()
    {
        $this->promoModel = new FirstPurchasePromoModel();
        $this->productModel = new ProductModel();
    }

    /**
     * List all first purchase promos
     */
    public function index()
    {
        $promos = $this->promoModel->orderBy('created_at', 'DESC')->findAll();
        
        // Get usage count for each promo
        $db = \Config\Database::connect();
        foreach ($promos as &$promo) {
            // Count users with has_completed_first_purchase = 1
            $promo['used_count'] = $db->table('users')
                ->where('has_completed_first_purchase', 1)
                ->countAllResults();
        }

        $data = [
            'title' => 'First Purchase Promos',
            'pageTitle' => 'Manage First Purchase Promos',
            'promos' => $promos,
        ];

        return view('admin/first-purchase/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        
        $data = [
            'title' => 'Add First Purchase Promo',
            'pageTitle' => 'Create First Purchase Promo',
            'products' => $products,
        ];

        return view('admin/first-purchase/form', $data);
    }

    /**
     * Create new promo
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'discount_type' => 'required|in_list[fixed_amount,percentage]',
            'discount_value' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $promoData = [
            'name' => $this->request->getPost('name'),
            'label' => $this->request->getPost('label') ?: $this->request->getPost('name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_spend_amount' => $this->request->getPost('min_spend_amount') ?: 0,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') ?: null,
        ];

        $promoId = $this->promoModel->insert($promoData);

        // Handle product applicability
        $applicableProducts = $this->request->getPost('applicable_products');
        if ($applicableProducts && is_array($applicableProducts)) {
            $this->promoModel->setApplicableProducts($promoId, $applicableProducts);
        }

        return redirect()->to(site_url('admin/first-purchase-promos'))->with('success', 'First purchase promo created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/first-purchase-promos'))->with('error', 'Promo not found.');
        }

        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $applicableProductIds = $this->promoModel->getApplicableProducts($id);

        $data = [
            'title' => 'Edit First Purchase Promo',
            'pageTitle' => 'Edit First Purchase Promo',
            'promo' => $promo,
            'products' => $products,
            'applicableProductIds' => $applicableProductIds,
        ];

        return view('admin/first-purchase/form', $data);
    }

    /**
     * Update promo
     */
    public function update($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/first-purchase-promos'))->with('error', 'Promo not found.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'discount_type' => 'required|in_list[fixed_amount,percentage]',
            'discount_value' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $promoData = [
            'name' => $this->request->getPost('name'),
            'label' => $this->request->getPost('label') ?: $this->request->getPost('name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'discount_type' => $this->request->getPost('discount_type'),
            'discount_value' => $this->request->getPost('discount_value'),
            'min_spend_amount' => $this->request->getPost('min_spend_amount') ?: 0,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') ?: null,
        ];

        $this->promoModel->update($id, $promoData);

        // Handle product applicability
        $applicableProducts = $this->request->getPost('applicable_products');
        $this->promoModel->setApplicableProducts($id, is_array($applicableProducts) ? $applicableProducts : []);

        return redirect()->to(site_url('admin/first-purchase-promos'))->with('success', 'First purchase promo updated successfully!');
    }

    /**
     * Delete promo
     */
    public function delete($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/first-purchase-promos'))->with('error', 'Promo not found.');
        }

        $this->promoModel->delete($id);

        return redirect()->to(site_url('admin/first-purchase-promos'))->with('success', 'First purchase promo deleted successfully!');
    }

    /**
     * Toggle promo active status
     */
    public function toggleStatus($id)
    {
        $promo = $this->promoModel->find($id);
        
        if (!$promo) {
            return redirect()->to(site_url('admin/first-purchase-promos'))->with('error', 'Promo not found.');
        }

        $this->promoModel->update($id, [
            'is_active' => $promo['is_active'] ? 0 : 1,
        ]);

        $status = $promo['is_active'] ? 'deactivated' : 'activated';
        return redirect()->to(site_url('admin/first-purchase-promos'))->with('success', "First purchase promo {$status} successfully!");
    }
}
