<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ProductSyncService;
use App\Models\ProductModel;

class ProductSyncController extends BaseController
{
    protected $syncService;
    protected $productModel;

    public function __construct()
    {
        $this->syncService = new ProductSyncService();
        $this->productModel = new ProductModel();
    }

    /**
     * Show sync status and product list
     */
    public function index()
    {
        $status = $this->syncService->getSyncStatus();

        return view('admin/product_sync', [
            'status' => $status,
            'title' => 'StreamingPH Product Sync'
        ]);
    }

    /**
     * Sync/Refresh balances
     */
    public function sync()
    {
        $result = $this->syncService->syncBalances();

        if ($result['success']) {
            return redirect()->to('/admin/products/sync')->with('message', $result['message']);
        }

        return redirect()->to('/admin/products/sync')->with('error', $result['message']);
    }

    /**
     * AJAX Endpoint to map a local product to a StreamingPH product
     */
    public function mapProduct()
    {
        $localId = $this->request->getPost('local_id');
        $streamingPhId = $this->request->getPost('streamingph_id');

        if (!$localId || !$streamingPhId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing ID parameters']);
        }

        $result = $this->syncService->mapProduct((int)$localId, $streamingPhId);

        return $this->response->setJSON($result);
    }
    
    /**
     * AJAX Endpoint to unmap
     */
    public function unmapProduct()
    {
        $localId = $this->request->getPost('local_id');
        
        if (!$localId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing ID parameters']);
        }

        $result = $this->syncService->unmapProduct((int)$localId);

        return $this->response->setJSON($result);
    }
}
