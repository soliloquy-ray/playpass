<div class="story-card fade-in">
    <div class="story-image-wrapper">
        <img src="<?= $story['image'] ?>" alt="<?= $story['title'] ?>" loading="lazy">
        
        <?php if($story['category'] === 'trailer'): ?>
            <span class="trailer-badge">TRAILER</span>
        <?php endif; ?>
    </div>

    <div class="story-content">
        <div class="story-meta">
            <span class="story-category <?= $badgeColor ?>"><?= ucfirst($story['category']) ?></span>
            <span class="story-time"><?= date('h:i A', strtotime($story['published_at'])) ?></span>
        </div>

        <h3 class="story-title"><?= $story['title'] ?></h3>
        
        <p class="story-excerpt">
            <?= $story['excerpt'] ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' ?>
        </p>
    </div>
</div>