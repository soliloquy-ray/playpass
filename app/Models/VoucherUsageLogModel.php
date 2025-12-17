<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherUsageLogModel extends Model
{
    protected $table            = 'voucher_usage_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'user_id',
        'campaign_id',
        'voucher_code_id',
        'order_id',
        'used_at',
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    /**
     * Get usage count for a user and campaign
     * 
     * @param int $userId
     * @param int $campaignId
     * @return int
     */
    public function getUserUsageCount(int $userId, int $campaignId): int
    {
        return $this->where('user_id', $userId)
                    ->where('campaign_id', $campaignId)
                    ->countAllResults();
    }

    /**
     * Log voucher usage
     * 
     * @param int $userId
     * @param int $campaignId
     * @param int $voucherCodeId
     * @param int|null $orderId
     * @return int|false
     */
    public function logUsage(int $userId, int $campaignId, int $voucherCodeId, ?int $orderId = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'voucher_code_id' => $voucherCodeId,
            'order_id' => $orderId,
            'used_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

