<section class="stories-section" style="margin-bottom: 40px;">
    <h2 style="color: #fff; margin-bottom: 20px; font-weight: 800; font-size: 1.8rem; text-transform: uppercase;"><?= $title ?></h2>

    <div class="stories-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
        <?php if (!empty($stories)): ?>
            <?php foreach ($stories as $story): ?>
                <a href="/app/stories/<?= esc($story['slug'] ?? $story['id'] ?? '#') ?>" 
                   class="story-card-link" 
                   style="text-decoration: none; color: inherit; display: block;">
                    <div class="story-card" style="background: #121212; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s;">
                        <div style="position: relative; aspect-ratio: 16/9; background: #222;">
                            <img src="<?= esc($story['image']) ?>" alt="<?= esc($story['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            <?php if(isset($story['is_trailer']) && $story['is_trailer']): ?>
                                <span style="position: absolute; bottom: 8px; left: 8px; background: #fbbf24; color: #000; padding: 3px 6px; font-size: 0.65rem; font-weight: 800; border-radius: 2px; text-transform: uppercase;">TRAILER</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="padding: 12px 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 0.75rem;">
                                <span class="story-category" style="font-weight: 700; text-transform: uppercase; color: <?= 
                                    $story['category'] === 'trailer' ? '#fbbf24' : 
                                    ($story['category'] === 'promo' ? '#ff0055' : 
                                    ($story['category'] === 'event' ? '#c084fc' : '#60a5fa')) 
                                ?>;"><?= ucfirst($story['category'] ?? 'story') ?></span>
                                <span style="color: #888;"><?= esc($story['time'] ?? '') ?></span>
                            </div>
                            
                            <?php if (!empty($story['excerpt'])): ?>
                                <p style="color: #888; font-size: 0.8rem; margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= esc($story['excerpt']) ?>
                                </p>
                            <?php else: ?>
                                <p style="color: #888; font-size: 0.8rem; margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #888; text-align: center; grid-column: 1 / -1; padding: 20px;">No stories available.</p>
        <?php endif; ?>
    </div>

    <a href="/stories" style="display: block; width: 100%; background-color: #ff0055; color: white; text-align: center; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold;">
        Explore More
    </a>
</section>

<style>
    .story-card-link:hover .story-card {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    
    @media (min-width: 768px) {
        .stories-grid {
            grid-template-columns: repeat(4, 1fr) !important;
        }
    }
    
    @media (max-width: 767px) {
        .stories-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
</style>