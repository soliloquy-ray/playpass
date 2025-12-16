<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage customer support channels displayed on the homepage</p>
    <a href="<?= site_url('admin/customer-support/new') ?>" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Channel
    </a>
</div>

<!-- Channels Table -->
<div class="admin-card">
    <?php if (!empty($channels)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Order</th>
                    <th style="width: 60px;">Icon</th>
                    <th>Label</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($channels as $channel): ?>
                <tr>
                    <td>
                        <span style="color: var(--text-muted); font-weight: 600;"><?= esc($channel['sort_order']) ?></span>
                    </td>
                    <td>
                        <?php if ($channel['icon']): ?>
                        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border-radius: 8px;">
                            <i class="<?= esc($channel['icon']) ?>" style="font-size: 1.2rem; color: var(--primary);"></i>
                        </div>
                        <?php else: ?>
                        <div class="table-thumbnail" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border-radius: 8px;">
                            <i class="fas fa-circle-question" style="color: var(--text-muted);"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($channel['label']) ?></strong></td>
                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <a href="<?= esc($channel['link']) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                            <?= esc($channel['link']) ?>
                        </a>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $channel['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $channel['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= site_url('admin/customer-support/edit/' . $channel['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= site_url('admin/customer-support/delete/' . $channel['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this support channel?');">
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
        <div class="empty-state-icon"><i class="fas fa-headset"></i></div>
        <h4 class="empty-state-title">No support channels found</h4>
        <p class="empty-state-text">Add customer support channels to display on the homepage.</p>
        <a href="<?= site_url('admin/customer-support/new') ?>" class="btn-admin btn-admin-primary">Add Channel</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
