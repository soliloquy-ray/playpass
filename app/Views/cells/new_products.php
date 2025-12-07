<section class="new-products">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card new">
                <div class="product-image">
                    <span class="badge-new">NEW</span>
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" loading="lazy">
                </div>
                <div class="product-details">
                    <h3><?= $product['name'] ?></h3>
                    <p class="product-date"><?= $product['date'] ?></p>
                    <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                    <button class="btn btn-primary btn-block">Shop Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
