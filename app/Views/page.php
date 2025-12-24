<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 900px; padding: 40px 20px;">
    
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 2rem; margin-bottom: 8px; color: #fff;"><?= esc($page['title']) ?></h1>
        <?php if (!empty($page['updated_at'])): ?>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            Last updated: <?= date('F d, Y', strtotime($page['updated_at'])) ?>
        </p>
        <?php endif; ?>
    </div>

    <!-- Page Content -->
    <div class="page-content" style="
        background: rgba(255,255,255,0.03);
        border-radius: 16px;
        padding: 32px;
        line-height: 1.8;
        color: #e0e0e0;
    ">
        <?= $page['content'] ?>
    </div>

    <!-- Back Link -->
    <div style="margin-top: 32px; text-align: center;">
        <a href="<?= site_url('app') ?>" style="color: var(--primary); text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
    </div>

</div>

<style>
.page-content h2 {
    font-size: 1.5rem;
    margin-top: 32px;
    margin-bottom: 16px;
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 8px;
}

.page-content h3 {
    font-size: 1.2rem;
    margin-top: 24px;
    margin-bottom: 12px;
    color: #fff;
}

.page-content p {
    margin-bottom: 16px;
}

.page-content ul, .page-content ol {
    margin-bottom: 16px;
    padding-left: 24px;
}

.page-content li {
    margin-bottom: 8px;
}

.page-content a {
    color: var(--primary);
    text-decoration: none;
}

.page-content a:hover {
    text-decoration: underline;
}

.page-content strong {
    color: #fff;
}

/* FAQ specific styles */
.faq-item {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.faq-item:last-child {
    border-bottom: none;
}

.faq-question {
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
}

.faq-answer {
    color: #a0a0a0;
}
</style>

<?= $this->endSection() ?>
