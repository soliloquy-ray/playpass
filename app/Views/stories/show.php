<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="story-detail-page">
    <!-- Hero Banner Section -->
    <div class="story-hero">
        <div class="story-hero-image" style="position: relative; width: 100%; aspect-ratio: 16/9; background: #222; overflow: hidden;">
            <img src="<?= asset_url($story['image'] ?? 'assets/images/placeholder.jpg') ?>" 
                 alt="<?= esc($story['title']) ?>" 
                 style="width: 100%; height: 100%; object-fit: cover;">
            
            <!-- Overlay Content -->
            <div class="story-hero-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                <?php if(isset($story['is_trailer']) && $story['is_trailer']): ?>
                    <span style="display: inline-block; background: #fbbf24; color: #000; padding: 4px 8px; font-size: 0.7rem; font-weight: 800; border-radius: 2px; text-transform: uppercase; margin-bottom: 10px;">TRAILER</span>
                <?php endif; ?>
                <h1 class="story-hero-title" style="color: #fff; font-size: 2rem; font-weight: 800; margin: 10px 0; line-height: 1.2;">
                    <?= esc($story['title']) ?>
                </h1>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="container" style="max-width: 900px; padding: 30px 15px;">
        <!-- Story Meta -->
        <div class="story-meta-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #333;">
            <span class="story-category-badge" style="font-weight: 700; text-transform: uppercase; color: <?= 
                $story['category'] === 'trailer' ? '#fbbf24' : 
                ($story['category'] === 'promo' ? '#ff0055' : 
                ($story['category'] === 'event' ? '#c084fc' : '#60a5fa')) 
            ?>;"><?= ucfirst($story['category'] ?? 'story') ?></span>
            <span class="story-time" style="color: #888; font-size: 0.9rem;">
                <?= !empty($story['published_at']) ? date('h:i A', strtotime($story['published_at'])) : date('h:i A') ?>
            </span>
        </div>

        <!-- Story Content -->
        <div class="story-content-body" style="color: #fff; line-height: 1.8; font-size: 1rem;">
            <?php if (!empty($story['content'])): ?>
                <div style="white-space: pre-wrap;"><?= esc($story['content']) ?></div>
            <?php else: ?>
                <p><?= esc($story['excerpt'] ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.') ?></p>
            <?php endif; ?>
        </div>

        <!-- Related Stories Section -->
        <?php if (!empty($relatedStories)): ?>
            <div class="related-stories-section" style="margin-top: 60px;">
                <h2 style="color: #fff; font-size: 1.8rem; font-weight: 800; margin-bottom: 25px;">Related Stories</h2>
                
                <div class="related-stories-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <?php foreach ($relatedStories as $relatedStory): ?>
                        <a href="<?= site_url('app/stories/' . esc($relatedStory['slug'] ?? $relatedStory['id'])) ?>" 
                           class="related-story-card" 
                           style="text-decoration: none; color: inherit; display: block;">
                            <div style="background: #121212; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s;">
                                <div style="position: relative; aspect-ratio: 16/9; background: #222;">
                                    <img src="<?= asset_url($relatedStory['image'] ?? 'assets/images/placeholder.jpg') ?>" 
                                         alt="<?= esc($relatedStory['title'] ?? 'Story') ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    <?php if(isset($relatedStory['is_trailer']) && $relatedStory['is_trailer']): ?>
                                        <span style="position: absolute; bottom: 8px; left: 8px; background: #fbbf24; color: #000; padding: 3px 6px; font-size: 0.65rem; font-weight: 800; border-radius: 2px; text-transform: uppercase;">TRAILER</span>
                                    <?php endif; ?>
                                </div>
                                <div style="padding: 12px 10px;">
                                    <p style="color: #fff; font-size: 0.9rem; font-weight: 600; margin: 0 0 6px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= esc($relatedStory['title'] ?? 'Story Title') ?>
                                    </p>
                                    <span style="color: #888; font-size: 0.75rem;">
                                        <?= !empty($relatedStory['published_at']) ? date('h:i A', strtotime($relatedStory['published_at'])) : date('h:i A') ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .story-detail-page {
        background: #0a0a0a;
        min-height: 100vh;
    }

    .story-hero {
        width: 100%;
    }

    .story-hero-image {
        position: relative;
    }

    /* Mobile: Stack related stories vertically */
    @media (max-width: 768px) {
        .related-stories-grid {
            grid-template-columns: 1fr !important;
        }
        
        .story-hero-title {
            font-size: 1.5rem !important;
        }
    }

    /* Desktop: Horizontal scroll for related stories */
    @media (min-width: 769px) {
        .related-stories-grid {
            display: flex !important;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
        }
        
        .related-stories-grid > a {
            flex: 0 0 280px;
        }
        
        .related-stories-grid::-webkit-scrollbar {
            height: 6px;
        }
        
        .related-stories-grid::-webkit-scrollbar-track {
            background: #1a1a1a;
            border-radius: 3px;
        }
        
        .related-stories-grid::-webkit-scrollbar-thumb {
            background: #d8369f;
            border-radius: 3px;
        }
    }

    .related-story-card:hover {
        transform: translateY(-4px);
    }

    .related-story-card > div {
        transition: transform 0.2s;
    }
</style>

<?= $this->endSection() ?>
