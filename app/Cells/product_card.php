<div class="product-card" style="width:160px; background-color:#051429; padding:12px; border-radius:12px; position:relative; display:flex; flex-direction:column; justify-content:space-between;">
    
    <!-- Badges -->
    <div style="position:absolute; top:8px; left:8px; display:flex; gap:4px; z-index:10;">
        <?php if ($isBundle): ?>
            <span style="background-color:#d8369f; color:white; padding:2px 6px; border-radius:4px; font-size:0.7rem; font-weight:bold;">BUNDLE</span>
        <?php endif; ?>
    </div>

    <!-- Image -->
    <div style="height:180px; background-color:#252525; border-radius:8px; margin-bottom:12px; overflow:hidden;">
        <?php if ($image): ?>
            <img src="<?= esc($image) ?>" alt="<?= esc($product['name']) ?>" style="width:100%; height:100%; object-fit:cover;">
        <?php else: ?>
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#666; font-size:0.8rem;">
                No Image
            </div>
        <?php endif; ?>
    </div>

    <!-- Info -->
    <div>
        <h3 style="margin:0 0 4px 0; font-size:0.95rem; font-weight:bold; line-height:1.2; color:#fff;">
            <?= esc($product['name']) ?>
        </h3>
        
        <div style="display:flex; justify-content:space-between; align-items:baseline; margin-top:4px;">
            <span style="color:#4caf50; font-weight:bold;">₱<?= $formattedPrice ?></span>
        </div>

        <?php if ($showPoints): ?>
            <div style="font-size:0.75rem; color:#ffd700; margin-top:2px;">
                +<?= $product['points_to_earn'] ?> Pts
            </div>
        <?php endif; ?>
    </div>

    <!-- Action -->
    <a href="/product/<?= esc($product['sku']) ?>" class="btn btn-secondary" style="width:100%; box-sizing:border-box; text-align:center; padding:8px; margin-top:12px; font-size:0.9rem;">
        Buy Now
    </a>
</div>