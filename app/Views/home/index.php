<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Carousel -->
<?php if (!empty($carouselSlides)): ?>
<div class="hero-carousel">
    <?php foreach ($carouselSlides as $index => $slide): ?>
    <div class="carousel-slide <?= $index === 0 ? 'active' : '' ?>">
        <div class="carousel-slide-inner">
            <div class="carousel-content">
                <h1><?= esc($slide['title']) ?></h1>
                <?php if (!empty($slide['subtitle'])): ?>
                <p><?= esc($slide['subtitle']) ?></p>
                <?php endif; ?>
                <?php if (!empty($slide['cta_text']) && !empty($slide['cta_link'])): ?>
                <a href="<?= esc($slide['cta_link']) ?>" class="btn btn-primary"><?= esc($slide['cta_text']) ?></a>
                <?php endif; ?>
            </div>
            <div class="carousel-image">
                <?php if (!empty($slide['image_url'])): ?>
                    <img src="<?= asset_url($slide['image_url']) ?>" alt="<?= esc($slide['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, <?= esc($slide['bg_gradient_start'] ?? '#d8369f') ?> 0%, <?= esc($slide['bg_gradient_end'] ?? '#051429') ?> 100%);"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Carousel Controls -->
    <?php if (count($carouselSlides) > 1): ?>
    <div class="carousel-controls">
        <?php foreach ($carouselSlides as $index => $slide): ?>
        <button class="carousel-dot <?= $index === 0 ? 'active' : '' ?>"></button>
        <?php endforeach; ?>
    </div>

    <!-- Carousel Arrows -->
    <button class="carousel-arrow prev">❮</button>
    <button class="carousel-arrow next">❯</button>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="container" style="padding: 0 15px; max-width: 1200px; margin: 0 auto;">

    <!-- New Products Section -->
    <?php
    // Prepare products data for the cell
    $productsForCell = [];
    if (!empty($newProducts) && is_array($newProducts)) {
        $productsForCell = array_map(function($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['thumbnail_url'] ?? base_url('assets/images/placeholder.jpg'),
                'date' => date('M j, Y', strtotime($p['created_at'])),
                'brand_id' => $p['brand_id'] ?? null,
                'brand_name' => $p['brand_name'] ?? null,
                'brand_logo' => $p['brand_logo'] ?? null
            ];
        }, $newProducts);
    }
    ?>

    <?= view_cell('App\Cells\NewProductsCell::render', [
        'title' => 'NEW',
        'subtitle' => 'Fresh products added this week',
        'products' => $productsForCell
    ]) ?>

    <!-- Featured Products Section -->
    <?php
    // Prepare featured products data for the cell
    $featuredProductsForCell = [];
    if (!empty($featuredProducts) && is_array($featuredProducts)) {
        $featuredProductsForCell = array_map(function($p) {
            return [
                'id' => $p['id'],
                'brand_id' => $p['brand_id'] ?? null,
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['thumbnail_url'] ?? base_url('assets/images/placeholder.jpg'),
                'bg_color' => $p['bg_color'] ?? '#1a1a1a',
                'badge' => 'Featured'
            ];
        }, $featuredProducts);
    }
    
    $featuredProductsData = [
        'title' => 'Featured Products',
        'subtitle' => 'Handpicked selections just for you',
        'products' => $featuredProductsForCell
    ];
    ?>
    <?= view_cell('App\Cells\FeaturedProductsCell::render', $featuredProductsData) ?>


    <!-- Promos Section -->
    <?php
    $promosData = [
        'title' => 'PROMOS',
        'promos' => array_map(function($promo) {
            return [
                'title' => $promo['name'],
                'image' => !empty($promo['icon']) ? asset_url($promo['icon']) : base_url('assets/icons/percent.png')
            ];
        }, $promos ?? [])
    ];
    
    // If no promos in DB, show default icons (fallback)
    if (empty($promosData['promos'])) {
        $promosData['promos'] = [
            ['title' => 'Playpass Points', 'image' => base_url('assets/icons/crown.png')], 
            ['title' => 'Discount Vouchers', 'image' => base_url('assets/icons/ticket.png')],
            ['title' => 'Brand Packs', 'image' => base_url('assets/icons/box.png')],
            ['title' => 'Flash Deals', 'image' => base_url('assets/icons/flash.png')],
            ['title' => 'Buy More Save More', 'image' => base_url('assets/icons/basket.png')],
            ['title' => 'New Brand Promo', 'image' => base_url('assets/icons/percent.png')],
            ['title' => 'Gift & Earn', 'image' => base_url('assets/icons/gift.png')],
            ['title' => 'Streak Rewards', 'image' => base_url('assets/icons/medal.png')],
            ['title' => 'Mini-Games', 'image' => base_url('assets/icons/gamepad.png')],
            ['title' => 'Refer a Friend', 'image' => base_url('assets/icons/users.png')],
            ['title' => 'Birthday Bonus', 'image' => base_url('assets/icons/cake.png')],
            ['title' => 'Seasonal Promo', 'image' => base_url('assets/icons/calendar.png')],
        ];
    }
    ?>
    <?= view_cell('App\Cells\PromosCell::render', $promosData) ?>

    <!-- Stories Section -->
    <?php
    $storiesData = [
        'title' => 'STORIES',
        'subtitle' => null,
        'stories' => array_map(function($s) {
            return [
                'title' => $s['title'],
                'image' => $s['image'] ?? 'https://placehold.co/600x338/1a1a1a/FFF?text=Story',
                'time' => $s['published_at'] ? date('g:i A', strtotime($s['published_at'])) : '',
                'is_trailer' => (bool) ($s['is_trailer'] ?? false)
            ];
        }, $latestStories ?? [])
    ];
    ?>
    <?= view_cell('App\Cells\StoriesCell::render', $storiesData) ?>

    <!-- How It Works Section -->
    <?= view_cell('App\Cells\HowItWorksCell::render') ?>

    <!-- Customer Support Section -->
    <?= view_cell('App\Cells\CustomerSupportCell::render') ?>

</div>

<?= $this->endSection() ?>
