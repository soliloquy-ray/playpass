<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Carousel -->
<div class="hero-carousel">
    <!-- Slide 1 -->
    <div class="carousel-slide active">
        <div class="carousel-content">
            <h1>To the Moon 🚀</h1>
            <p>Now Streaming via Playpass</p>
            <a href="/products" class="btn btn-primary">Explore Now</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #d8369f 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-slide">
        <div class="carousel-content">
            <h1>Gaming Bundles 🎮</h1>
            <p>Exclusive packages for hardcore gamers</p>
            <a href="/products?category=bundles" class="btn btn-primary">Shop Bundles</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #4caf50 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-slide">
        <div class="carousel-content">
            <h1>Get 50% Off 🎉</h1>
            <p>First-time top-up promotion</p>
            <a href="/login" class="btn btn-primary">Get Started</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #ff9800 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <div class="carousel-controls">
        <button class="carousel-dot active"></button>
        <button class="carousel-dot"></button>
        <button class="carousel-dot"></button>
    </div>

    <!-- Carousel Arrows -->
    <button class="carousel-arrow prev">❮</button>
    <button class="carousel-arrow next">❯</button>
</div>

<!-- New Arrivals Section -->
<section class="section">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 15px; margin-bottom: 20px;">
        <h2 class="section-title" style="margin: 0;">New Arrivals</h2>
        <a href="/products" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">View All →</a>
    </div>

    <div class="grid grid-auto">
        <?php if (! empty($newProducts)): ?>
            <?php foreach ($newProducts as $product): ?>
                <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-muted); padding: 0 15px;">No new products available yet.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Section -->
<?php if (! empty($featuredProducts)): ?>
<section class="section">
    <h2 class="section-title">Featured</h2>

    <div class="grid grid-auto">
        <?php foreach ($featuredProducts as $product): ?>
            <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Latest Stories Section -->
<section class="section">
    <h2 class="section-title">Latest Stories</h2>

    <div class="grid grid-2">
        <?php if (! empty($latestArticles)): ?>
            <?php foreach ($latestArticles as $article): ?>
                <?= view_cell('App\Cells\ArticleCell::renderCard', ['article' => $article]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-muted); padding: 0 15px;">No stories published yet.</p>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>