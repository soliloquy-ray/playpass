<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="margin: 0;">Manage Products</h1>
            <a href="/admin/products/create" class="btn btn-primary">
                ➕ Add Product
            </a>
        </div>

        <!-- Filters -->
        <div style="background-color: var(--card-bg); padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap;">
            <input type="search" placeholder="Search products..." class="input-dark" style="flex: 1; min-width: 200px; margin: 0;">
            <select class="input-dark" style="min-width: 150px; margin: 0;">
                <option>All Categories</option>
                <option>Games</option>
                <option>Streaming</option>
                <option>Bundles</option>
            </select>
            <select class="input-dark" style="min-width: 150px; margin: 0;">
                <option>All Status</option>
                <option>Published</option>
                <option>Draft</option>
                <option>Archived</option>
            </select>
        </div>

        <!-- Products Table -->
        <div class="card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); background-color: var(--secondary);">
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Product Name</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">SKU</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Price</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Stock</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Status</th>
                        <th style="text-align: left; padding: 15px; font-weight: 700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px;">
                            <div>
                                <strong>Game Bundle Premium</strong>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 5px 0 0 0;">Bundle of 10 AAA games</p>
                            </div>
                        </td>
                        <td style="padding: 15px;">GB-PREM-001</td>
                        <td style="padding: 15px; color: var(--success); font-weight: 700;">₱1,999</td>
                        <td style="padding: 15px;">∞ (Digital)</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Published</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/products/edit/1" class="btn btn-secondary btn-small">Edit</a>
                                <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 15px;">
                            <div>
                                <strong>Netflix Subscription (3 months)</strong>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 5px 0 0 0;">3-month Netflix Premium access</p>
                            </div>
                        </td>
                        <td style="padding: 15px;">NETFLIX-3M</td>
                        <td style="padding: 15px; color: var(--success); font-weight: 700;">₱549</td>
                        <td style="padding: 15px;">∞ (Digital)</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(76, 175, 80, 0.2); color: var(--success); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Published</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/products/edit/2" class="btn btn-secondary btn-small">Edit</a>
                                <button class="btn btn-outline btn-small" style="color: var(--danger); border-color: var(--danger);">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px;">
                            <div>
                                <strong>In-App Gems Pack</strong>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 5px 0 0 0;">5,000 premium gems</p>
                            </div>
                        </td>
                        <td style="padding: 15px;">GEMS-5K</td>
                        <td style="padding: 15px; color: var(--success); font-weight: 700;">₱299</td>
                        <td style="padding: 15px;">∞ (Digital)</td>
                        <td style="padding: 15px;">
                            <span style="background-color: rgba(255, 152, 0, 0.2); color: var(--warning); padding: 4px 12px; border-radius: 12px; font-size: 0.85rem;">Draft</span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/products/edit/3" class="btn btn-secondary btn-small">Edit</a>
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
            <button class="btn btn-secondary">3</button>
            <button class="btn btn-secondary">Next →</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
