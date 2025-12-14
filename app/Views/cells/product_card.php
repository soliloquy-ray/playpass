<div class="product-card">
    <div class="product-image">
        <?php if ($image): ?>
            <img src="<?= esc($image) ?>" alt="<?= esc($product['name']) ?>">
        <?php else: ?>
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.8rem;">
                No Image
            </div>
        <?php endif; ?>
        
        <div class="product-badges">
            <?php if ($isBundle): ?>
                <span class="badge">BUNDLE</span>
            <?php endif; ?>
            <?php if (isset($product['is_sale']) && $product['is_sale']): ?>
                <span class="badge badge-warning">SALE</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-info">
        <h3 class="product-name"><?= esc($product['name']) ?></h3>
        
        <div class="product-meta">
            <span class="product-price">₱<?= $formattedPrice ?></span>
        </div>

        <?php if ($showPoints): ?>
            <p class="product-points">+<?= $product['points_to_earn'] ?> Pts</p>
        <?php endif; ?>

        <a href="/app/buy/<?= esc($product['id']) ?>" class="btn btn-secondary btn-small product-btn">
            Buy Now
        </a>
    </div>
</div>