<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage product brands</p>
    <a href="/admin/brands/new" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Brand
    </a>
</div>

<!-- Brands Grid -->
<div class="admin-card">
    <?php if (!empty($brands)): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <?php foreach ($brands as $brand): ?>
        <div style="background: rgba(26, 26, 38, 0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: var(--card-bg); display: flex; align-items: center; justify-content: center;">
                <?php if ($brand['logo']): ?>
                <img src="<?= esc($brand['logo']) ?>" alt="<?= esc($brand['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                <?php else: ?>
                <i class="fas fa-tag" style="font-size: 1.5rem; color: var(--text-muted);"></i>
                <?php endif; ?>
            </div>
            
            <div style="flex: 1;">
                <h4 style="margin: 0 0 4px;"><?= esc($brand['name']) ?></h4>
                <span class="status-badge status-<?= $brand['is_enabled'] ? 'active' : 'inactive' ?>" style="font-size: 0.7rem;">
                    <?= $brand['is_enabled'] ? 'Enabled' : 'Disabled' ?>
                </span>
            </div>
            
            <div class="table-actions">
                <a href="/admin/brands/edit/<?= $brand['id'] ?>" class="btn-admin btn-admin-secondary btn-admin-sm btn-admin-icon">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="/admin/brands/delete/<?= $brand['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this brand?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm btn-admin-icon">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-tags"></i></div>
        <h4 class="empty-state-title">No brands found</h4>
        <p class="empty-state-text">Add brands to organize your products.</p>
        <a href="/admin/brands/new" class="btn-admin btn-admin-primary">Add Brand</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

