<a href="<?= esc($url) ?>" class="category-badge" style="
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background: linear-gradient(135deg, var(--secondary) 0%, var(--card-bg) 100%);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    text-align: center;
">
    <!-- Icon -->
    <div style="font-size: 2.5rem;">
        <?= $icon ?>
    </div>

    <!-- Category Name -->
    <div style="
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    ">
        <?= esc($category) ?>
    </div>

    <!-- Count -->
    <?php if ($count !== null): ?>
        <div style="
            font-size: 0.85rem;
            color: var(--text-muted);
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
        ">
            <?= number_format($count) ?> products
        </div>
    <?php endif; ?>
</a>

<style>
.category-badge:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(216, 54, 159, 0.2);
}
</style>
