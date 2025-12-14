<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\BrandModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $brandModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->brandModel = new BrandModel();
    }

    /**
     * List all products
     */
    public function index()
    {
        $data = [
            'title' => 'Products',
            'pageTitle' => 'Manage Products',
            'products' => $this->productModel
                ->select('products.*, brands.name as brand_name')
                ->join('brands', 'brands.id = products.brand_id', 'left')
                ->orderBy('products.created_at', 'DESC')
                ->findAll(),
            'brands' => $this->brandModel->findAll(),
        ];

        return view('admin/products/index', $data);
    }

    /**
     * Show create form
     */
    public function new()
    {
        $data = [
            'title' => 'Add Product',
            'pageTitle' => 'Add New Product',
            'brands' => $this->brandModel->where('is_enabled', 1)->findAll(),
        ];

        return view('admin/products/form', $data);
    }

    /**
     * Create new product
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'sku' => 'required|is_unique[products.sku]',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'brand_id' => $this->request->getPost('brand_id') ?: null,
            'thumbnail_url' => $this->request->getPost('thumbnail_url'),
            'bg_color' => $this->request->getPost('bg_color') ?: '#1a1a1a',
            'badge_label' => $this->request->getPost('badge_label'),
            'maya_product_code' => $this->request->getPost('maya_product_code'),
            'points_to_earn' => $this->request->getPost('points_to_earn') ?: 0,
            'is_bundle' => $this->request->getPost('is_bundle') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_new' => $this->request->getPost('is_new') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $data['thumbnail_url'] = '/uploads/products/' . $newName;
        }

        $this->productModel->insert($data);

        return redirect()->to('/admin/products')->with('success', 'Product created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        $data = [
            'title' => 'Edit Product',
            'pageTitle' => 'Edit Product',
            'product' => $product,
            'brands' => $this->brandModel->where('is_enabled', 1)->findAll(),
        ];

        return view('admin/products/form', $data);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'sku' => "required|is_unique[products.sku,id,{$id}]",
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'brand_id' => $this->request->getPost('brand_id') ?: null,
            'bg_color' => $this->request->getPost('bg_color') ?: '#1a1a1a',
            'badge_label' => $this->request->getPost('badge_label'),
            'maya_product_code' => $this->request->getPost('maya_product_code'),
            'points_to_earn' => $this->request->getPost('points_to_earn') ?: 0,
            'is_bundle' => $this->request->getPost('is_bundle') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_new' => $this->request->getPost('is_new') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle file upload
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $data['thumbnail_url'] = '/uploads/products/' . $newName;
        } elseif ($this->request->getPost('thumbnail_url')) {
            $data['thumbnail_url'] = $this->request->getPost('thumbnail_url');
        }

        $this->productModel->update($id, $data);

        return redirect()->to('/admin/products')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        $this->productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Product deleted successfully!');
    }
}

