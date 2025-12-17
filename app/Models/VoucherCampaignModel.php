<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherCampaignModel extends Model
{
    protected $table            = 'voucher_campaigns';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'name',
        'label',
        'description', 
        'code_type', 
        'discount_type', 
        'discount_value',
        'min_spend_amount', 
        'max_discount_amount', 
        'usage_limit_per_user',
        'total_usage_limit', 
        'is_stackable', 
        'start_date', 
        'end_date'
    ];
    
    protected $useTimestamps = false; // Timestamps handled manually in migration
    
    /**
     * Get available voucher campaigns that are currently active
     * 
     * @return array
     */
    public function getAvailableVouchers(): array
    {
        $now = date('Y-m-d H:i:s');
        
        return $this->where('start_date <=', $now)
                    ->where('end_date >=', $now)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get products applicable to this campaign
     * 
     * @param int $campaignId
     * @return array
     */
    public function getApplicableProducts(int $campaignId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('voucher_applicability');
        $builder->select('product_id');
        $builder->where('campaign_id', $campaignId);
        $results = $builder->get()->getResultArray();
        
        return array_column($results, 'product_id');
    }

    /**
     * Set applicable products for a campaign
     * 
     * @param int $campaignId
     * @param array $productIds
     * @return bool
     */
    public function setApplicableProducts(int $campaignId, array $productIds): bool
    {
        $db = \Config\Database::connect();
        
        // Delete existing
        $db->table('voucher_applicability')->where('campaign_id', $campaignId)->delete();
        
        // Insert new
        if (!empty($productIds)) {
            $data = [];
            foreach ($productIds as $productId) {
                $data[] = [
                    'campaign_id' => $campaignId,
                    'product_id' => (int)$productId,
                ];
            }
            return $db->table('voucher_applicability')->insertBatch($data) !== false;
        }
        
        return true;
    }

    /**
     * Check if campaign is currently active (timezone-aware)
     * 
     * @param array $campaign
     * @return bool
     */
    public function isActive(array $campaign): bool
    {
        $timezone = new \DateTimeZone('Asia/Manila'); // Philippines timezone
        $now = new \DateTime('now', $timezone);
        $start = new \DateTime($campaign['start_date'], $timezone);
        $end = new \DateTime($campaign['end_date'], $timezone);
        
        return ($now >= $start && $now <= $end);
    }

    /**
     * Format voucher campaign data for display
     * 
     * @param array $campaign
     * @return array
     */
    public function formatVoucherForDisplay(array $campaign): array
    {
        $formatted = [
            'id' => $campaign['id'],
            'name' => $campaign['name'] ?? '',
            'label' => $campaign['label'] ?? $campaign['name'] ?? '',
            'description' => $campaign['description'] ?? '',
            'discount_type' => $campaign['discount_type'] ?? 'fixed_amount',
            'discount_value' => $campaign['discount_value'] ?? 0,
            'min_spend' => $campaign['min_spend_amount'] ?? 0,
            'is_stackable' => (bool) ($campaign['is_stackable'] ?? false),
            'start_date' => $campaign['start_date'] ?? null,
            'end_date' => $campaign['end_date'] ?? null,
        ];
        
        // Format discount display
        if ($formatted['discount_type'] === 'percentage') {
            $formatted['discount_display'] = $formatted['discount_value'] . '% OFF';
        } else {
            $formatted['discount_display'] = '₱' . number_format($formatted['discount_value'], 2) . ' OFF';
        }
        
        // Format minimum spend display
        if ($formatted['min_spend'] > 0) {
            $formatted['min_spend_display'] = 'Min. spend: ₱' . number_format($formatted['min_spend'], 2);
        } else {
            $formatted['min_spend_display'] = 'No minimum spend';
        }
        
        return $formatted;
    }
}

