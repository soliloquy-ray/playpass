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
                    <div class="voucher-info">
                        <?php if (!empty($voucher['code'])): ?>
                            <div class="voucher-code-container">
                                <span class="voucher-code"><?= esc($voucher['code']) ?></span>
                                <button type="button" class="btn-copy-code" onclick="copyVoucherCode('<?= esc($voucher['code']) ?>', this)" title="Copy code">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <p class="voucher-description">
                            <?php
                            $description = $voucher['description'] ?? '';
                            if (empty($description)) {
                                if (($voucher['discount_type'] ?? '') === 'percentage') {
                                    $description = ($voucher['discount_value'] ?? 0) . '% off';
                                } else {
                                    $description = '₱' . number_format($voucher['discount_value'] ?? 0) . ' off';
                                }
                                if (($voucher['min_spend'] ?? 0) > 0) {
                                    $description .= ' (min. ₱' . number_format($voucher['min_spend']) . ')';
                                }
                            }
                            echo esc($description);
                            ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.voucher-code-container {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.voucher-code {
    font-family: monospace;
    font-size: 1.1rem;
    font-weight: 700;
    color: #d8369f;
    background: rgba(216, 54, 159, 0.1);
    padding: 6px 12px;
    border-radius: 6px;
    letter-spacing: 1px;
}
.btn-copy-code {
    background: transparent;
    border: 1px solid #d8369f;
    color: #d8369f;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-copy-code:hover {
    background: #d8369f;
    color: white;
}
.voucher-description {
    color: #a0a0a0;
    font-size: 0.9rem;
    margin: 0;
}
</style>

<script>
function copyVoucherCode(code, btn) {
    navigator.clipboard.writeText(code).then(function() {
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = '#10b981';
        btn.style.borderColor = '#10b981';
        btn.style.color = 'white';
        setTimeout(function() {
            btn.innerHTML = originalContent;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 2000);
    });
}
</script>

