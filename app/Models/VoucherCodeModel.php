<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherCodeModel extends Model
{
    protected $table            = 'voucher_codes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['campaign_id', 'code', 'is_redeemed', 'redeemed_at', 'redeemed_by_user_id'];
    protected $useTimestamps    = false; // We handle dates manually in logic

    /**
     * Strategic Function: Find a valid code ensuring it matches all Rules
     * @param string $code The code typed by user
     * @return array|null The joined campaign data if valid
     */
    public function getValidVoucherByCode(string $code)
    {
        $db = \Config\Database::connect();
        
        // Join with Campaign to check dates and active status
        $builder = $this->db->table('voucher_codes');
        $builder->select('voucher_codes.*, voucher_campaigns.name, voucher_campaigns.label, voucher_campaigns.code_type, voucher_campaigns.discount_type, voucher_campaigns.discount_value, voucher_campaigns.min_spend_amount, voucher_campaigns.max_discount_amount, voucher_campaigns.usage_limit_per_user, voucher_campaigns.total_usage_limit, voucher_campaigns.is_stackable, voucher_campaigns.start_date, voucher_campaigns.end_date');
        $builder->join('voucher_campaigns', 'voucher_campaigns.id = voucher_codes.campaign_id');
        
        $builder->where('voucher_codes.code', $code);
        
        // For unique batch vouchers, must be unused. For universal, we check usage limits separately
        // Note: Universal vouchers can be used multiple times, so we don't filter by is_redeemed here
        
        // Date Logic (Rule: "Invalidating... by setting date")
        // We'll do timezone-aware checking in VoucherEngine, but basic check here
        $now = date('Y-m-d H:i:s');
        $builder->where('voucher_campaigns.start_date <=', $now);
        $builder->where('voucher_campaigns.end_date >=', $now);

        $result = $builder->get()->getRowArray();
        
        // For unique batch vouchers, check if already redeemed
        if ($result && isset($result['code_type']) && $result['code_type'] === 'unique_batch' && $result['is_redeemed']) {
            return null;
        }

        return $result;
    }
}