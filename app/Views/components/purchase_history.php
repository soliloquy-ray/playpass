<?php
// Purchase History Component
// Expected variables: 
//   $transactions (array of transaction data)
//   $filterType (string: 'all', 'transaction', 'redeemed', 'earned')
//   $dateFrom (string or null)
//   $dateTo (string or null)
?>

<div class="purchase-history-card">
    <h3 class="purchase-history-title">PURCHASE HISTORY</h3>
    
    <div class="purchase-history-filters">
        <div class="filter-buttons">
            <span class="filter-label">Filter:</span>
            <a href="<?= site_url('app/account?filter=all' . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'all' ? 'active' : '' ?>">All</a>
            <a href="<?= site_url('app/account?filter=transaction' . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'transaction' ? 'active' : '' ?>">Transaction</a>
            <a href="<?= site_url('app/account?filter=redeemed' . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'redeemed' ? 'active' : '' ?>">Redeemed</a>
            <a href="<?= site_url('app/account?filter=earned' . ($dateFrom ? '&date_from=' . urlencode($dateFrom) : '') . ($dateTo ? '&date_to=' . urlencode($dateTo) : '')) ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'earned' ? 'active' : '' ?>">Earned</a>
        </div>
        <div class="date-filters">
            <span class="date-label">Date:</span>
            <form method="GET" action="<?= site_url('app/account') ?>" class="date-filter-form">
                <input type="hidden" name="filter" value="<?= esc($filterType ?? 'all') ?>">
                <div class="date-input-group">
                    <label>From</label>
                    <input type="date" name="date_from" value="<?= esc($dateFrom ?? '') ?>" class="date-input">
                </div>
                <div class="date-input-group">
                    <label>To</label>
                    <input type="date" name="date_to" value="<?= esc($dateTo ?? '') ?>" class="date-input">
                </div>
                <button type="submit" class="btn-apply-filter">Apply</button>
            </form>
        </div>
    </div>
    
    <div class="purchase-history-list">
        <?php if (empty($transactions)): ?>
            <p class="no-transactions">No transactions found.</p>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <div class="transaction-item">
                    <div class="transaction-header">
                        <span class="transaction-date">
                            <?php 
                            $date = $transaction['date'] ?? '';
                            if ($date && strtotime($date)) {
                                echo date('M. d, Y h:i A', strtotime($date));
                            } else {
                                echo 'Date not available';
                            }
                            ?>
                        </span>
                        <span class="transaction-amount">
                            Total Amount: PHP <?= number_format($transaction['amount'] ?? 0, 2) ?>
                        </span>
                    </div>
                    <p class="transaction-description">
                        <?= esc($transaction['description'] ?? 'Transaction') ?>
                    </p>
                    <?php if (isset($transaction['payment_status']) || isset($transaction['fulfillment_status'])): ?>
                    <div class="transaction-status">
                        <?php if (!empty($transaction['payment_status'])): ?>
                            <?php
                            $paymentClasses = [
                                'paid' => 'status-success',
                                'pending' => 'status-pending',
                                'failed' => 'status-failed',
                            ];
                            $paymentClass = $paymentClasses[$transaction['payment_status']] ?? 'status-neutral';
                            ?>
                            <span class="status-badge <?= $paymentClass ?>">
                                <i class="fas fa-credit-card"></i> <?= ucfirst($transaction['payment_status']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($transaction['fulfillment_status'])): ?>
                            <?php
                            $fulfillmentClasses = [
                                'sent' => 'status-success',
                                'processing' => 'status-pending',
                                'pending' => 'status-pending',
                                'failed' => 'status-failed',
                            ];
                            $fulfillmentClass = $fulfillmentClasses[$transaction['fulfillment_status']] ?? 'status-neutral';
                            ?>
                            <span class="status-badge <?= $fulfillmentClass ?>">
                                <i class="fas fa-truck"></i> <?= ucfirst($transaction['fulfillment_status']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.transaction-status {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    flex-wrap: wrap;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}
.status-success {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}
.status-pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}
.status-failed {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}
.status-neutral {
    background: rgba(156, 163, 175, 0.15);
    color: #9ca3af;
}
</style>
