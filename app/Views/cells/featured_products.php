<section class="featured-products">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="products-carousel">
        <?php foreach ($products as $product): ?>
            <div class="product-card featured">
                <div class="product-image">
                    <?php if (isset($product['badge'])): ?>
                        <span class="product-badge"><?= $product['badge'] ?></span>
                    <?php endif; ?>
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" loading="lazy">
                </div>
                <div class="product-details">
                    <h3><?= $product['name'] ?></h3>
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <span class="star <?= $i < floor($product['rating']) ? 'filled' : '' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="reviews">(<?= $product['reviews'] ?> reviews)</span>
                    </div>
                    <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                    <button class="btn btn-primary btn-block">Add to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carousel-nav">
        <button class="carousel-prev">←</button>
        <button class="carousel-next">→</button>
    </div>
</section>
