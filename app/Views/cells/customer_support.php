<section class="customer-support" style="margin-bottom: 60px;">
    <h2 style="color: #3b82f6; margin: 0; font-weight: bold;">CUSTOMER SUPPORT</h2>
    <p style="color: #fff; margin: 5px 0 20px 0; font-size: 0.9rem;">Reach Us At</p>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
        <?php foreach ($supports as $support): ?>
            <a href="<?= link_url($support['link'] ?? '#') ?>" class="support-btn" style="
                border: 1px solid #ff0055;
                border-radius: 8px;
                padding: 15px 5px;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                background: rgba(255, 0, 85, 0.05);
                transition: background 0.2s;">
                
                <div style="color: #ff0055; font-size: 1.2rem;">
                    <i class="<?= $support['icon'] ?? 'fa-solid fa-circle-question' ?>"></i>
                </div>
                
                <span style="color: #eee; font-size: 0.8rem; font-weight: 500;">
                    <?= $support['label'] ?? ''; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>