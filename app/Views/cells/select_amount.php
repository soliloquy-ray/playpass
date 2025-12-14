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
function selectAmount(element, id) {
    document.querySelectorAll('.amount-card').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    // Set hidden input value if inside a form
    // document.getElementById('selected_product').value = id;
}
</script>