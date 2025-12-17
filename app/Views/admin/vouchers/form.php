<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($campaign); ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/vouchers') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Vouchers
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

<form action="<?= $isEdit ? site_url('admin/vouchers/update/' . $campaign['id']) : site_url('admin/vouchers/create') ?>" method="POST" class="admin-form">
    <?= csrf_field() ?>
    
    <div class="admin-grid admin-grid-equal">
        <!-- Campaign Info -->
        <div class="admin-card">
            <h3 class="admin-card-title" style="margin-bottom: 24px;">Campaign Details</h3>
            
            <div class="form-group">
                <label class="form-label">Campaign Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-input" value="<?= esc($campaign['name'] ?? old('name')) ?>" required placeholder="e.g., Holiday Sale 50% Off">
            </div>

            <div class="form-group">
                <label class="form-label">Label</label>
                <input type="text" name="label" class="form-input" value="<?= esc($campaign['label'] ?? old('label') ?? ($campaign['name'] ?? '')) ?>" placeholder="Display label (defaults to name)">
                <p class="form-hint">Short label shown to users (e.g., "50% OFF", "Save ₱100")</p>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" style="min-height: 80px;"><?= esc($campaign['description'] ?? old('description')) ?></textarea>
            </div>

            <?php if (!$isEdit): ?>
            <div class="form-group">
                <label class="form-label">Code Type <span class="required">*</span></label>
                <select name="code_type" class="form-select" id="codeType" required>
                    <option value="universal">Universal Code (single code for everyone)</option>
                    <option value="unique_batch">Unique Batch (generate many unique codes)</option>
                </select>
            </div>

            <!-- Universal Code Options -->
            <div id="universalOptions">
                <div class="form-group">
                    <label class="form-label">Voucher Code <span class="required">*</span></label>
                    <input type="text" name="voucher_code" class="form-input" placeholder="e.g., SAVE50" style="text-transform: uppercase;">
                </div>
            </div>

            <!-- Unique Batch Options -->
            <div id="batchOptions" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code Prefix</label>
                        <input type="text" name="code_prefix" class="form-input" placeholder="e.g., PLAY" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Number of Codes</label>
                        <input type="number" name="batch_size" class="form-input" value="100" min="1" max="10000">
                    </div>
                </div>
                <p class="form-hint">Codes will be generated like: PLAY-A1B2C3D4</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Discount Settings -->
        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Discount Settings</h3>

                <div class="form-group">
                    <label class="form-label">Discount Type <span class="required">*</span></label>
                    <select name="discount_type" class="form-select" id="discountType" required>
                        <option value="fixed_amount" <?= ($campaign['discount_type'] ?? '') === 'fixed_amount' ? 'selected' : '' ?>>Fixed Amount (₱)</option>
                        <option value="percentage" <?= ($campaign['discount_type'] ?? '') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Discount Value <span class="required">*</span></label>
                    <input type="number" name="discount_value" class="form-input" step="0.01" value="<?= esc($campaign['discount_value'] ?? old('discount_value')) ?>" required>
                    <p class="form-hint" id="discountHint">Amount in pesos to discount</p>
                </div>

                <div class="form-group" id="maxDiscountGroup" style="display: none;">
                    <label class="form-label">Maximum Discount (₱)</label>
                    <input type="number" name="max_discount_amount" class="form-input" step="0.01" value="<?= esc($campaign['max_discount_amount'] ?? old('max_discount_amount')) ?>">
                    <p class="form-hint">Cap the maximum discount for percentage vouchers</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Minimum Spend (₱)</label>
                    <input type="number" name="min_spend_amount" class="form-input" step="0.01" value="<?= esc($campaign['min_spend_amount'] ?? 0) ?>">
                    <p class="form-hint">Minimum order amount to use this voucher</p>
                </div>
            </div>

            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Usage Limits</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Per User Limit</label>
                        <input type="number" name="usage_limit_per_user" class="form-input" value="<?= esc($campaign['usage_limit_per_user'] ?? 1) ?>" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Usage Limit</label>
                        <input type="number" name="total_usage_limit" class="form-input" value="<?= esc($campaign['total_usage_limit'] ?? '') ?>" placeholder="Unlimited">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_stackable" class="form-checkbox" value="1" <?= ($campaign['is_stackable'] ?? false) ? 'checked' : '' ?>>
                        <span>Stackable (can be used with other vouchers)</span>
                    </label>
                </div>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Validity Period</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date <span class="required">*</span></label>
                        <input type="datetime-local" name="start_date" class="form-input" value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($campaign['start_date'])) : date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date <span class="required">*</span></label>
                        <input type="datetime-local" name="end_date" class="form-input" value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($campaign['end_date'])) : date('Y-m-d\TH:i', strtotime('+30 days')) ?>" required>
                    </div>
                </div>
                <p class="form-hint" style="margin-top: 8px;">Dates are in Asia/Manila timezone. To invalidate a voucher, set end date to past.</p>
            </div>

            <div class="admin-card" style="margin-top: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Product Applicability</h3>

                <div class="form-group">
                    <label class="form-label">Applicable Products</label>
                    <p class="form-hint" style="margin-bottom: 12px;">Leave empty to apply to all products. Select specific products to restrict this voucher.</p>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                        <?php if (!empty($products ?? [])): ?>
                            <?php 
                            $applicableProductIds = $applicableProductIds ?? [];
                            foreach ($products as $product): 
                            ?>
                            <label class="form-checkbox-group" style="display: block; margin-bottom: 8px;">
                                <input type="checkbox" name="applicable_products[]" class="form-checkbox" value="<?= $product['id'] ?>" 
                                    <?= in_array($product['id'], $applicableProductIds) ? 'checked' : '' ?>>
                                <span><?= esc($product['name']) ?> - ₱<?= number_format($product['price'], 2) ?></span>
                            </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: var(--text-muted); margin: 0;">No products available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions-sticky">
        <div class="form-actions-inner">
            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Update Campaign' : 'Create Campaign' ?>
            </button>
            <a href="<?= site_url('admin/vouchers') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
        </div>
    </div>
</form>

<script>
// Toggle code type options
document.getElementById('codeType')?.addEventListener('change', function() {
    document.getElementById('universalOptions').style.display = this.value === 'universal' ? 'block' : 'none';
    document.getElementById('batchOptions').style.display = this.value === 'unique_batch' ? 'block' : 'none';
});

// Toggle discount type hint
document.getElementById('discountType').addEventListener('change', function() {
    const hint = document.getElementById('discountHint');
    const maxGroup = document.getElementById('maxDiscountGroup');
    
    if (this.value === 'percentage') {
        hint.textContent = 'Percentage to discount (e.g., 15 for 15%)';
        maxGroup.style.display = 'block';
    } else {
        hint.textContent = 'Amount in pesos to discount';
        maxGroup.style.display = 'none';
    }
});

// Trigger on load
document.getElementById('discountType').dispatchEvent(new Event('change'));
</script>

<?= $this->endSection() ?>

