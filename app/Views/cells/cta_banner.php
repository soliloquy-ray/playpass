<div class="cta-banner" style="
    background: linear-gradient(135deg, var(--primary) 0%, #c0216d 100%);
    border-radius: 12px;
    padding: 40px 30px;
    margin: 40px 15px;
    text-align: center;
">
    <div style="font-size: 2rem; margin-bottom: 12px;">
        <?= $icon ?>
    </div>
    
    <h2 style="margin-bottom: 12px; font-size: 1.8rem;">
        <?= esc($title) ?>
    </h2>
    
    <p style="color: rgba(255, 255, 255, 0.95); margin-bottom: 24px; font-size: 1rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        <?= esc($subtitle) ?>
    </p>
    
    <a href="<?= esc($button_url) ?>" class="btn btn-secondary" style="background: white; color: var(--primary); font-weight: 800;">
        <?= esc($button_text) ?>
    </a>
</div>
