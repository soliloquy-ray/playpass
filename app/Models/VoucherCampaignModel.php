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

