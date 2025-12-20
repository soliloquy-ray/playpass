<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PointLedgerModel - The "bank statement" for user points.
 * 
 * Points are tracked as ledger entries:
 * - Positive amounts = earned (purchase_reward, referral_bonus, adjustment)
 * - Negative amounts = spent (redemption, adjustment)
 * 
 * Expiration: Points expire 1 year from earning. Redemptions use FIFO
 * (oldest non-expired points are consumed first).
 */
class PointLedgerModel extends Model
{
    protected $table            = 'point_ledger';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'user_id',
        'amount',
        'transaction_type',
        'reference_id',
        'earned_at',
        'expires_at',
        'notes',
        'created_at',
    ];

    protected $useTimestamps = false; // We manually set created_at for precision

    // Transaction types
    public const TYPE_PURCHASE_REWARD = 'purchase_reward';
    public const TYPE_REDEMPTION      = 'redemption';
    public const TYPE_REFERRAL_BONUS  = 'referral_bonus';
    public const TYPE_ADJUSTMENT      = 'adjustment';

    /**
     * Get the current valid (non-expired) points balance for a user.
     * This considers only non-expired positive entries minus all negative entries.
     */
    public function getBalance(int $userId): int
    {
        $now = date('Y-m-d H:i:s');
        
        // Sum all non-expired earnings
        $earnings = $this->selectSum('amount', 'total')
            ->where('user_id', $userId)
            ->where('amount >', 0)
            ->groupStart()
                ->where('expires_at IS NULL')
                ->orWhere('expires_at >', $now)
            ->groupEnd()
            ->get()
            ->getRowArray();
        
        $earnedTotal = (int)($earnings['total'] ?? 0);

        // Sum all redemptions (negative amounts, no expiration on these)
        $redemptions = $this->selectSum('amount', 'total')
            ->where('user_id', $userId)
            ->where('amount <', 0)
            ->get()
            ->getRowArray();
        
        $redeemedTotal = (int)($redemptions['total'] ?? 0); // This is negative

        return max(0, $earnedTotal + $redeemedTotal);
    }

    /**
     * Get total points ever earned by user (ignores expiration for display).
     */
    public function getEarningTotal(int $userId): int
    {
        $result = $this->selectSum('amount', 'total')
            ->where('user_id', $userId)
            ->where('amount >', 0)
            ->get()
            ->getRowArray();
        
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get total points ever redeemed by user.
     */
    public function getRedemptionTotal(int $userId): int
    {
        $result = $this->selectSum('amount', 'total')
            ->where('user_id', $userId)
            ->where('amount <', 0)
            ->get()
            ->getRowArray();
        
        return abs((int)($result['total'] ?? 0));
    }

    /**
     * Get user transactions for dashboard display with optional filters.
     */
    public function getUserTransactions(
        int $userId, 
        string $filterType = 'all', 
        ?string $dateFrom = null, 
        ?string $dateTo = null,
        int $limit = 50
    ): array {
        $builder = $this->where('user_id', $userId);
        
        if ($filterType === 'earned') {
            $builder->where('amount >', 0);
        } elseif ($filterType === 'redeemed') {
            $builder->where('amount <', 0);
        }
        
        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        return $builder->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Earn points for a user. Sets expiration to 1 year from now.
     * 
     * @param int $userId
     * @param int $amount Must be positive
     * @param string|null $referenceId Order ID or other reference
     * @param string $type Transaction type
     * @return int|false Insert ID or false on failure
     */
    public function earnPoints(
        int $userId, 
        int $amount, 
        ?string $referenceId = null, 
        string $type = self::TYPE_PURCHASE_REWARD
    ) {
        if ($amount <= 0) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));

        return $this->insert([
            'user_id'          => $userId,
            'amount'           => $amount, // Always positive for earning
            'transaction_type' => $type,
            'reference_id'     => $referenceId,
            'earned_at'        => $now,
            'expires_at'       => $expiresAt,
            'created_at'       => $now,
        ]);
    }

    /**
     * Redeem points from user balance. Creates a negative ledger entry.
     * Validates sufficient non-expired balance before redemption.
     * 
     * @param int $userId
     * @param int $points Points to redeem (positive number)
     * @param int|null $orderId Reference order ID
     * @return int|false Insert ID or false if insufficient balance
     */
    public function redeemPoints(int $userId, int $points, ?int $orderId = null)
    {
        if ($points <= 0) {
            return false;
        }

        // Check sufficient balance (non-expired)
        $currentBalance = $this->getBalance($userId);
        if ($currentBalance < $points) {
            return false;
        }

        $now = date('Y-m-d H:i:s');

        return $this->insert([
            'user_id'          => $userId,
            'amount'           => -$points, // Negative for redemption
            'transaction_type' => self::TYPE_REDEMPTION,
            'reference_id'     => $orderId ? "order_{$orderId}" : null,
            'earned_at'        => null, // Redemptions don't earn, no expiration
            'expires_at'       => null,
            'created_at'       => $now,
        ]);
    }

    /**
     * Adjust points (for admin corrections). Can be positive or negative.
     * Negative adjustments are per requirement #3 for deductions with notes.
     */
    public function adjustPoints(int $userId, int $amount, ?string $notes = null)
    {
        if ($amount === 0) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            'user_id'          => $userId,
            'amount'           => $amount,
            'transaction_type' => self::TYPE_ADJUSTMENT,
            'reference_id'     => null,
            'notes'            => $notes,
            'created_at'       => $now,
        ];

        // Only positive adjustments get expiration
        if ($amount > 0) {
            $data['earned_at'] = $now;
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime('+1 year'));
        }

        return $this->insert($data);
    }

    /**
     * Check if user has sufficient non-expired points for redemption.
     */
    public function hasEnoughPoints(int $userId, int $requiredPoints): bool
    {
        return $this->getBalance($userId) >= $requiredPoints;
    }

    /**
     * Update the cached balance on user record for performance.
     */
    public function syncUserBalance(int $userId): bool
    {
        $balance = $this->getBalance($userId);
        
        $userModel = new UserModel();
        return $userModel->update($userId, [
            'current_points_balance' => $balance
        ]);
    }
}
