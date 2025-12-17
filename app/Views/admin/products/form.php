<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($product); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/products') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Products
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

<form action="<?= $isEdit ? site_url('admin/products/update/' . $product['id']) : site_url('admin/products/create') ?>" method="POST" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    
    <div class="admin-grid admin-grid-equal">
        <!-- Main Info -->
        <div class="admin-card">
            <h3 class="admin-card-title" style="margin-bottom: 24px;">Product Information</h3>
            
            <div class="form-group">
                <label class="form-label">Product Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-input" value="<?= esc($product['name'] ?? old('name')) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">SKU <span class="required">*</span></label>
                    <input type="text" name="sku" class="form-input" value="<?= esc($product['sku'] ?? old('sku')) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Price (₱) <span class="required">*</span></label>
                    <input type="number" name="price" class="form-input" step="0.01" value="<?= esc($product['price'] ?? old('price')) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea"><?= esc($product['description'] ?? old('description')) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select">
                        <option value="">No Brand</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?= $brand['id'] ?>" <?= ($product['brand_id'] ?? old('brand_id')) == $brand['id'] ? 'selected' : '' ?>>
                            <?= esc($brand['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Badge Label</label>
                    <input type="text" name="badge_label" class="form-input" placeholder="e.g., Hot, New, Sale" value="<?= esc($product['badge_label'] ?? old('badge_label')) ?>">
                </div>
            </div>
        </div>

        <!-- Media & Settings -->
        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Product Image</h3>
                
                <div class="form-group">
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click or drag to upload image</span>
                            <input type="file" name="thumbnail" class="file-upload-input" accept="image/*">
                        </label>
                    </div>
                    <?php if ($isEdit && $product['thumbnail_url']): ?>
                    <div class="file-preview" style="margin-top: 16px;">
                        <div class="file-preview-item">
                            <img src="<?= asset_url($product['thumbnail_url']) ?>" alt="Current thumbnail">
                        </div>
                    </div>
                    <input type="hidden" name="thumbnail_url" value="<?= esc($product['thumbnail_url']) ?>">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Or paste image URL</label>
                    <input type="url" name="thumbnail_url" class="form-input" placeholder="https://..." value="<?= esc($product['thumbnail_url'] ?? old('thumbnail_url')) ?>">
                </div>
            </div>

            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Settings</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Points to Earn</label>
                        <input type="number" name="points_to_earn" class="form-input" value="<?= esc($product['points_to_earn'] ?? old('points_to_earn') ?? 0) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input" value="<?= esc($product['sort_order'] ?? old('sort_order') ?? 0) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Maya Product Code</label>
                    <input type="text" name="maya_product_code" class="form-input" value="<?= esc($product['maya_product_code'] ?? old('maya_product_code')) ?>">
                    <p class="form-hint">For payment integration</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Background Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" name="bg_color" class="color-picker-input" value="<?= esc($product['bg_color'] ?? '#1a1a1a') ?>">
                        <input type="text" class="form-input" style="width: 120px;" value="<?= esc($product['bg_color'] ?? '#1a1a1a') ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Visibility</h3>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($product['is_active'] ?? true) ? 'checked' : '' ?>>
                        <span>Active (visible on site)</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_featured" class="form-checkbox" value="1" <?= ($product['is_featured'] ?? false) ? 'checked' : '' ?>>
                        <span>Featured Product</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_new" class="form-checkbox" value="1" <?= ($product['is_new'] ?? false) ? 'checked' : '' ?>>
                        <span>Mark as New</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_bundle" class="form-checkbox" value="1" <?= ($product['is_bundle'] ?? false) ? 'checked' : '' ?>>
                        <span>Bundle Product</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> <?= $isEdit ? 'Update Product' : 'Create Product' ?>
        </button>
        <a href="<?= site_url('admin/products') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
    </div>
</form>

<?= $this->endSection() ?>

