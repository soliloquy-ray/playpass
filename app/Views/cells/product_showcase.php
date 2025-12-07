<div class="product-showcase">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 15px; margin-bottom: 20px;">
        <h2 class="section-title" style="margin: 0;"><?= esc($title) ?></h2>
        <a href="<?= esc($view_all_url) ?>" style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">
            View All →
        </a>
    </div>

    <!-- Showcase Grid -->
    <div class="grid grid-auto" style="padding: 0 15px;">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-muted);">No products to display.</p>
        <?php endif; ?>
    </div>
</div>
