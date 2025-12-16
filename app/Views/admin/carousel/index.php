<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage your homepage carousel slides</p>
    <a href="<?= site_url('admin/carousel/new') ?>" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Slide
    </a>
</div>

<!-- Slides List -->
<div class="admin-card">
    <?php if (!empty($slides)): ?>
    <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">
        <i class="fas fa-info-circle"></i> Drag and drop to reorder slides
    </p>
    
    <ul class="sortable-list" id="slideSortable">
        <?php foreach ($slides as $slide): ?>
        <li class="sortable-item" data-id="<?= $slide['id'] ?>">
            <div class="sortable-handle">
                <i class="fas fa-grip-vertical"></i>
            </div>
            
            <div style="width: 120px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, <?= esc($slide['bg_gradient_start']) ?> 0%, <?= esc($slide['bg_gradient_end']) ?> 100%);">
                <?php if ($slide['image_url']): ?>
                <img src="<?= esc($slide['image_url']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
            </div>
            
            <div class="sortable-content" style="flex: 1;">
                <h4 style="margin: 0 0 4px;"><?= esc($slide['title']) ?></h4>
                <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">
                    <?= esc($slide['subtitle'] ?? 'No subtitle') ?>
                </p>
                <?php if ($slide['cta_text']): ?>
                <span style="display: inline-block; margin-top: 8px; padding: 4px 12px; background: var(--primary); border-radius: 4px; font-size: 0.8rem;">
                    <?= esc($slide['cta_text']) ?> → <?= esc($slide['cta_link']) ?>
                </span>
                <?php endif; ?>
            </div>

            <span class="status-badge status-<?= $slide['is_active'] ? 'active' : 'inactive' ?>">
                <?= $slide['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
            
            <div class="table-actions">
                <a href="<?= site_url('admin/carousel/edit/' . $slide['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="<?= site_url('admin/carousel/delete/' . $slide['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this slide?');">
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
        <div class="empty-state-icon"><i class="fas fa-images"></i></div>
        <h4 class="empty-state-title">No carousel slides</h4>
        <p class="empty-state-text">Add slides to display on your homepage hero section.</p>
        <a href="<?= site_url('admin/carousel/new') ?>" class="btn-admin btn-admin-primary">Add Slide</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

