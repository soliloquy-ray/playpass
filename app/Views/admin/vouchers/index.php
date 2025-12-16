<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Create and manage voucher campaigns</p>
    <a href="<?= site_url('admin/vouchers/new') ?>" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Create Campaign
    </a>
</div>

<!-- Filters -->
<div class="filters-bar">
    <input type="search" class="filter-input" placeholder="Search campaigns..." id="searchInput">
    <select class="filter-select" id="typeFilter">
        <option value="">All Types</option>
        <option value="fixed_amount">Fixed Amount</option>
        <option value="percentage">Percentage</option>
    </select>
    <select class="filter-select" id="statusFilter">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="expired">Expired</option>
    </select>
</div>

<!-- Campaigns Table -->
<div class="admin-card">
    <?php if (!empty($campaigns)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Type</th>
                    <th>Discount</th>
                    <th>Usage</th>
                    <th>Valid Period</th>
                    <th>Status</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaigns as $campaign): ?>
                <?php 
                    $now = time();
                    $start = strtotime($campaign['start_date']);
                    $end = strtotime($campaign['end_date']);
                    $status = ($now >= $start && $now <= $end) ? 'active' : (($now > $end) ? 'expired' : 'pending');
                ?>
                <tr>
                    <td>
                        <strong><?= esc($campaign['name']) ?></strong>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 0;">
                            <?= $campaign['code_type'] === 'universal' ? 'Universal Code' : 'Unique Codes Batch' ?>
                        </p>
                    </td>
                    <td>
                        <span style="text-transform: capitalize;"><?= str_replace('_', ' ', $campaign['discount_type']) ?></span>
                    </td>
                    <td style="color: var(--primary); font-weight: 700;">
                        <?php if ($campaign['discount_type'] === 'percentage'): ?>
                            <?= number_format($campaign['discount_value']) ?>%
                        <?php else: ?>
                            ₱<?= number_format($campaign['discount_value'], 2) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= number_format($campaign['used_count']) ?> / <?= $campaign['total_usage_limit'] ? number_format($campaign['total_usage_limit']) : '∞' ?>
                    </td>
                    <td style="font-size: 0.85rem;">
                        <?= date('M d', $start) ?> - <?= date('M d, Y', $end) ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $status ?>">
                            <?= ucfirst($status) ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= site_url('admin/vouchers/codes/' . $campaign['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm" title="View Codes">
                                <i class="fas fa-ticket-alt"></i>
                            </a>
                            <a href="<?= site_url('admin/vouchers/edit/' . $campaign['id']) ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= site_url('admin/vouchers/delete/' . $campaign['id']) ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this campaign and all its codes?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-ticket-alt"></i></div>
        <h4 class="empty-state-title">No voucher campaigns</h4>
        <p class="empty-state-text">Create voucher campaigns to offer discounts to your customers.</p>
        <a href="<?= site_url('admin/vouchers/new') ?>" class="btn-admin btn-admin-primary">Create Campaign</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
