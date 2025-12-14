<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($promo); ?>

<div style="margin-bottom: 24px;">
    <a href="/admin/promos" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Promos
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

<form action="<?= $isEdit ? '/admin/promos/update/' . $promo['id'] : '/admin/promos/create' ?>" method="POST" enctype="multipart/form-data" class="admin-form" style="max-width: 700px;">
    <?= csrf_field() ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Promo Information</h3>
        
        <div class="form-group">
            <label class="form-label">Promo Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="<?= esc($promo['name'] ?? old('name')) ?>" required placeholder="e.g., Playpass Points">
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-textarea" style="min-height: 100px;"><?= esc($promo['description'] ?? old('description')) ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Icon/Image</label>
            <!-- Simple visible file input for debugging -->
            <input type="file" name="icon" id="icon-file-input" accept="image/*" style="display: block; margin-bottom: 10px; color: #fff;">
            <div id="file-selected" style="margin-top: 10px; color: #3b82f6; display: none;">
                <small>File selected: <span id="file-name"></span></small>
            </div>
            <?php if ($isEdit && $promo['icon']): ?>
            <div class="file-preview" style="margin-top: 16px;">
                <div class="file-preview-item" style="width: 60px; height: 60px;">
                    <img src="<?= esc($promo['icon']) ?>" alt="Current icon">
                </div>
            </div>
            <input type="hidden" name="icon_url" value="<?= esc($promo['icon']) ?>">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">Or paste icon URL</label>
            <input type="url" name="icon_url" class="form-input" placeholder="https://..." value="<?= esc($promo['icon'] ?? old('icon_url')) ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input type="datetime-local" name="start_date" class="form-input" value="<?= $isEdit && $promo['start_date'] ? date('Y-m-d\TH:i', strtotime($promo['start_date'])) : old('start_date') ?>">
                <p class="form-hint">Leave blank for no start limit</p>
            </div>
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input type="datetime-local" name="end_date" class="form-input" value="<?= $isEdit && $promo['end_date'] ? date('Y-m-d\TH:i', strtotime($promo['end_date'])) : old('end_date') ?>">
                <p class="form-hint">Leave blank for no end limit</p>
            </div>
        </div>

        <div class="form-group">
            <label class="form-checkbox-group">
                <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($promo['is_active'] ?? true) ? 'checked' : '' ?>>
                <span>Active (visible on homepage)</span>
            </label>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Promo' : 'Create Promo' ?>
        </button>
        <a href="/admin/promos" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('icon-file-input');
    
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

