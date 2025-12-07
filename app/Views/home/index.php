<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Welcome to Playpass</h1>

<!-- Hero section -->
<div style="background-color:#051429; border-radius:8px; padding:20px; margin-bottom:30px;">
    <h2>To the Moon</h2>
    <p>Now Streaming</p>
    <a href="#" class="btn btn-secondary">Buy Now</a>
</div>

<!-- New Products Section -->
<h2>New</h2>
<div style="display:flex; flex-wrap:wrap; gap:20px;">
    <?php if (! empty($newProducts)): ?>
        <?php foreach ($newProducts as $product): ?>
            <div style="width:150px; background-color:#051429; padding:10px; border-radius:8px;">
                <div style="height:200px; background-color:#252525; border-radius:4px; margin-bottom:10px;">
                    <?php if (! empty($product['thumbnail_url'])): ?>
                        <img src="<?= esc($product['thumbnail_url']) ?>" alt="<?= esc($product['name']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div style="width:100%; height:100%; background-color:#333; display:flex; align-items:center; justify-content:center; color:#666;">Image</div>
                    <?php endif; ?>
                </div>
                <p style="margin:0; font-weight:bold;"><?= esc($product['name']) ?></p>
                <p style="margin:0; font-size:0.9rem;">₱<?= number_format($product['price'], 2) ?></p>
                <a href="#" class="btn btn-secondary" style="margin-top:5px;">Buy Now</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No new products available.</p>
    <?php endif; ?>
</div>

<!-- Featured Products Section -->
<h2 style="margin-top:40px;">Featured Products</h2>
<div style="display:flex; flex-wrap:wrap; gap:20px;">
    <?php if (! empty($featuredProducts)): ?>
        <?php foreach ($featuredProducts as $product): ?>
            <div style="width:150px; background-color:#051429; padding:10px; border-radius:8px;">
                <div style="height:200px; background-color:#252525; border-radius:4px; margin-bottom:10px;">
                    <?php if (! empty($product['thumbnail_url'])): ?>
                        <img src="<?= esc($product['thumbnail_url']) ?>" alt="<?= esc($product['name']) ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div style="width:100%; height:100%; background-color:#333; display:flex; align-items:center; justify-content:center; color:#666;">Image</div>
                    <?php endif; ?>
                </div>
                <p style="margin:0; font-weight:bold;"><?= esc($product['name']) ?></p>
                <p style="margin:0; font-size:0.9rem;">₱<?= number_format($product['price'], 2) ?></p>
                <a href="#" class="btn btn-secondary" style="margin-top:5px;">Buy Now</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No featured products available.</p>
    <?php endif; ?>
</div>

<!-- Placeholder for Promos and Stories -->
<h2 style="margin-top:40px;">Promos</h2>
<p>Promotional offers coming soon.</p>

<h2 style="margin-top:40px;">Stories</h2>
<p>Latest stories and trailers will appear here.</p>

<?= $this->endSection() ?>