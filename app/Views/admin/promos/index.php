<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage promotional tiles on the homepage</p>
    <a href="<?= site_url('admin/promos/new') ?>" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Promo
    </a>
</div>

<!-- Promos Table -->
<div class="admin-card">
    <?php if (!empty($promos)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Icon</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promos as $promo): ?>
                <tr>
                    <td>
                        <?php if ($promo['icon']): ?>
                        <img src="<?= esc($promo['icon']) ?>" alt="" class="table-thumbnail" style="width: 40px; height: 40px;">
                        <?php else: ?>
                        <div class="table-thumbnail" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg);">
                            <i class="fas fa-percent" style="color: var(--text-muted);"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($promo['name']) ?></strong></td>
                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= esc($promo['description'] ?? '-') ?>
                    </td>
                    <td>
                        <?php if ($promo['start_date'] && $promo['end_date']): ?>
                        <span style="font-size: 0.85rem;">
                            <?= date('M d', strtotime($promo['start_date'])) ?> - <?= date('M d, Y', strtotime($promo['end_date'])) ?>
                        </span>
                        <?php else: ?>
                        <span style="color: var(--text-muted);">Always</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $promo['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $promo['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= site_url('admin/promos/edit/' . $promo['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= site_url('admin/promos/delete/' . $promo['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this promo?');">
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
        <div class="empty-state-icon"><i class="fas fa-percent"></i></div>
        <h4 class="empty-state-title">No promos found</h4>
        <p class="empty-state-text">Add promotional tiles to display on the homepage.</p>
        <a href="<?= site_url('admin/promos/new') ?>" class="btn-admin btn-admin-primary">Add Promo</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

