<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($story); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/stories') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Stories
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

<form action="<?= $isEdit ? site_url('admin/stories/update/' . $story['id']) : site_url('admin/stories/create') ?>" method="POST" enctype="multipart/form-data" class="admin-form" style="max-width: 1000px;">
    <?= csrf_field() ?>
    
    <div class="admin-grid admin-grid-equal">
        <!-- Main Content -->
        <div>
            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Story Content</h3>
                
                <div class="form-group">
                    <label class="form-label">Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-input" value="<?= esc($story['title'] ?? old('title')) ?>" required id="titleInput">
                </div>

                <div class="form-group">
                    <label class="form-label">Slug <span class="required">*</span></label>
                    <input type="text" name="slug" class="form-input" value="<?= esc($story['slug'] ?? old('slug')) ?>" required id="slugInput">
                    <p class="form-hint">URL-friendly version of the title (auto-generated)</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-textarea" style="min-height: 80px;" placeholder="Brief summary for cards and previews..."><?= esc($story['excerpt'] ?? old('excerpt')) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-textarea" style="min-height: 300px;"><?= esc($story['content'] ?? old('content')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Featured Image</h3>
                
                <div class="form-group">
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click or drag to upload image</span>
                            <input type="file" name="image" class="file-upload-input" accept="image/*">
                        </label>
                    </div>
                    <?php if ($isEdit && $story['image']): ?>
                    <div class="file-preview" style="margin-top: 16px;">
                        <div class="file-preview-item" style="width: 100%; height: auto;">
                            <img src="<?= asset_url($story['image']) ?>" alt="Current image" style="width: 100%; height: auto;">
                        </div>
                    </div>
                    <input type="hidden" name="image_url" value="<?= esc($story['image']) ?>">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Or paste image URL</label>
                    <input type="url" name="image_url" class="form-input" placeholder="https://..." value="<?= esc($story['image'] ?? old('image_url')) ?>">
                </div>
            </div>

            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Settings</h3>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($story['category'] ?? old('category')) === $cat ? 'selected' : '' ?>>
                            <?= ucfirst($cat) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= ($story['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($story['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_trailer" class="form-checkbox" value="1" <?= ($story['is_trailer'] ?? false) ? 'checked' : '' ?>>
                        <span>This is a Trailer</span>
                    </label>
                    <p class="form-hint">Shows a "TRAILER" badge on the card</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Story' : 'Create Story' ?>
        </button>
        <a href="<?= site_url('admin/stories') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<script>
// Auto-generate slug from title
document.getElementById('titleInput').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
    document.getElementById('slugInput').value = slug;
});
</script>

<?= $this->endSection() ?>

