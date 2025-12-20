<section class="section-component" style="margin-bottom: 30px;">
    <h3 class="section-label">Select Payment Channel</h3>
    
    <div class="payment-grid">
        <?php foreach ($channels as $channel): ?>
            <div class="payment-card" onclick="selectPayment(this, '<?= $channel['code'] ?>')">
                <?php if(!empty($channel['logo'])): ?>
                    <img src="<?= asset_url($channel['logo']) ?>" class="payment-logo" alt="<?= esc($channel['name']) ?>" style="display: block; width: 100%; height: auto;">
                <?php else: ?>
                    <div style="width:100%; height:40px; background:#333; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                        <i class="fa fa-credit-card" style="color:#aaa;"></i>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
// Initialize global variable
if (typeof window.selectedPaymentMethod === 'undefined') {
    window.selectedPaymentMethod = 'gcash';
}

function selectPayment(element, code) {
    document.querySelectorAll('.payment-card').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    
    // Store selected payment method globally
    window.selectedPaymentMethod = code;
    console.log('Payment method selected:', code);
    
    if (typeof cartState !== 'undefined') {
        cartState.paymentMethod = code;
    }
}
</script>