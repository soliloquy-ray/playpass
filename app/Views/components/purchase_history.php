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
            <a href="/app/account?filter=all<?= $dateFrom ? '&date_from=' . urlencode($dateFrom) : '' ?><?= $dateTo ? '&date_to=' . urlencode($dateTo) : '' ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'all' ? 'active' : '' ?>">All</a>
            <a href="/app/account?filter=transaction<?= $dateFrom ? '&date_from=' . urlencode($dateFrom) : '' ?><?= $dateTo ? '&date_to=' . urlencode($dateTo) : '' ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'transaction' ? 'active' : '' ?>">Transaction</a>
            <a href="/app/account?filter=redeemed<?= $dateFrom ? '&date_from=' . urlencode($dateFrom) : '' ?><?= $dateTo ? '&date_to=' . urlencode($dateTo) : '' ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'redeemed' ? 'active' : '' ?>">Redeemed</a>
            <a href="/app/account?filter=earned<?= $dateFrom ? '&date_from=' . urlencode($dateFrom) : '' ?><?= $dateTo ? '&date_to=' . urlencode($dateTo) : '' ?>" 
               class="filter-btn <?= ($filterType ?? 'all') === 'earned' ? 'active' : '' ?>">Earned</a>
        </div>
        <div class="date-filters">
            <span class="date-label">Date:</span>
            <form method="GET" action="/app/account" class="date-filter-form">
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
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

