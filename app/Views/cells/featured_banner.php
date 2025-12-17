<div class="featured-banner" style="
    background: <?= $background_gradient ?>;
    border-radius: 12px;
    padding: 40px;
    margin: 30px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
    position: relative;
    overflow: hidden;
">
    <!-- Overlay pattern -->
    <div style="position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; opacity: 0.5;"></div>

    <!-- Content -->
    <div style="flex: 1; z-index: 10;">
        <div style="display: inline-block; background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">
            <?= esc($badge) ?>
        </div>
        
        <h2 style="margin: 0 0 12px 0; font-size: 2rem;">
            <?= esc($title) ?>
        </h2>
        
        <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 20px; font-size: 1.05rem;">
            <?= esc($description) ?>
        </p>
        
        <a href="<?= esc($button_url) ?>" class="btn btn-secondary" style="background: white; color: var(--primary); font-weight: 800;">
            <?= esc($button_text) ?>
        </a>
    </div>

    <!-- Image (if available) -->
    <?php if ($image_url): ?>
        <div style="flex: 0 0 250px; height: 250px; border-radius: 8px; overflow: hidden; margin-left: 30px;">
            <img src="<?= asset_url($image_url) ?>" alt="<?= esc($title) ?>" 
                 style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    <?php endif; ?>
</div>
