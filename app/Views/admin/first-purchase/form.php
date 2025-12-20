<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($promo); ?>

<div style="max-width: 800px;">
    <form action="<?= $isEdit ? site_url('admin/first-purchase-promos/update/' . $promo['id']) : site_url('admin/first-purchase-promos/create') ?>" method="POST">
        <?= csrf_field() ?>

        <!-- Basic Info -->
        <div class="admin-card" style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 20px; color: var(--text-primary);">
                <i class="fas fa-gift" style="color: var(--primary);"></i> Promo Details
            </h3>

            <div class="form-group">
                <label class="form-label">Promo Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" class="form-control" 
                       value="<?= old('name', $promo['name'] ?? '') ?>" 
                       placeholder="e.g., Welcome Discount" required>
                <small class="form-hint">Internal name for reference</small>
            </div>

            <div class="form-group">
                <label class="form-label">Display Label</label>
                <input type="text" name="label" class="form-control" 
                       value="<?= old('label', $promo['label'] ?? '') ?>" 
                       placeholder="e.g., First Purchase Special!">
                <small class="form-hint">Shown to customers at checkout</small>
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" 
                           <?= old('is_active', $promo['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <span class="form-check-label">Active</span>
                </label>
                <small class="form-hint">Only active promos are applied to checkouts</small>
            </div>
        </div>

        <!-- Discount Settings -->
        <div class="admin-card" style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 20px; color: var(--text-primary);">
                <i class="fas fa-percent" style="color: var(--primary);"></i> Discount Settings
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Discount Type <span style="color: #ef4444;">*</span></label>
                    <select name="discount_type" class="form-control" id="discountType" required>
                        <option value="fixed_amount" <?= old('discount_type', $promo['discount_type'] ?? '') === 'fixed_amount' ? 'selected' : '' ?>>Fixed Amount (₱)</option>
                        <option value="percentage" <?= old('discount_type', $promo['discount_type'] ?? '') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Discount Value <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="discount_value" class="form-control" 
                           value="<?= old('discount_value', $promo['discount_value'] ?? '') ?>" 
                           step="0.01" min="0" placeholder="e.g., 50" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Minimum Spend Amount</label>
                    <input type="number" name="min_spend_amount" class="form-control" 
                           value="<?= old('min_spend_amount', $promo['min_spend_amount'] ?? 0) ?>" 
                           step="0.01" min="0" placeholder="0 = No minimum">
                    <small class="form-hint">Cart must meet this amount</small>
                </div>

                <div class="form-group" id="maxDiscountGroup">
                    <label class="form-label">Max Discount Cap</label>
                    <input type="number" name="max_discount_amount" class="form-control" 
                           value="<?= old('max_discount_amount', $promo['max_discount_amount'] ?? '') ?>" 
                           step="0.01" min="0" placeholder="Optional">
                    <small class="form-hint">For % discounts, cap the max peso value</small>
                </div>
            </div>
        </div>

        <!-- Product Applicability -->
        <div class="admin-card" style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 20px; color: var(--text-primary);">
                <i class="fas fa-box" style="color: var(--primary);"></i> Applicable Products
            </h3>
            
            <p style="color: var(--text-muted); margin-bottom: 16px;">
                Leave all unchecked to apply to <strong>all products</strong>, or select specific products.
            </p>

            <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                    <label class="form-check" style="display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                        <input type="checkbox" name="applicable_products[]" value="<?= $product['id'] ?>"
                               <?= in_array($product['id'], $applicableProductIds ?? []) ? 'checked' : '' ?>>
                        <span class="form-check-label" style="display: flex; justify-content: space-between; width: 100%;">
                            <span><?= esc($product['name']) ?></span>
                            <span style="color: var(--text-muted);">₱<?= number_format($product['price'], 2) ?></span>
                        </span>
                    </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted); text-align: center; padding: 20px;">No products available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Update Promo' : 'Create Promo' ?>
            </button>
            <a href="<?= site_url('admin/first-purchase-promos') ?>" class="btn-admin btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
// Show/hide max discount cap based on discount type
document.getElementById('discountType').addEventListener('change', function() {
    const maxDiscountGroup = document.getElementById('maxDiscountGroup');
    maxDiscountGroup.style.opacity = this.value === 'percentage' ? '1' : '0.5';
});
// Initial state
document.getElementById('discountType').dispatchEvent(new Event('change'));
</script>

<?= $this->endSection() ?>
