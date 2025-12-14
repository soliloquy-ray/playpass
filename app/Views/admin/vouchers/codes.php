<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="/admin/vouchers" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Vouchers
    </a>
</div>

<!-- Campaign Info -->
<div class="admin-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h2 style="margin: 0 0 8px;"><?= esc($campaign['name']) ?></h2>
            <p style="color: var(--text-muted); margin: 0;">
                <?= $campaign['code_type'] === 'universal' ? 'Universal Code' : 'Unique Codes Batch' ?> • 
                <?php if ($campaign['discount_type'] === 'percentage'): ?>
                    <?= number_format($campaign['discount_value']) ?>% off
                <?php else: ?>
                    ₱<?= number_format($campaign['discount_value'], 2) ?> off
                <?php endif; ?>
            </p>
        </div>
        
        <?php if ($campaign['code_type'] === 'unique_batch'): ?>
        <form action="/admin/vouchers/generate-codes/<?= $campaign['id'] ?>" method="POST" style="display: flex; gap: 12px; align-items: flex-end;">
            <?= csrf_field() ?>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">Prefix</label>
                <input type="text" name="code_prefix" class="form-input" value="PLAY" style="width: 100px; text-transform: uppercase;">
            </div>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">Amount</label>
                <input type="number" name="batch_size" class="form-input" value="100" min="1" max="1000" style="width: 100px;">
            </div>
            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-plus"></i> Generate More
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon">🎟️</div>
        <div class="stat-value"><?= number_format(count($codes)) ?></div>
        <div class="stat-label">Total Codes</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= number_format(count(array_filter($codes, fn($c) => $c['is_redeemed']))) ?></div>
        <div class="stat-label">Redeemed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?= number_format(count(array_filter($codes, fn($c) => !$c['is_redeemed']))) ?></div>
        <div class="stat-label">Available</div>
    </div>
</div>

<!-- Codes Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Voucher Codes</h3>
        <input type="search" class="filter-input" placeholder="Search codes..." style="width: 250px;">
    </div>
    
    <?php if (!empty($codes)): ?>
    <div class="admin-table-wrapper" style="max-height: 500px; overflow-y: auto;">
        <table class="admin-table">
            <thead style="position: sticky; top: 0; background: var(--card-bg);">
                <tr>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Redeemed At</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $code): ?>
                <tr>
                    <td>
                        <code style="background: rgba(216, 54, 159, 0.15); color: var(--primary); padding: 6px 12px; border-radius: 4px; font-weight: 700; letter-spacing: 1px;">
                            <?= esc($code['code']) ?>
                        </code>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $code['is_redeemed'] ? 'completed' : 'active' ?>">
                            <?= $code['is_redeemed'] ? 'Redeemed' : 'Available' ?>
                        </span>
                    </td>
                    <td>
                        <?= $code['redeemed_at'] ? date('M d, Y H:i', strtotime($code['redeemed_at'])) : '-' ?>
                    </td>
                    <td>
                        <?= $code['redeemed_by_user_id'] ? '#' . $code['redeemed_by_user_id'] : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-ticket-alt"></i></div>
        <h4 class="empty-state-title">No codes generated</h4>
        <p class="empty-state-text">Generate codes for this campaign.</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

