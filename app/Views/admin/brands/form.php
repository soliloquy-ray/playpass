<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($brand); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/brands') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Brands
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

<form action="<?= $isEdit ? site_url('admin/brands/update/' . $brand['id']) : site_url('admin/brands/create') ?>" method="POST" enctype="multipart/form-data" class="admin-form" style="max-width: 600px;">
    <?= csrf_field() ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Brand Information</h3>
        
        <div class="form-group">
            <label class="form-label">Brand Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="<?= esc($brand['name'] ?? old('name')) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Logo</label>
            <!-- Simple visible file input for better compatibility -->
            <input type="file" name="logo" id="logo-file-input" accept="image/*" style="display: block; margin-bottom: 10px; color: #fff;">
            <div id="file-selected" style="margin-top: 10px; color: #3b82f6; display: none;">
                <small>File selected: <span id="file-name"></span></small>
            </div>
            <?php if ($isEdit && $brand['logo']): ?>
            <div class="file-preview" style="margin-top: 16px;">
                <div class="file-preview-item" style="width: 80px; height: 80px;">
                    <img src="<?= asset_url($brand['logo']) ?>" alt="Current logo">
                </div>
            </div>
            <input type="hidden" name="logo_url" value="<?= esc($brand['logo']) ?>">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Or paste logo URL</label>
            <input type="url" name="logo_url" class="form-input" placeholder="https://..." value="<?= esc($brand['logo'] ?? old('logo_url')) ?>">
        </div>

        <div class="form-group">
            <label class="form-checkbox-group">
                <input type="checkbox" name="is_enabled" class="form-checkbox" value="1" <?= ($brand['is_enabled'] ?? true) ? 'checked' : '' ?>>
                <span>Enabled (visible in product filters)</span>
            </label>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Brand' : 'Create Brand' ?>
        </button>
        <a href="<?= site_url('admin/brands') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('logo-file-input');
    
    if (fileInput) {
        // Show file name when selected
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                document.getElementById('file-selected').style.display = 'block';
            } else {
                document.getElementById('file-selected').style.display = 'none';
            }
        });
    }
});
</script>

<?= $this->endSection() ?>

