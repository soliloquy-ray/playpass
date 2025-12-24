<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div style="margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Update company details displayed in the footer and contact pages</p>
</div>

<form action="<?= site_url('admin/company-info/save') ?>" method="POST" class="admin-form" style="max-width: 800px;">
    <?= csrf_field() ?>
    
    <div class="admin-card">
        <h3 class="admin-card-title" style="margin-bottom: 24px;">Company Details</h3>
        
        <?php foreach ($info as $item): ?>
        <div class="form-group">
            <label class="form-label"><?= esc($item['label']) ?></label>
            <?php if ($item['key'] === 'address'): ?>
            <textarea name="<?= esc($item['key']) ?>" class="form-textarea" style="min-height: 100px;" placeholder="Enter company address..."><?= esc($item['value']) ?></textarea>
            <p class="form-hint">Use line breaks for multi-line addresses. HTML &lt;br&gt; tags will be added automatically.</p>
            <?php elseif ($item['key'] === 'copyright'): ?>
            <input type="text" name="<?= esc($item['key']) ?>" class="form-input" value="<?= esc($item['value']) ?>" placeholder="© 2024 Company Name. All Rights Reserved">
            <p class="form-hint">Include © symbol and year</p>
            <?php else: ?>
            <input type="text" name="<?= esc($item['key']) ?>" class="form-input" value="<?= esc($item['value']) ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Submit Button -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>

<?= $this->endSection() ?>
