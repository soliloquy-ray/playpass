<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-muted); margin: 0;">Create and manage your stories and articles</p>
    <a href="/admin/stories/new" class="btn-admin btn-admin-primary">
        <i class="fas fa-plus"></i> Add Story
    </a>
</div>

<!-- Filters -->
<div class="filters-bar">
    <input type="search" class="filter-input" placeholder="Search stories..." id="searchInput">
    <select class="filter-select" id="categoryFilter">
        <option value="">All Categories</option>
        <option value="news">News</option>
        <option value="trailer">Trailer</option>
        <option value="review">Review</option>
        <option value="gaming">Gaming</option>
        <option value="entertainment">Entertainment</option>
    </select>
    <select class="filter-select" id="statusFilter">
        <option value="">All Status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
    </select>
</div>

<!-- Stories Table -->
<div class="admin-card">
    <?php if (!empty($stories)): ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stories as $story): ?>
                <tr>
                    <td>
                        <?php if ($story['image']): ?>
                        <img src="<?= esc($story['image']) ?>" alt="" class="table-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                        <?php else: ?>
                        <div class="table-thumbnail" style="width: 80px; height: 50px; display: flex; align-items: center; justify-content: center; background: var(--card-bg);">
                            <i class="fas fa-image" style="color: var(--text-muted);"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <strong><?= esc($story['title']) ?></strong>
                            <?php if ($story['is_trailer']): ?>
                            <span class="badge badge-warning" style="margin-left: 8px; font-size: 0.7rem;">Trailer</span>
                            <?php endif; ?>
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 0;">
                                /stories/<?= esc($story['slug']) ?>
                            </p>
                        </div>
                    </td>
                    <td>
                        <span style="text-transform: capitalize;"><?= esc($story['category'] ?? 'Uncategorized') ?></span>
                    </td>
                    <td>
                        <span class="status-badge status-<?= $story['status'] ?>">
                            <?= ucfirst($story['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?= $story['published_at'] ? date('M d, Y', strtotime($story['published_at'])) : '-' ?>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="/admin/stories/edit/<?= $story['id'] ?>" class="btn-admin btn-admin-secondary btn-admin-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/admin/stories/delete/<?= $story['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this story?');">
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
        <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
        <h4 class="empty-state-title">No stories found</h4>
        <p class="empty-state-text">Start creating engaging content for your users.</p>
        <a href="/admin/stories/new" class="btn-admin btn-admin-primary">Create Story</a>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

