<div class="testimonial-card" style="
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
">
    <!-- Stars -->
    <div style="color: #ffd700; font-size: 1.2rem;">
        <?php for ($i = 0; $i < $rating; $i++): ?>
            ★
        <?php endfor; ?>
        <?php for ($i = $rating; $i < 5; $i++): ?>
            <span style="opacity: 0.3;">★</span>
        <?php endfor; ?>
    </div>

    <!-- Quote -->
    <p style="
        color: var(--text-light);
        font-size: 1rem;
        line-height: 1.6;
        font-style: italic;
        margin: 0;
    ">
        "<?= esc($content) ?>"
    </p>

    <!-- Author -->
    <div style="display: flex; align-items: center; gap: 12px; margin-top: 12px;">
        <?php if ($avatar): ?>
            <img src="<?= esc($avatar) ?>" alt="<?= esc($name) ?>" 
                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
        <?php else: ?>
            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800;">
                <?= strtoupper(substr($name, 0, 1)) ?>
            </div>
        <?php endif; ?>
        
        <div>
            <div style="font-weight: 700; color: var(--text-main);">
                <?= esc($name) ?>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                <?= esc($role) ?>
            </div>
        </div>
    </div>
</div>
