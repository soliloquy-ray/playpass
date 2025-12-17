<section class="promos-section" style="margin-bottom: 40px;">
    <h2 style="color: #3b82f6; margin-bottom: 20px; font-weight: bold;"><?= esc($title ?? 'PROMOS') ?></h2>

    <?php if (empty($promos) || !is_array($promos)): ?>
        <p style="color: #999; padding: 10px;">No promos available</p>
    <?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
        <?php foreach ($promos as $promo): ?>
            <div class="promo-tile" style="
                border: 1px solid #444;
                border-radius: 12px;
                background-color: #121212;
                aspect-ratio: 1/1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 10px;">
                
                <div style="font-size: 2rem; margin-bottom: 8px;">
                    <?php if(!empty($promo['image'])): ?>
                        <img src="<?= asset_url($promo['image']) ?>" style="width: 40px; height: 40px;">
                    <?php else: ?>
                        🎁
                    <?php endif; ?>
                </div>

                <span style="color: #ccc; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">
                    <?= $promo['title'] ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>