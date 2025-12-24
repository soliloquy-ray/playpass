<?php

namespace App\Libraries;

use App\Models\ProductModel;

/**
 * ProductSyncService
 * 
 * Handles syncing products from StreamingPH API to local database
 */
class ProductSyncService
{
    protected $streamingPH;
    protected $productModel;

    public function __construct()
    {
        $this->streamingPH = new StreamingPHService();
        $this->productModel = new ProductModel();
    }

    /**
     * Fetch all products from StreamingPH API
     * 
     * @return array ['success' => bool, 'products' => array, 'message' => string]
     */
    public function fetchRemoteProducts(): array
    {
        $response = $this->streamingPH->getProducts();

        if (!isset($response['status']) || $response['status'] !== 'success') {
            return [
                'success' => false,
                'products' => [],
                'message' => $response['value'] ?? 'Failed to fetch products from StreamingPH'
            ];
        }

        return [
            'success' => true,
            'products' => $response['value'] ?? [],
            'message' => 'Products fetched successfully'
        ];
    }

    /**
     * Get sync status: compare local products with remote API products
     * 
     * @return array ['local' => array, 'remote' => array, 'mapped' => array, 'unmapped' => array]
     */
    public function getSyncStatus(): array
    {
        // Fetch remote products
        $remoteResult = $this->fetchRemoteProducts();
        $remoteProducts = $remoteResult['products'];

        // Fetch local products
        $localProducts = $this->productModel
            ->select('id, name, sku, price, streamingph_product_id, streamingph_balance, is_active')
            ->findAll();

        // Build maps for comparison
        $mappedProductIds = [];
        $mappedLocal = [];
        $unmappedLocal = [];

        foreach ($localProducts as $local) {
            if (!empty($local['streamingph_product_id'])) {
                $mappedProductIds[] = $local['streamingph_product_id'];
                $mappedLocal[] = $local;
            } else {
                $unmappedLocal[] = $local;
            }
        }

        // Find unmapped remote products
        $unmappedRemote = [];
        $mappedRemote = [];
        foreach ($remoteProducts as $remote) {
            if (in_array($remote['productID'], $mappedProductIds)) {
                $mappedRemote[] = $remote;
            } else {
                $unmappedRemote[] = $remote;
            }
        }

        return [
            'local_total' => count($localProducts),
            'remote_total' => count($remoteProducts),
            'mapped_count' => count($mappedLocal),
            'local_products' => $localProducts,
            'remote_products' => $remoteProducts,
            'mapped_local' => $mappedLocal,
            'unmapped_local' => $unmappedLocal,
            'unmapped_remote' => $unmappedRemote,
            'api_success' => $remoteResult['success'],
            'api_message' => $remoteResult['message'],
        ];
    }

    /**
     * Map a local product to a StreamingPH product ID
     * 
     * @param int $localProductId Local product ID
     * @param string $streamingphProductId StreamingPH productID from API
     * @return array ['success' => bool, 'message' => string]
     */
    public function mapProduct(int $localProductId, string $streamingphProductId): array
    {
        $product = $this->productModel->find($localProductId);

        if (!$product) {
            return ['success' => false, 'message' => 'Local product not found'];
        }

        // Verify the StreamingPH product exists
        $remoteResult = $this->fetchRemoteProducts();
        if (!$remoteResult['success']) {
            return ['success' => false, 'message' => 'Could not verify StreamingPH product'];
        }

        $remoteProduct = null;
        foreach ($remoteResult['products'] as $remote) {
            if ($remote['productID'] === $streamingphProductId) {
                $remoteProduct = $remote;
                break;
            }
        }

        if (!$remoteProduct) {
            return ['success' => false, 'message' => 'StreamingPH product ID not found in API'];
        }

        // Update local product with StreamingPH data
        $updateData = [
            'streamingph_product_id' => $streamingphProductId,
            'streamingph_validity' => $remoteProduct['validity'] ?? null,
            'streamingph_balance' => $remoteProduct['balance'] ?? null,
        ];

        $this->productModel->update($localProductId, $updateData);

        return [
            'success' => true,
            'message' => "Product mapped successfully to StreamingPH product: {$remoteProduct['name']} ({$remoteProduct['type']})"
        ];
    }

    /**
     * Unmap a local product from StreamingPH
     * 
     * @param int $localProductId Local product ID
     * @return array ['success' => bool, 'message' => string]
     */
    public function unmapProduct(int $localProductId): array
    {
        $product = $this->productModel->find($localProductId);

        if (!$product) {
            return ['success' => false, 'message' => 'Local product not found'];
        }

        $this->productModel->update($localProductId, [
            'streamingph_product_id' => null,
            'streamingph_validity' => null,
            'streamingph_balance' => null,
        ]);

        return ['success' => true, 'message' => 'Product unmapped successfully'];
    }

    /**
     * Sync balance/stock info from StreamingPH for all mapped products
     * 
     * @return array ['success' => bool, 'updated' => int, 'message' => string]
     */
    public function syncBalances(): array
    {
        $remoteResult = $this->fetchRemoteProducts();
        if (!$remoteResult['success']) {
            return ['success' => false, 'updated' => 0, 'message' => $remoteResult['message']];
        }

        // Build lookup by productID
        $remoteByProductId = [];
        foreach ($remoteResult['products'] as $remote) {
            $remoteByProductId[$remote['productID']] = $remote;
        }

        // Get all mapped local products (where streamingph_product_id is NOT null or empty)
        $localProducts = $this->productModel
            ->where('streamingph_product_id IS NOT NULL')
            ->where('streamingph_product_id !=', '')
            ->findAll();

        $updatedCount = 0;
        foreach ($localProducts as $local) {
            $productId = $local['streamingph_product_id'];
            if (isset($remoteByProductId[$productId])) {
                $remote = $remoteByProductId[$productId];
                $this->productModel->update($local['id'], [
                    'streamingph_balance' => $remote['balance'] ?? null,
                    'streamingph_validity' => $remote['validity'] ?? null,
                ]);
                $updatedCount++;
            }
        }

        return [
            'success' => true,
            'updated' => $updatedCount,
            'message' => "Updated balance info for {$updatedCount} products"
        ];
    }

    /**
     * Get wallet balance from StreamingPH
     * 
     * @return array ['success' => bool, 'balance' => mixed, 'message' => string]
     */
    public function getWalletBalance(): array
    {
        $response = $this->streamingPH->checkBalance();

        if (!isset($response['status']) || $response['status'] !== 'success') {
            return [
                'success' => false,
                'balance' => null,
                'message' => $response['value'] ?? 'Failed to fetch wallet balance'
            ];
        }

        return [
            'success' => true,
            'balance' => $response['value']['balance'] ?? 0,
            'message' => 'Balance fetched successfully'
        ];
    }
}
