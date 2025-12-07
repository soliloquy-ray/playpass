<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <h1 style="margin-bottom: 30px;">Admin Dashboard</h1>

        <!-- Quick Stats -->
        <div class="grid grid-4" style="margin-bottom: 40px;">
            <div class="card" style="text-align: center;">
                <p style="color: var(--text-muted); margin: 0 0 10px 0;">Total Revenue</p>
                <h2 style="color: var(--success); margin: 0;">₱245,890</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">↑ 12% from last month</p>
            </div>
            <div class="card" style="text-align: center;">
                <p style="color: var(--text-muted); margin: 0 0 10px 0;">Total Orders</p>
                <h2 style="color: var(--primary); margin: 0;">1,234</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">↑ 8% from last month</p>
            </div>
            <div class="card" style="text-align: center;">
                <p style="color: var(--text-muted); margin: 0 0 10px 0;">Active Users</p>
                <h2 style="color: #2196f3; margin: 0;">5,678</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">↑ 15% from last month</p>
            </div>
            <div class="card" style="text-align: center;">
                <p style="color: var(--text-muted); margin: 0 0 10px 0;">Conversion Rate</p>
                <h2 style="color: #ffd700; margin: 0;">3.24%</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;">↓ 0.5% from last month</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Recent Orders -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0;">Recent Orders</h3>
                    <a href="/admin/orders" style="color: var(--primary); font-size: 0.9rem;">View All →</a>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <th style="text-align: left; padding: 10px 0; font-weight: 700;">Order ID</th>
                                <th style="text-align: left; padding: 10px 0; font-weight: 700;">Customer</th>
                                <th style="text-align: left; padding: 10px 0; font-weight: 700;">Amount</th>
                                <th style="text-align: left; padding: 10px 0; font-weight: 700;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 10px 0;">#ORD-001</td>
                                <td style="padding: 10px 0;">John Doe</td>
                                <td style="padding: 10px 0; color: var(--success); font-weight: 700;">₱1,999</td>
                                <td style="padding: 10px 0;">
                                    <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">Completed</span>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 10px 0;">#ORD-002</td>
                                <td style="padding: 10px 0;">Jane Smith</td>
                                <td style="padding: 10px 0; color: var(--success); font-weight: 700;">₱549</td>
                                <td style="padding: 10px 0;">
                                    <span style="background-color: rgba(33, 150, 243, 0.2); color: #2196f3; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">Pending</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0;">#ORD-003</td>
                                <td style="padding: 10px 0;">Mike Johnson</td>
                                <td style="padding: 10px 0; color: var(--success); font-weight: 700;">₱299</td>
                                <td style="padding: 10px 0;">
                                    <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">Completed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="margin-top: 0;">Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="/admin/products" class="btn btn-secondary" style="justify-content: flex-start;">
                            📦 Manage Products
                        </a>
                        <a href="/admin/vouchers" class="btn btn-secondary" style="justify-content: flex-start;">
                            🎟️ Manage Vouchers
                        </a>
                        <a href="/admin/users" class="btn btn-secondary" style="justify-content: flex-start;">
                            👥 Manage Users
                        </a>
                        <a href="/admin/reports" class="btn btn-secondary" style="justify-content: flex-start;">
                            📊 View Reports
                        </a>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-top: 0;">System Status</h3>
                    <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items: center;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span>
                        <span style="font-size: 0.9rem;">Server Status: Online</span>
                    </div>
                    <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items: center;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span>
                        <span style="font-size: 0.9rem;">Database: Connected</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success);"></span>
                        <span style="font-size: 0.9rem;">Maya API: Connected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
