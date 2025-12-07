<a href="<?= esc($url) ?>" class="category-badge">
    <!-- Icon -->
    <div class="badge-icon">
        <?= $icon ?>
    </div>

    <!-- Category Name -->
    <div class="badge-title">
        <?= esc($title) ?>
    </div>

    <!-- Count -->
    <?php if ($count !== null): ?>
        <div class="badge-count">
            <?= number_format($count) ?> products
        </div>
    <?php endif; ?>
</a>
