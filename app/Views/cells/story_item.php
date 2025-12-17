<a href="<?= site_url('app/stories/' . esc($story['slug'] ?? $story['id'])) ?>" class="story-card-link">
    <div class="story-card fade-in">
        <div class="story-image-wrapper">
            <img src="<?= asset_url($story['image'] ?? 'assets/images/placeholder.jpg') ?>" alt="<?= esc($story['title'] ?? 'Story') ?>" loading="lazy">
            
            <?php if(isset($story['is_trailer']) && $story['is_trailer']): ?>
                <span class="trailer-badge">TRAILER</span>
            <?php endif; ?>
        </div>

        <div class="story-content">
            <div class="story-meta">
                <span class="story-category <?= $badgeColor ?>"><?= ucfirst($story['category'] ?? 'story') ?></span>
                <span class="story-time"><?= !empty($story['published_at']) ? date('h:i A', strtotime($story['published_at'])) : date('h:i A') ?></span>
            </div>
            
            <p class="story-excerpt">
                <?= !empty($story['excerpt']) ? esc($story['excerpt']) : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' ?>
            </p>
        </div>
    </div>
</a>