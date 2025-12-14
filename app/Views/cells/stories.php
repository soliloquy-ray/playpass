<section class="stories-section" style="margin-bottom: 40px;">
    <h2 style="color: #3b82f6; margin-bottom: 20px; font-weight: bold;"><?= $title ?></h2>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
        <?php foreach ($stories as $story): ?>
            <div class="story-card" style="background: #1a1a1a; border-radius: 8px; overflow: hidden;">
                <div style="position: relative; aspect-ratio: 16/9;">
                    <img src="<?= $story['image'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php if(isset($story['is_trailer'])): ?>
                        <span style="position: absolute; bottom: 10px; left: 10px; background: #fbbf24; color: #000; padding: 2px 8px; font-size: 0.7rem; font-weight: bold; border-radius: 4px;">TRAILER</span>
                    <?php endif; ?>
                </div>
                
                <div style="padding: 15px;">
                    <h3 style="color: #fff; font-size: 0.95rem; line-height: 1.4; margin-bottom: 8px;">
                        <?= $story['title'] ?>
                    </h3>
                    <p style="color: #666; font-size: 0.8rem;">
                        <?= $story['time'] ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="/app/stories" style="display: block; width: 100%; background-color: #ff0055; color: white; text-align: center; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold;">
        Explore More
    </a>
</section>