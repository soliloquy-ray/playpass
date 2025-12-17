<?php
// Vouchers and Discounts Card Component
// Expected variables: $vouchers (array of voucher data)
?>

<div class="vouchers-card">
    <h3 class="vouchers-card-title">VOUCHERS AND DISCOUNTS</h3>
    <div class="vouchers-list">
        <?php if (empty($vouchers)): ?>
            <p class="no-vouchers">No vouchers available at this time.</p>
        <?php else: ?>
            <?php foreach ($vouchers as $voucher): ?>
                <div class="voucher-item">
                    <div class="voucher-description">
                        <?php
                        $description = $voucher['description'] ?? '';
                        if (empty($description)) {
                            // Generate description from voucher data
                            if ($voucher['discount_type'] === 'percentage') {
                                $description = $voucher['discount_value'] . '% Cashback for every P' . number_format($voucher['min_spend'] ?? 500) . ' purchase';
                            } else {
                                $description = 'P' . number_format($voucher['discount_value'] ?? 10) . ' off for a minimum purchase of P' . number_format($voucher['min_spend'] ?? 100);
                            }
                        }
                        ?>
                        <p><?= esc($description) ?></p>
                    </div>
                    <a href="<?= site_url('app/account/voucher/use/' . $voucher['id']) ?>" class="btn-use-voucher">Use Voucher</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

