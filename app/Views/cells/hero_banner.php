<div class="hero-banner" style="
    background: <?= $image_gradient ?>;
    border-radius: 12px;
    padding: 50px 30px;
    margin: 30px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 300px;
    position: relative;
    overflow: hidden;
">
    <!-- Content -->
    <div style="flex: 1; z-index: 10;">
        <h1 style="margin-bottom: 12px; font-size: 2.2rem;">
            <?= esc($title) ?>
        </h1>
        <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 24px; font-size: 1.1rem;">
            <?= esc($subtitle) ?>
        </p>
        <a href="<?= esc($button_url) ?>" class="btn <?= $button_class ?>">
            <?= esc($button_text) ?>
        </a>
    </div>

    <!-- Decorative element -->
    <div style="
        flex: 0 0 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: none;
    "></div>
</div>
