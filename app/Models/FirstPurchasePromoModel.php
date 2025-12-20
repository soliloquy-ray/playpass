<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * FirstPurchasePromoModel
 * 
 * Manages first purchase promo settings and product applicability.
 */
class FirstPurchasePromoModel extends Model
{
    protected $table            = 'first_purchase_promos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name',
        'label',
        'is_active',
        'discount_type',
        'discount_value',
        'min_spend_amount',
        'max_discount_amount',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get the currently active first purchase promo.
     * Returns the first active promo (typically only one should be active).
     */
    public function getActivePromo(): ?array
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Get product IDs applicable to a promo.
     * Empty array means all products are applicable.
     */
    public function getApplicableProducts(int $promoId): array
    {
        $db = \Config\Database::connect();
        $results = $db->table('first_purchase_promo_products')
            ->select('product_id')
            ->where('promo_id', $promoId)
            ->get()
            ->getResultArray();

        return array_column($results, 'product_id');
    }

    /**
     * Set applicable products for a promo.
     */
    public function setApplicableProducts(int $promoId, array $productIds): bool
    {
        $db = \Config\Database::connect();

        // Delete existing
        $db->table('first_purchase_promo_products')
            ->where('promo_id', $promoId)
            ->delete();

        // Insert new
        if (!empty($productIds)) {
            $data = [];
            foreach ($productIds as $productId) {
                $data[] = [
                    'promo_id' => $promoId,
                    'product_id' => (int)$productId,
                ];
            }
            return $db->table('first_purchase_promo_products')->insertBatch($data) !== false;
        }

        return true;
    }

    /**
     * Check if promo applies to any of the given products.
     * If no products are configured, applies to all.
     */
    public function appliesToProducts(int $promoId, array $cartProductIds): bool
    {
        $applicableProducts = $this->getApplicableProducts($promoId);

        // Empty means applies to all products
        if (empty($applicableProducts)) {
            return true;
        }

        // Check if any cart product is in the applicable list
        return !empty(array_intersect($cartProductIds, $applicableProducts));
    }
}
