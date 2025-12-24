<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage your site's legal and informational pages</p>
</div>

<!-- Site Pages Table -->
<div class="admin-card">
    <?php if (!empty($pages)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td>
                        <div>
                            <strong><?= esc($page['title']) ?></strong>
                            <?php if (!empty($page['meta_title'])): ?>
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 0;">
                                <?= esc(substr($page['meta_title'], 0, 50)) ?><?= strlen($page['meta_title']) > 50 ? '...' : '' ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <code style="background: var(--card-bg); padding: 4px 8px; border-radius: 4px;">/<?= esc($page['slug']) ?></code>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $page['is_active'] ? 'published' : 'draft' ?>">
                            <?= $page['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <?= $page['updated_at'] ? date('M d, Y H:i', strtotime($page['updated_at'])) : '-' ?>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= site_url('admin/site-pages/edit/' . $page['slug']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= site_url($page['slug']) ?>" target="_blank" class="btn-admin btn-admin-secondary btn-admin-sm" title="View Page">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-file-alt"></i></div>
        <h4 class="empty-state-title">No site pages found</h4>
        <p class="empty-state-text">Run the seeder to create default pages (Terms, Privacy, FAQ).</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
