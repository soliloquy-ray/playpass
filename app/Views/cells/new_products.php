<section class="new-products">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="products-grid">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
            <div class="product-card new">
                <div class="product-image">
                    <span class="badge-new">NEW</span>
                    <img src="<?= $product['image'] ?>" alt="<?= esc($product['name']) ?>" loading="lazy">
                </div>
                <?php 
                // Check if product has a brand_id to link to brand page
                $hasBrand = isset($product['brand_id']) && !empty($product['brand_id']);
                if ($hasBrand): 
                    $brandName = strtolower($product['brand_name'] ?? '');
                    $brandClass = '';
                    // Determine brand-specific button style
                    if (strpos($brandName, 'viu') !== false) {
                        $brandClass = 'viu';
                    } elseif (strpos($brandName, 'viva') !== false || strpos($brandName, 'viva one') !== false) {
                        $brandClass = 'viva-one';
                    } elseif (strpos($brandName, 'cignal') !== false) {
                        $brandClass = 'cignalplay';
                    }
                ?>
                    <a href="/app/buy/<?= esc($product['brand_id']) ?>" class="btn btn-brand btn-block <?= esc($brandClass) ?>">
                        <?php if (!empty($product['brand_logo'])): ?>
                            <img src="<?= esc($product['brand_logo']) ?>" alt="<?= esc($product['brand_name'] ?? 'Brand') ?>" style="height: 20px; width: auto; max-width: 100px; object-fit: contain;">
                        <?php elseif (!empty($product['brand_name'])): ?>
                            <?= esc($product['brand_name']) ?>
                        <?php else: ?>
                            Shop Now
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <button class="btn btn-primary btn-block">Shop Now</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #999; text-align: center; padding: 20px;">No new products available at this time.</p>
        <?php endif; ?>
    </div>
</section>
