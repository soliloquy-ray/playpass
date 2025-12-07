<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="margin: 0;">Manage Vouchers</h1>
            <a href="/admin/vouchers/create" class="btn btn-primary">
                ➕ Create Voucher
            </a>
        </div>

        <!-- Filters -->
        <div style="background-color: var(--card-bg); padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap;">
            <input type="search" placeholder="Search voucher codes..." class="input-dark" style="flex: 1; min-width: 200px; margin: 0;">
            <select class="input-dark" style="min-width: 150px; margin: 0;">
                <option>All Status</option>
                <option>Active</option>
                <option>Expired</option>
                <option>Inactive</option>
            </select>
            <select class="input-dark" style="min-width: 150px; margin: 0;">
                <option>All Types</option>
                <option>Fixed Amount</option>
                <option>Percentage</option>
            </select>
        </div>

        <!-- Vouchers Table -->
        <div class="card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); background-color: var(--secondary);">
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Code</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Description</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Discount</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Used / Limit</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Valid Until</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Status</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px;"><strong>FIRST50</strong></td>
                        <td style="padding: 15px;">First-time top-up</td>
                        <td style="padding: 15px; color: var(--primary); font-weight: 700;">50%</td>
                        <td style="padding: 15px;">234 / 1000</td>
                        <td style="padding: 15px;">Dec 31, 2025</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Active</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/vouchers/edit/1" class="btn btn-secondary btn-small">Edit</a>
                                <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px;"><strong>SAVE100</strong></td>
                        <td style="padding: 15px;">Save ₱100 on purchases</td>
                        <td style="padding: 15px; color: var(--primary); font-weight: 700;">₱100 Fixed</td>
                        <td style="padding: 15px;">456 / 500</td>
                        <td style="padding: 15px;">Dec 15, 2025</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Active</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/vouchers/edit/2" class="btn btn-secondary btn-small">Edit</a>
                                <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;"><strong>HOLIDAY30</strong></td>
                        <td style="padding: 15px;">Holiday special discount</td>
                        <td style="padding: 15px; color: var(--primary); font-weight: 700;">30%</td>
                        <td style="padding: 15px;">1200 / ∞</td>
                        <td style="padding: 15px;">Dec 10, 2025</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(244, 67, 54, 0.2); color: var(--danger); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Expired</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/vouchers/edit/3" class="btn btn-secondary btn-small">Edit</a>
                                <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">
            <button class="btn btn-secondary" disabled>← Previous</button>
            <button class="btn btn-secondary" style="background-color: var(--primary); color: white;">1</button>
            <button class="btn btn-secondary">2</button>
            <button class="btn btn-secondary">Next →</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
