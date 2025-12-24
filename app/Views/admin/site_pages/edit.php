<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="margin-bottom: 24px;">
    <a href="<?= site_url('admin/site-pages') ?>" style="color: var(--text-muted); text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Back to Site Pages
    </a>
</div>

<!-- Validation Errors -->
<?php if (session()->has('errors')): ?>
<div class="alert alert-error" style="margin-bottom: 24px;">
    <ul style="margin: 0; padding-left: 20px;">
        <?php foreach (session('errors') as $error): ?>
        <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= site_url('admin/site-pages/update/' . $page['slug']) ?>" method="POST" class="admin-form" style="max-width: 1200px;">
    <?= csrf_field() ?>
    
    <div class="admin-grid admin-grid-equal">
        <!-- Main Content -->
        <div>
            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Page Content</h3>
                
                <div class="form-group">
                    <label class="form-label">Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-input" value="<?= esc($page['title'] ?? old('title')) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Slug (read-only)</label>
                    <input type="text" class="form-input" value="<?= esc($page['slug']) ?>" disabled style="background: var(--card-bg); color: var(--text-muted);">
                    <p class="form-hint">The URL path for this page: /<?= esc($page['slug']) ?></p>
                </div>

                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-textarea" style="min-height: 500px; font-family: monospace; font-size: 0.9rem;"><?= $page['content'] ?? old('content') ?></textarea>
                    <p class="form-hint">You can use HTML for formatting. Basic tags like &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt; are supported.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">SEO Settings</h3>
                
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-input" value="<?= esc($page['meta_title'] ?? old('meta_title')) ?>" placeholder="SEO title for search engines">
                    <p class="form-hint">Recommended: 50-60 characters</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-textarea" style="min-height: 100px;" placeholder="Brief description for search engine results"><?= esc($page['meta_description'] ?? old('meta_description')) ?></textarea>
                    <p class="form-hint">Recommended: 150-160 characters</p>
                </div>
            </div>

            <div class="admin-card">
                <h3 class="admin-card-title" style="margin-bottom: 24px;">Settings</h3>

                <div class="form-group">
                    <label class="form-checkbox-group">
                        <input type="checkbox" name="is_active" class="form-checkbox" value="1" <?= ($page['is_active'] ?? true) ? 'checked' : '' ?>>
                        <span>Page is Active</span>
                    </label>
                    <p class="form-hint">Inactive pages will return a 404 error</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div style="display: flex; gap: 16px; margin-top: 24px;">
        <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
        <a href="<?= site_url('admin/site-pages') ?>" class="btn-admin btn-admin-secondary">Cancel</a>
        <a href="<?= site_url($page['slug']) ?>" target="_blank" class="btn-admin btn-admin-secondary">
            <i class="fas fa-external-link-alt"></i> Preview Page
        </a>
    </div>
</form>

<?= $this->endSection() ?>
