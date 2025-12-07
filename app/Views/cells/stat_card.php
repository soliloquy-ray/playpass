<div class="stat-card" style="
    background: linear-gradient(135deg, var(--card-bg) 0%, var(--secondary) 100%);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    transition: all 0.3s ease;
">
    <div style="font-size: 2.5rem; margin-bottom: 12px;">
        <?= $icon ?>
    </div>
    
    <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-bottom: 8px;">
        <?= number_format($number) ?><?= !empty($unit) ? ' ' . esc($unit) : '' ?>
    </div>
    
    <div style="color: var(--text-muted); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
        <?= esc($label) ?>
    </div>
</div>
