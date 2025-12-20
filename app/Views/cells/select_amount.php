<section class="section-component" style="margin-bottom: 30px;">
    <h3 class="section-label">Select Amount</h3>
    
    <div class="amount-grid">
        <?php foreach ($products as $product): ?>
            <div class="amount-card" onclick="selectAmount(this, <?= $product['id'] ?>)">
                <h4><?= $product['name'] ?></h4>
                <div class="amount-price">PHP <?= number_format($product['price'], 2) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
// Initialize global variable
if (typeof window.selectedProductId === 'undefined') {
    window.selectedProductId = null;
}

function selectAmount(element, id) {
    document.querySelectorAll('.amount-card').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    
    // Store selected product ID globally for checkout
    window.selectedProductId = id;
    console.log('Product selected:', id);
}
</script>