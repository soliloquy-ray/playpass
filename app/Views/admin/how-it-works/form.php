<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($step); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/how-it-works') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to How It Works
    </a>
</div>

<!-- Validation Errors -->
<?php if (session()->has('errors')): ?>
<div class="alert alert-error" style="margin-bottom: 24px;">
    <ul style="margin: 0; padding-left: 20px;">
        <?php foreach (session('errors') as $error): ?>
        <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= $isEdit ? '/admin/how-it-works/update/' . $step['id'] : '/admin/how-it-works/create' ?>" method="POST" class="admin-form" style="max-width: 700px;">
    <?= csrf_field() ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Step Information</h3>
        
        <div class="form-group">
            <label class="form-label">Title <span class="required">*</span></label>
            <input type="text" name="title" class="form-input" value="<?= esc($step['title'] ?? old('title')) ?>" required placeholder="e.g., Select Product">
        </div>

        <div class="form-group">
            <label class="form-label">Description <span class="required">*</span></label>
            <textarea name="description" class="form-textarea" required placeholder="Explain this step..."><?= esc($step['description'] ?? old('description')) ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Icon (Font Awesome class)</label>
            <input type="text" name="icon" class="form-input" value="<?= esc($step['icon'] ?? old('icon')) ?>" placeholder="e.g., fas fa-shopping-cart">
            <p class="form-hint">
                Use Font Awesome icon classes. 
                <a href="https://fontawesome.com/icons" target="_blank" style="color: var(--primary);">Browse icons →</a>
            </p>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= esc($step['sort_order'] ?? 0) ?>">
                <p class="form-hint">Lower numbers appear first</p>
            </div>
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <label class="form-checkbox-group">
                    <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($step['is_active'] ?? true) ? 'checked' : '' ?>>
                    <span>Active (visible on homepage)</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Step' : 'Create Step' ?>
        </button>
        <a href="<?= site_url('admin/how-it-works') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<?= $this->endSection() ?>

