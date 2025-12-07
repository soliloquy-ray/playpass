<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="max-width: 900px; margin: 0 auto; padding: 40px 15px;">
    <!-- Article Header -->
    <div style="margin-bottom: 40px;">
        <p style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0 0 10px 0;">
            <?= isset($article['category']) ? esc($article['category']) : 'Gaming' ?>
        </p>
        <h1 style="margin-bottom: 10px;"><?= esc($article['title']) ?></h1>
        <div style="display: flex; align-items: center; gap: 20px; color: var(--text-muted); font-size: 0.9rem; flex-wrap: wrap;">
            <span>By <strong><?= isset($article['author']) ? esc($article['author']) : 'Playpass Team' ?></strong></span>
            <span>•</span>
            <span><?= isset($article['created_at']) ? date('M d, Y', strtotime($article['created_at'])) : date('M d, Y') ?></span>
            <span>•</span>
            <span>5 min read</span>
        </div>
    </div>

    <!-- Featured Image -->
    <div style="width: 100%; aspect-ratio: 16/9; background: linear-gradient(135deg, var(--primary) 0%, #4caf50 100%); border-radius: 12px; margin-bottom: 40px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
        📸
    </div>

    <!-- Article Content -->
    <div style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 60px;">
        <p>
            <?= isset($article['content']) ? $article['content'] : 'Article content goes here. This would be the full article body with detailed information about the topic.' ?>
        </p>
    </div>

    <!-- Author Bio -->
    <div style="background-color: var(--card-bg); padding: 30px; border-radius: 12px; margin-bottom: 40px; display: flex; gap: 20px;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, #4caf50 100%); flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 700;">
            PT
        </div>
        <div>
            <h4 style="margin-top: 0;">Playpass Team</h4>
            <p style="color: var(--text-muted); margin: 5px 0 0 0;">
                Our dedicated team brings you the latest news, tips, and stories from the digital entertainment world. 
                Follow us for exclusive insights and updates.
            </p>
            <div style="display: flex; gap: 12px; margin-top: 12px;">
                <a href="#" style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">Follow</a>
                <a href="#" style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">Share</a>
            </div>
        </div>
    </div>

    <!-- Related Articles -->
    <h2 style="margin-bottom: 20px;">Related Stories</h2>
    <div class="grid grid-2" style="margin-bottom: 40px;">
        <div class="article-card">
            <div class="article-content">
                <div class="article-date">Dec 5, 2025</div>
                <h3 class="article-title">
                    <a href="#">10 Must-Play Games This Winter</a>
                </h3>
                <p class="article-excerpt">
                    Check out our curated list of the best games to play during the holiday season...
                </p>
                <a href="#" class="article-link">Read Story →</a>
            </div>
        </div>

        <div class="article-card">
            <div class="article-content">
                <div class="article-date">Dec 3, 2025</div>
                <h3 class="article-title">
                    <a href="#">Streaming Services Comparison 2025</a>
                </h3>
                <p class="article-excerpt">
                    We compare the latest streaming platforms to help you choose the right one...
                </p>
                <a href="#" class="article-link">Read Story →</a>
            </div>
        </div>
    </div>

    <!-- Share & Comments CTA -->
    <div style="background: linear-gradient(135deg, var(--secondary) 0%, #0d2b52 100%); padding: 40px; border-radius: 12px; text-align: center;">
        <h3 style="margin-top: 0;">Share This Story</h3>
        <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin: 20px 0;">
            <button class="btn btn-secondary">📘 Facebook</button>
            <button class="btn btn-secondary">𝕏 Twitter</button>
            <button class="btn btn-secondary">💬 Telegram</button>
            <button class="btn btn-secondary">🔗 Copy Link</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
