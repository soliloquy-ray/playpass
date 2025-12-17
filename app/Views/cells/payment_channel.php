<section class="section-component" style="margin-bottom: 30px;">
    <h3 class="section-label">Select Payment Channel</h3>
    
    <div class="payment-grid">
        <?php foreach ($channels as $channel): ?>
            <div class="payment-card" onclick="selectPayment(this, '<?= $channel['code'] ?>')">
                <?php if(!empty($channel['logo'])): ?>
                    <img src="<?= asset_url($channel['logo']) ?>" class="payment-logo" alt="<?= esc($channel['name']) ?>">
                <?php else: ?>
                    <div style="width:32px; height:32px; background:#333; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                        <i class="fa fa-credit-card" style="color:#aaa;"></i>
                    </div>
                <?php endif; ?>
                
                <span class="payment-name"><?= $channel['name'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
function selectPayment(element, code) {
    document.querySelectorAll('.payment-card').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
}
</script>