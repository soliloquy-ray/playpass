<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($channel); ?>

<div style="margin-bottom: 24px;">
    <a href="/admin/customer-support" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Customer Support
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

<form action="<?= $isEdit ? '/admin/customer-support/update/' . $channel['id'] : '/admin/customer-support/create' ?>" method="POST" class="admin-form" style="max-width: 700px;">
    <?= csrf_field() ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Support Channel Information</h3>
        
        <div class="form-group">
            <label class="form-label">Label <span class="required">*</span></label>
            <input type="text" name="label" class="form-input" value="<?= esc($channel['label'] ?? old('label')) ?>" required placeholder="e.g., Email, FAQ, Viber">
            <p class="form-hint">The text displayed on the support button</p>
        </div>

        <div class="form-group">
            <label class="form-label">Link/URL <span class="required">*</span></label>
            <input type="text" name="link" class="form-input" value="<?= esc($channel['link'] ?? old('link')) ?>" required placeholder="e.g., mailto:support@playpass.ph or https://...">
            <p class="form-hint">The destination URL or mailto link when clicked</p>
        </div>

        <div class="form-group">
            <label class="form-label">Icon (Font Awesome class)</label>
            <input type="text" name="icon" class="form-input" value="<?= esc($channel['icon'] ?? old('icon')) ?>" placeholder="e.g., fa-solid fa-envelope">
            <p class="form-hint">
                Use Font Awesome icon classes. 
                <a href="https://fontawesome.com/icons" target="_blank" style="color: var(--primary);">Browse icons →</a>
            </p>
            <?php if ($isEdit && $channel['icon']): ?>
            <div style="margin-top: 12px; padding: 12px; background: var(--card-bg); border-radius: 8px; display: inline-flex; align-items: center; gap: 12px;">
                <i class="<?= esc($channel['icon']) ?>" style="font-size: 1.5rem; color: var(--primary);"></i>
                <span style="color: var(--text-muted); font-size: 0.9rem;">Preview</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="<?= esc($channel['sort_order'] ?? 0) ?>" min="0">
                <p class="form-hint">Lower numbers appear first</p>
            </div>
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <label class="form-checkbox-group">
                    <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($channel['is_active'] ?? true) ? 'checked' : '' ?>>
                    <span>Active (visible on homepage)</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Channel' : 'Create Channel' ?>
        </button>
        <a href="/admin/customer-support" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<?= $this->endSection() ?>
