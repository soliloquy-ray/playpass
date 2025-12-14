<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage the "How It Works" steps on the homepage</p>
    <a href="/admin/how-it-works/new" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Step
    </a>
</div>

<!-- Steps List -->
<div class="admin-card">
    <?php if (!empty($steps)): ?>
    <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">
        <i class="fas fa-info-circle"></i> Drag and drop to reorder steps
    </p>
    
    <ul class="sortable-list" id="stepsSortable">
        <?php foreach ($steps as $index => $step): ?>
        <li class="sortable-item" data-id="<?= $step['id'] ?>">
            <div class="sortable-handle">
                <i class="fas fa-grip-vertical"></i>
            </div>
            
            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, #a01570 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 800;">
                <?= $index + 1 ?>
            </div>
            
            <div class="sortable-content" style="flex: 1;">
                <h4 style="margin: 0 0 4px;"><?= esc($step['title']) ?></h4>
                <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">
                    <?= esc(substr($step['description'], 0, 100)) ?><?= strlen($step['description']) > 100 ? '...' : '' ?>
                </p>
            </div>

            <?php if ($step['icon']): ?>
            <span style="color: var(--text-muted); font-size: 0.85rem;">
                <i class="<?= esc($step['icon']) ?>"></i> <?= esc($step['icon']) ?>
            </span>
            <?php endif; ?>

            <span class="status-badge status-<?= $step['is_active'] ? 'active' : 'inactive' ?>">
                <?= $step['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
            
            <div class="table-actions">
                <a href="/admin/how-it-works/edit/<?= $step['id'] ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="/admin/how-it-works/delete/<?= $step['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this step?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-list-ol"></i></div>
        <h4 class="empty-state-title">No steps found</h4>
        <p class="empty-state-text">Add steps to explain how your service works.</p>
        <a href="/admin/how-it-works/new" class="btn-admin btn-admin-primary">Add Step</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

