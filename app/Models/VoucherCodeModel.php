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
        $builder->select('voucher_codes.*, voucher_campaigns.name, voucher_campaigns.discount_type, voucher_campaigns.discount_value, voucher_campaigns.min_spend_amount, voucher_campaigns.is_stackable');
        $builder->join('voucher_campaigns', 'voucher_campaigns.id = voucher_codes.campaign_id');
        
        $builder->where('voucher_codes.code', $code);
        $builder->where('voucher_codes.is_redeemed', 0); // Must be unused
        
        // Date Logic (Rule: "Invalidating... by setting date")
        $now = date('Y-m-d H:i:s');
        $builder->where('voucher_campaigns.start_date <=', $now);
        $builder->where('voucher_campaigns.end_date >=', $now);

        return $builder->get()->getRowArray();
    }
}