<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage your product catalog</p>
    <a href="/admin/products/new" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<!-- Filters -->
<div class="filters-bar">
    <input type="search" class="filter-input" placeholder="Search products..." id="searchInput">
    <select class="filter-select" id="brandFilter">
        <option value="">All Brands</option>
        <?php foreach ($brands as $brand): ?>
        <option value="<?= $brand['id'] ?>"><?= esc($brand['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <select class="filter-select" id="statusFilter">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="featured">Featured</option>
    </select>
</div>

<!-- Products Table -->
<div class="admin-card">
    <?php if (!empty($products)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Image</th>
                    <th>Product Name</th>
                    <th>SKU</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if ($product['thumbnail_url']): ?>
                        <img src="<?= esc($product['thumbnail_url']) ?>" alt="" class="table-thumbnail">
                        <?php else: ?>
                        <div class="table-thumbnail" style="display: flex; align-items: center; justify-content: center; background: var(--card-bg);">
                            <i class="fas fa-image" style="color: var(--text-muted);"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <strong><?= esc($product['name']) ?></strong>
                            <?php if ($product['is_featured']): ?>
                            <span class="badge" style="margin-left: 8px; font-size: 0.7rem;">Featured</span>
                            <?php endif; ?>
                            <?php if ($product['is_new']): ?>
                            <span class="badge badge-success" style="margin-left: 4px; font-size: 0.7rem;">New</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><code style="background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 4px;"><?= esc($product['sku']) ?></code></td>
                    <td><?= esc($product['brand_name'] ?? 'N/A') ?></td>
                    <td style="color: var(--success); font-weight: 700;">₱<?= number_format($product['price'], 2) ?></td>
                    <td>
                        <span class="status-badge status-<?= $product['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/admin/products/delete/<?= $product['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
        <h4 class="empty-state-title">No products found</h4>
        <p class="empty-state-text">Start by adding your first product to the catalog.</p>
        <a href="/admin/products/new" class="btn-admin btn-admin-primary">Add Product</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
