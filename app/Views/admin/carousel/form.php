<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($slide); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/carousel') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Carousel
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

<form action="<?= $isEdit ? '/admin/carousel/update/' . $slide['id'] : '/admin/carousel/create' ?>" method="POST" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    
    <div class="admin-grid admin-grid-equal">
        <!-- Content -->
        <div class="admin-card">
            <h3 class="admin-card-title" style="margin-bottom: 24px;">Slide Content</h3>
            
            <div class="form-group">
                <label class="form-label">Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-input" value="<?= esc($slide['title'] ?? old('title')) ?>" required placeholder="e.g., To the Moon 🚀">
            </div>

            <div class="form-group">
                <label class="form-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-input" value="<?= esc($slide['subtitle'] ?? old('subtitle')) ?>" placeholder="e.g., Now Streaming via Playpass">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="cta_text" class="form-input" value="<?= esc($slide['cta_text'] ?? old('cta_text')) ?>" placeholder="e.g., Explore Now">
                </div>
                <div class="form-group">
                    <label class="form-label">Button Link</label>
                    <input type="text" name="cta_link" class="form-input" value="<?= esc($slide['cta_link'] ?? old('cta_link')) ?>" placeholder="e.g., /products">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Background Image</label>
                <div class="file-upload-wrapper">
                    <label class="file-upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click or drag to upload image</span>
                        <input type="file" name="image" class="file-upload-input" accept="image/*">
                    </label>
                </div>
                <?php if ($isEdit && $slide['image_url']): ?>
                <div class="file-preview" style="margin-top: 16px;">
                    <div class="file-preview-item" style="width: 200px; height: 100px;">
                        <img src="<?= esc($slide['image_url']) ?>" alt="Current image">
                    </div>
                </div>
                <input type="hidden" name="image_url" value="<?= esc($slide['image_url']) ?>">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Or paste image URL</label>
                <input type="url" name="image_url" class="form-input" placeholder="https://..." value="<?= esc($slide['image_url'] ?? old('image_url')) ?>">
            </div>
        </div>

        <!-- Settings -->
        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Background Gradient</h3>
                
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
                    Set colors for the gradient overlay behind the image
                </p>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="bg_gradient_start" class="color-picker-input" value="<?= esc($slide['bg_gradient_start'] ?? '#d8369f') ?>" id="gradientStart">
                            <input type="text" class="form-input" style="width: 100px;" value="<?= esc($slide['bg_gradient_start'] ?? '#d8369f') ?>" id="gradientStartText" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="bg_gradient_end" class="color-picker-input" value="<?= esc($slide['bg_gradient_end'] ?? '#051429') ?>" id="gradientEnd">
                            <input type="text" class="form-input" style="width: 100px;" value="<?= esc($slide['bg_gradient_end'] ?? '#051429') ?>" id="gradientEndText" readonly>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="form-group">
                    <label class="form-label">Preview</label>
                    <div id="gradientPreview" style="height: 100px; border-radius: 8px; background: linear-gradient(135deg, <?= esc($slide['bg_gradient_start'] ?? '#d8369f') ?> 0%, <?= esc($slide['bg_gradient_end'] ?? '#051429') ?> 100%);"></div>
                </div>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Settings</h3>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="<?= esc($slide['sort_order'] ?? 0) ?>">
                    <p class="form-hint">Lower numbers appear first</p>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($slide['is_active'] ?? true) ? 'checked' : '' ?>>
                        <span>Active (visible on homepage)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Slide' : 'Create Slide' ?>
        </button>
        <a href="<?= site_url('admin/carousel') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<script>
// Update gradient preview
function updateGradientPreview() {
    const start = document.getElementById('gradientStart').value;
    const end = document.getElementById('gradientEnd').value;
    document.getElementById('gradientPreview').style.background = `linear-gradient(135deg, ${start} 0%, ${end} 100%)`;
    document.getElementById('gradientStartText').value = start;
    document.getElementById('gradientEndText').value = end;
}

document.getElementById('gradientStart').addEventListener('input', updateGradientPreview);
document.getElementById('gradientEnd').addEventListener('input', updateGradientPreview);
</script>

<?= $this->endSection() ?>

