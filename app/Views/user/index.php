<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px; max-width: 1200px; margin: 0 auto;">
        
        <!-- Sidebar -->
        <div style="background-color: var(--card-bg); border-radius: 12px; padding: 20px; height: fit-content;">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, #4caf50 100%); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 700;">
                    <?= strtoupper(substr($user['name'] ?? 'User', 0, 1)) ?>
                </div>
                <h3 style="margin: 0 0 5px 0;"><?= esc($user['name'] ?? 'User') ?></h3>
                <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;"><?= esc($user['email']) ?></p>
            </div>

            <div style="background-color: var(--secondary); padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <p style="color: var(--text-muted); margin: 0 0 5px 0; font-size: 0.9rem;">Account Balance</p>
                <p style="color: var(--success); font-size: 1.5rem; font-weight: 700; margin: 0;">
                    ₱<?= number_format($user['balance'] ?? 0, 2) ?>
                </p>
            </div>

            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="/account" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    📋 Dashboard
                </a>
                <a href="/account/orders" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    📦 My Orders
                </a>
                <a href="/account/subscriptions" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    🎁 Subscriptions
                </a>
                <a href="/account/wishlist" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    ♡ Wishlist
                </a>
                <a href="/account/vouchers" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    🎟️ My Vouchers
                </a>
                <a href="/account/settings" class="btn btn-secondary" style="justify-content: flex-start; width: 100%;">
                    ⚙️ Settings
                </a>
                <a href="/logout" class="btn btn-outline" style="justify-content: flex-start; width: 100%; color: var(--danger); border-color: var(--danger);">
                    🚪 Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div>
            <h1 style="margin-bottom: 30px;">Welcome Back, <?= esc(explode(' ', $user['name'] ?? 'User')[0]) ?>! 👋</h1>

            <!-- Quick Stats -->
            <div class="grid grid-3" style="margin-bottom: 40px;">
                <div class="card" style="text-align: center;">
                    <p style="color: var(--text-muted); margin: 0 0 10px 0;">Total Orders</p>
                    <h2 style="color: var(--primary); margin: 0;">12</h2>
                </div>
                <div class="card" style="text-align: center;">
                    <p style="color: var(--text-muted); margin: 0 0 10px 0;">Active Subscriptions</p>
                    <h2 style="color: var(--success); margin: 0;">3</h2>
                </div>
                <div class="card" style="text-align: center;">
                    <p style="color: var(--text-muted); margin: 0 0 10px 0;">Loyalty Points</p>
                    <h2 style="color: #ffd700; margin: 0;">1,250</h2>
                </div>
            </div>

            <!-- Recent Orders -->
            <h2 style="margin-bottom: 20px;">Recent Orders</h2>
            <div class="card" style="margin-bottom: 40px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="text-align: left; padding: 12px 0; font-weight: 700;">Order ID</th>
                            <th style="text-align: left; padding: 12px 0; font-weight: 700;">Product</th>
                            <th style="text-align: left; padding: 12px 0; font-weight: 700;">Amount</th>
                            <th style="text-align: left; padding: 12px 0; font-weight: 700;">Status</th>
                            <th style="text-align: left; padding: 12px 0; font-weight: 700;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px 0;">#ORD-001</td>
                            <td style="padding: 12px 0;">Game Bundle Premium</td>
                            <td style="padding: 12px 0; color: var(--success); font-weight: 700;">₱1,999.00</td>
                            <td style="padding: 12px 0;">
                                <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">
                                    Completed
                                </span>
                            </td>
                            <td style="padding: 12px 0; color: var(--text-muted);">Dec 5, 2025</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px 0;">#ORD-002</td>
                            <td style="padding: 12px 0;">Netflix Subscription (3 months)</td>
                            <td style="padding: 12px 0; color: var(--success); font-weight: 700;">₱549.00</td>
                            <td style="padding: 12px 0;">
                                <span style="background-color: rgba(33, 150, 243, 0.2); color: #2196f3; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">
                                    Active
                                </span>
                            </td>
                            <td style="padding: 12px 0; color: var(--text-muted);">Dec 3, 2025</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">#ORD-003</td>
                            <td style="padding: 12px 0;">In-App Gems Pack</td>
                            <td style="padding: 12px 0; color: var(--success); font-weight: 700;">₱299.00</td>
                            <td style="padding: 12px 0;">
                                <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">
                                    Completed
                                </span>
                            </td>
                            <td style="padding: 12px 0; color: var(--text-muted);">Dec 1, 2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Active Subscriptions -->
            <h2 style="margin-bottom: 20px;">Active Subscriptions</h2>
            <div class="grid grid-2" style="margin-bottom: 40px;">
                <div class="card">
                    <h3 style="margin-top: 0;">Netflix Premium</h3>
                    <p style="color: var(--text-muted); margin-bottom: 15px;">
                        Expires in <strong>45 days</strong>
                    </p>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-secondary btn-small">Renew</button>
                        <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Cancel</button>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-top: 0;">Spotify Premium</h3>
                    <p style="color: var(--text-muted); margin-bottom: 15px;">
                        Expires in <strong>20 days</strong>
                    </p>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-secondary btn-small">Renew</button>
                        <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Top Up CTA -->
            <div style="background: linear-gradient(135deg, var(--primary) 0%, #4caf50 100%); padding: 30px; border-radius: 12px; text-align: center;">
                <h3 style="margin-top: 0;">Low on Balance?</h3>
                <p style="margin-bottom: 20px;">Top up your account to get started with exclusive deals!</p>
                <button class="btn btn-secondary">Top Up Now</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
