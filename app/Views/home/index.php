<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero section -->
<div style="background-color:#051429; border-radius:12px; padding:40px; margin-bottom:40px; display:flex; align-items:center; justify-content:space-between; background: linear-gradient(90deg, #051429 0%, #0d2b52 100%);">
    <div>
        <h1 style="font-size:2.5rem; margin:0 0 10px 0;">To the Moon</h1>
        <p style="font-size:1.2rem; color:#a0c4ff; margin-bottom:20px;">Now Streaming via Playpass</p>
        <a href="#" class="btn btn-secondary" style="padding:12px 30px;">Buy Now</a>
    </div>
    <!-- Placeholder for Hero Image -->
    <div style="width:200px; height:150px; background:rgba(255,255,255,0.1); border-radius:8px;"></div>
</div>

<!-- New Arrivals -->
<div style="margin-bottom:40px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">New Arrivals</h2>
        <a href="/products" style="color:#888; text-decoration:none; font-size:0.9rem;">View All</a>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php if (! empty($newProducts)): ?>
            <?php foreach ($newProducts as $product): ?>
                <!-- Call the Product Cell -->
                <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#666;">No new products available.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Featured -->
<?php if (! empty($featuredProducts)): ?>
<div style="margin-bottom:40px;">
    <h2 style="margin-bottom:20px;">Featured</h2>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php foreach ($featuredProducts as $product): ?>
            <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Latest Stories -->
<div style="margin-bottom:40px;">
    <h2 style="margin-bottom:20px;">Latest Stories</h2>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php if (! empty($latestArticles)): ?>
            <?php foreach ($latestArticles as $article): ?>
                <!-- Call the Article Cell -->
                <?= view_cell('App\Cells\ArticleCell::renderCard', ['article' => $article]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#666;">No stories published yet.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>