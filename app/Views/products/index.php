<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <h1 style="margin-bottom: 10px;">Browse Products</h1>
    <p style="color: var(--text-muted); margin-bottom: 30px;">
        Explore our collection of digital products and bundles
    </p>

    <!-- Filters & Search -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; background-color: var(--card-bg); padding: 20px; border-radius: 8px;">
        <div class="form-group" style="margin: 0;">
            <input type="search" id="searchProducts" class="input-dark" placeholder="Search products..." 
                   style="margin: 0;">
        </div>
        
        <div class="form-group" style="margin: 0;">
            <select id="categoryFilter" class="input-dark" style="margin: 0;">
                <option value="">All Categories</option>
                <option value="games">Games</option>
                <option value="streaming">Streaming Services</option>
                <option value="bundles">Bundles</option>
                <option value="subscriptions">Subscriptions</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-auto" id="productsContainer">
        <?php if (! empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <p style="color: var(--text-muted); font-size: 1.1rem;">
                    No products found. Try adjusting your filters.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (! empty($pagination)): ?>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 40px; flex-wrap: wrap;">
            <?= $pagination ?? '' ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
