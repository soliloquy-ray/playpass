<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value">₱<?= number_format($stats['totalRevenue'] ?? 0, 2) ?></div>
        <div class="stat-label">Total Revenue</div>
        <span class="stat-change positive">+12.5%</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?= number_format($stats['totalOrders'] ?? 0) ?></div>
        <div class="stat-label">Total Orders</div>
        <span class="stat-change positive">+8.2%</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= number_format($stats['totalUsers'] ?? 0) ?></div>
        <div class="stat-label">Registered Users</div>
        <span class="stat-change positive">+15.3%</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛍️</div>
        <div class="stat-value"><?= number_format($stats['totalProducts'] ?? 0) ?></div>
        <div class="stat-label">Active Products</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-grid">
    <a href="/admin/products/new" class="quick-action-card">
        <i class="fas fa-plus-circle"></i>
        <span>Add Product</span>
    </a>
    <a href="/admin/stories/new" class="quick-action-card">
        <i class="fas fa-newspaper"></i>
        <span>New Story</span>
    </a>
    <a href="/admin/carousel/new" class="quick-action-card">
        <i class="fas fa-images"></i>
        <span>Add Slide</span>
    </a>
    <a href="/admin/vouchers/new" class="quick-action-card">
        <i class="fas fa-ticket-alt"></i>
        <span>Create Voucher</span>
    </a>
</div>

<!-- Main Content Grid -->
<div class="admin-grid">
    <!-- Recent Orders -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Recent Orders</h3>
            <a href="/admin/orders" class="btn-admin btn-admin-secondary btn-admin-sm">View All</a>
        </div>
        
        <?php if (!empty($recentOrders)): ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td><strong>#<?= esc($order['id']) ?></strong></td>
                        <td>User #<?= esc($order['user_id'] ?? 'N/A') ?></td>
                        <td style="color: var(--success); font-weight: 700;">₱<?= number_format($order['grand_total'] ?? 0, 2) ?></td>
                        <td>
                            <span class="status-badge status-<?= esc($order['payment_status'] ?? 'pending') ?>">
                                <?= ucfirst(esc($order['payment_status'] ?? 'pending')) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-shopping-cart"></i></div>
            <h4 class="empty-state-title">No orders yet</h4>
            <p class="empty-state-text">Orders will appear here once customers start purchasing.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Recent Stories -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Recent Stories</h3>
            <a href="/admin/stories" class="btn-admin btn-admin-secondary btn-admin-sm">View All</a>
        </div>
        
        <?php if (!empty($recentStories)): ?>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($recentStories as $story): ?>
            <li style="padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 0.95rem;"><?= esc($story['title']) ?></h4>
                        <span class="status-badge status-<?= $story['status'] ?>"><?= ucfirst($story['status']) ?></span>
                    </div>
                    <a href="/admin/stories/edit/<?= $story['id'] ?>" class="btn-admin btn-admin-secondary btn-admin-sm btn-admin-icon">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
            <h4 class="empty-state-title">No stories yet</h4>
            <p class="empty-state-text">Start creating content for your users.</p>
            <a href="/admin/stories/new" class="btn-admin btn-admin-primary">Create Story</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

