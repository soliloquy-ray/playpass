<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Account</h1>

<div style="background-color:#051429; padding:20px; border-radius:8px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;">
    <div>
        <h2><?= esc($user['email'] ?? 'User') ?></h2>
        <?php if (! empty($user['phone'])): ?>
            <p><?= esc($user['phone']) ?></p>
        <?php endif; ?>
    </div>
    <div>
        <a href="#" class="btn btn-primary">Edit Profile</a>
    </div>
</div>

<!-- Points and Vouchers -->
<div style="display:flex; flex-wrap:wrap; gap:20px; margin-top:20px;">
    <div style="flex:1; min-width:200px; background-color:#051429; padding:20px; border-radius:8px;">
        <h3>Points Earned</h3>
        <p style="font-size:2rem; font-weight:bold; color:#ffda44;"><?= esc($points) ?></p>
        <a href="#" class="btn btn-primary">Redeem</a>
    </div>
    <div style="flex:2; min-width:200px; background-color:#051429; padding:20px; border-radius:8px;">
        <h3>Vouchers and Discounts</h3>
        <?php if (! empty($vouchers)): ?>
            <?php foreach ($vouchers as $voucher): ?>
                <div style="margin-bottom:10px; padding:10px; border:1px solid #333; border-radius:4px; background-color:#0f2144;">
                    <strong><?= esc($voucher['title']) ?></strong><br>
                    <small><?= esc($voucher['description']) ?></small><br>
                    <a href="#" class="btn btn-secondary" style="margin-top:5px;">Use Voucher</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No vouchers available.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Purchase History -->
<div style="margin-top:30px; background-color:#051429; padding:20px; border-radius:8px;">
    <h3>Purchase History</h3>
    <?php if (! empty($orders)): ?>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding:8px; border-bottom:1px solid #333;">Date</th>
                    <th style="text-align:left; padding:8px; border-bottom:1px solid #333;">Order</th>
                    <th style="text-align:right; padding:8px; border-bottom:1px solid #333;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #333;">
                            <?= date('M d, Y H:i A', strtotime($order['created_at'])) ?>
                        </td>
                        <td style="padding:8px; border-bottom:1px solid #333;">
                            Order #<?= esc($order['order_number']) ?>
                        </td>
                        <td style="padding:8px; text-align:right; border-bottom:1px solid #333;">
                            ₱<?= number_format($order['total'], 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No purchases yet.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>