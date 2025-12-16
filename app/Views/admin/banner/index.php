<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Manage the top banner that appears above the header</p>
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

<form action="<?= site_url('admin/banner/save') ?>" method="POST" class="admin-form" style="max-width: 700px;">
    <?= csrf_field() ?>
    
    <?php if ($banner['id']): ?>
        <input type="hidden" name="id" value="<?= $banner['id'] ?>">
    <?php endif; ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Top Banner Settings</h3>
        
        <div class="form-group">
            <label class="form-label">Banner Text <span class="required">*</span></label>
            <input type="text" name="text" class="form-input" value="<?= esc($banner['text'] ?? old('text')) ?>" required placeholder="e.g., CODE AGAD. INSTANT DELIVERY VIA SMS/EMAIL!">
            <p class="form-hint">The text to display in the top banner</p>
        </div>

        <div class="form-group">
            <label class="form-label">Icon</label>
            <input type="text" name="icon" class="form-input" value="<?= esc($banner['icon'] ?? old('icon') ?: 'fa-bolt') ?>" placeholder="fa-bolt">
            <p class="form-hint">Font Awesome icon class (e.g., fa-bolt, fa-star, fa-fire). Default: fa-bolt</p>
        </div>

        <div class="form-group">
            <label class="form-checkbox-group">
                <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($banner['is_active'] ?? false) ? 'checked' : '' ?>>
                <span>Active (show banner on site)</span>
            </label>
        </div>

        <!-- Preview -->
        <?php if ($banner['is_active'] && !empty($banner['text'])): ?>
        <div class="form-group" style="margin-top: 24px; padding: 16px; background: var(--bg-primary); border-radius: 8px; border: 1px solid var(--border-color);">
            <label class="form-label" style="margin-bottom: 12px;">Preview:</label>
            <div class="top-cta" style="margin: 0;">
                <?php if (!empty($banner['icon'])): ?>
                    <i class="fa-solid <?= esc($banner['icon']) ?>" style="color: #ffd700;"></i>
                <?php else: ?>
                    <i class="fa-solid fa-bolt" style="color: #ffd700;"></i>
                <?php endif; ?>
                <?= esc($banner['text']) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> Save Banner
        </button>
    </div>
</form>

<?= $this->endSection() ?>
