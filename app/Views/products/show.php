<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 1000px; margin: 0 auto;">
        <!-- Product Image -->
        <div>
            <div style="background-color: var(--card-bg); border-radius: 12px; overflow: hidden; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <?php if (! empty($product['thumbnail_url'])): ?>
                    <img src="<?= asset_url($product['thumbnail_url']) ?>" alt="<?= esc($product['name']) ?>" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="color: var(--text-muted); text-align: center;">
                        <p style="font-size: 3rem; margin: 0;">📦</p>
                        <p>No image available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Gallery (if available) -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                <div style="aspect-ratio: 1; background-color: var(--card-bg); border-radius: 8px; border: 2px solid var(--primary); cursor: pointer;"></div>
                <div style="aspect-ratio: 1; background-color: var(--card-bg); border-radius: 8px; cursor: pointer;"></div>
                <div style="aspect-ratio: 1; background-color: var(--card-bg); border-radius: 8px; cursor: pointer;"></div>
                <div style="aspect-ratio: 1; background-color: var(--card-bg); border-radius: 8px; cursor: pointer;"></div>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <div style="margin-bottom: 20px;">
                <?php if ($product['is_bundle'] ?? false): ?>
                    <span class="badge">BUNDLE</span>
                <?php endif; ?>
            </div>

            <h1 style="margin-bottom: 10px;"><?= esc($product['name']) ?></h1>
            
            <p style="color: var(--text-muted); margin-bottom: 20px; line-height: 1.6;">
                <?= esc($product['description']) ?>
            </p>

            <!-- Rating (if available) -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <div style="color: #ffd700;">★★★★☆</div>
                <span style="color: var(--text-muted);">(24 reviews)</span>
            </div>

            <!-- Price & Points -->
            <div style="background-color: var(--card-bg); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 15px;">
                    <div>
                        <p style="color: var(--text-muted); margin-bottom: 5px;">Price</p>
                        <h2 style="color: var(--success); margin: 0;">₱<?= number_format($product['price'], 2) ?></h2>
                    </div>
                    <?php if (($product['points_to_earn'] ?? 0) > 0): ?>
                        <div style="text-align: right;">
                            <p style="color: var(--text-muted); margin-bottom: 5px;">Earn Points</p>
                            <h3 style="color: #ffd700; margin: 0;">+<?= $product['points_to_earn'] ?></h3>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (($product['original_price'] ?? 0) > $product['price']): ?>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="text-decoration: line-through; color: var(--text-muted);">
                            ₱<?= number_format($product['original_price'], 2) ?>
                        </span>
                        <span style="color: var(--warning); font-weight: 700;">
                            Save <?= round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) ?>%
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <button class="btn btn-primary btn-large" onclick="window.location.href='<?= site_url('app/checkout?product=' . esc($product['id'])) ?>'">
                    Buy Now
                </button>
                <button class="btn btn-secondary btn-large" onclick="alert('Added to wishlist')">
                    ♡ Wishlist
                </button>
            </div>

            <!-- Key Features -->
            <div style="background-color: var(--card-bg); padding: 20px; border-radius: 8px;">
                <h4 style="margin-top: 0;">What You Get</h4>
                <ul style="margin: 0; padding-left: 20px; color: var(--text-light);">
                    <li style="margin-bottom: 8px;">Instant digital delivery</li>
                    <li style="margin-bottom: 8px;">Lifetime access</li>
                    <li style="margin-bottom: 8px;">Secure transaction</li>
                    <li style="margin-bottom: 8px;">24/7 customer support</li>
                </ul>
            </div>

            <!-- Seller Info -->
            <div style="background-color: var(--card-bg); padding: 20px; border-radius: 8px; margin-top: 20px; display: flex; align-items: center; gap: 15px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, #4caf50 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem;">
                    P
                </div>
                <div>
                    <p style="margin: 0; font-weight: 700;">Playpass Official Store</p>
                    <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Verified Seller • 99% Positive</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div style="margin-top: 60px;">
        <h2 style="margin-bottom: 20px;">You May Also Like</h2>
        <div class="grid grid-auto">
            <div style="background-color: var(--card-bg); padding: 15px; border-radius: 8px; text-align: center; color: var(--text-muted);">
                Related products would appear here
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
