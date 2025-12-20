<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Offer discounts to first-time purchasers</p>
    <a href="<?= site_url('admin/first-purchase-promos/new') ?>" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Create Promo
    </a>
</div>

<!-- Info Box -->
<div class="admin-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1)); border: 1px solid rgba(59, 130, 246, 0.3); margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-info-circle" style="color: var(--primary); font-size: 1.5rem;"></i>
        <div>
            <strong style="color: var(--text-primary);">How it works</strong>
            <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.9rem;">
                First purchase promos are automatically applied when a new customer makes their first purchase. 
                Only <strong>one promo can be active</strong> at a time. Customers who complete a purchase are marked and cannot receive the discount again.
            </p>
        </div>
    </div>
</div>

<!-- Promos Table -->
<div class="admin-card">
    <?php if (!empty($promos)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Promo Name</th>
                    <th>Discount</th>
                    <th>Min Spend</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promos as $promo): ?>
                <tr>
                    <td>
                        <strong><?= esc($promo['name']) ?></strong>
                        <?php if (!empty($promo['label']) && $promo['label'] !== $promo['name']): ?>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 2px 0 0;">
                            Label: <?= esc($promo['label']) ?>
                        </p>
                        <?php endif; ?>
                    </td>
                    <td style="color: var(--primary); font-weight: 700;">
                        <?php if ($promo['discount_type'] === 'percentage'): ?>
                            <?= number_format($promo['discount_value']) ?>%
                        <?php else: ?>
                            ₱<?= number_format($promo['discount_value'], 2) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($promo['min_spend_amount'] > 0): ?>
                            ₱<?= number_format($promo['min_spend_amount'], 2) ?>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">No minimum</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $productCount = count($this->promoModel ?? (new \App\Models\FirstPurchasePromoModel())->getApplicableProducts($promo['id']));
                        ?>
                        <?php if ($productCount > 0): ?>
                            <?= $productCount ?> product(s)
                        <?php else: ?>
                            <span style="color: var(--text-muted);">All products</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $promo['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $promo['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <form action="<?= site_url('admin/first-purchase-promos/toggle/' . $promo['id']) ?>" method="POST" style="display: inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-admin btn-admin-<?= $promo['is_active'] ? 'warning' : 'success' ?> btn-admin-sm" title="<?= $promo['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                    <i class="fas fa-<?= $promo['is_active'] ? 'pause' : 'play' ?>"></i>
                                </button>
                            </form>
                            <a href="<?= site_url('admin/first-purchase-promos/edit/' . $promo['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= site_url('admin/first-purchase-promos/delete/' . $promo['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this promo?');">
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
        <div class="empty-state-icon"><i class="fas fa-gift"></i></div>
        <h4 class="empty-state-title">No first purchase promos</h4>
        <p class="empty-state-text">Create a promo to offer discounts to new customers.</p>
        <a href="<?= site_url('admin/first-purchase-promos/new') ?>" class="btn-admin btn-admin-primary">Create Promo</a>
    </div>
    <?php endif; ?>
</div>

<style>
.status-inactive {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}
.btn-admin-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}
.btn-admin-warning:hover {
    background: rgba(245, 158, 11, 0.3);
}
</style>

<?= $this->endSection() ?>
