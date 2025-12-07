<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Carousel -->
<div class="hero-carousel">
    <!-- Slide 1 -->
    <div class="carousel-slide active">
        <div class="carousel-content">
            <h1>To the Moon 🚀</h1>
            <p>Now Streaming via Playpass</p>
            <a href="/products" class="btn btn-primary">Explore Now</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #d8369f 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-slide">
        <div class="carousel-content">
            <h1>Gaming Bundles 🎮</h1>
            <p>Exclusive packages for hardcore gamers</p>
            <a href="/products?category=bundles" class="btn btn-primary">Shop Bundles</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #4caf50 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-slide">
        <div class="carousel-content">
            <h1>Get 50% Off 🎉</h1>
            <p>First-time top-up promotion</p>
            <a href="/login" class="btn btn-primary">Get Started</a>
        </div>
        <div class="carousel-image">
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #ff9800 0%, #051429 100%);"></div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <div class="carousel-controls">
        <button class="carousel-dot active"></button>
        <button class="carousel-dot"></button>
        <button class="carousel-dot"></button>
    </div>

    <!-- Carousel Arrows -->
    <button class="carousel-arrow prev">❮</button>
    <button class="carousel-arrow next">❯</button>
</div>

<div class="container" style="padding: 0 15px; max-width: 1200px; margin: 0 auto;">

    <!-- Categories Section -->
    <section style="margin: 60px 0;">
        <div class="section-header">
            <h2>Browse by Category</h2>
        </div>
        
        <div class="products-grid">
            <?= view_cell('App\Cells\CategoryBadgeCell::render', [
                'title' => 'Games',
                'icon' => '🎮',
                'count' => 245,
                'url' => '/products?category=games'
            ]) ?>
            <?= view_cell('App\Cells\CategoryBadgeCell::render', [
                'title' => 'Streaming',
                'icon' => '📺',
                'count' => 128,
                'url' => '/products?category=streaming'
            ]) ?>
            <?= view_cell('App\Cells\CategoryBadgeCell::render', [
                'title' => 'Subscriptions',
                'icon' => '⭐',
                'count' => 89,
                'url' => '/products?category=subscriptions'
            ]) ?>
            <?= view_cell('App\Cells\CategoryBadgeCell::render', [
                'title' => 'Bundles',
                'icon' => '📦',
                'count' => 56,
                'url' => '/products?category=bundles'
            ]) ?>
        </div>
    </section>

    <!-- Featured Products Section -->
    <?php
    $featuredProductsData = [
        'title' => 'Featured Products',
        'subtitle' => 'Handpicked selections just for you',
        'products' => [
            [
                'id' => 1,
                'name' => 'Premium Gaming Bundle',
                'price' => 49.99,
                'image' => '/assets/images/placeholder.jpg',
                'rating' => 4.8,
                'reviews' => 234,
                'badge' => 'Featured'
            ],
            [
                'id' => 2,
                'name' => 'Streaming Essentials',
                'price' => 29.99,
                'image' => '/assets/images/placeholder.jpg',
                'rating' => 4.6,
                'reviews' => 156,
                'badge' => 'Popular'
            ],
            [
                'id' => 3,
                'name' => 'Ultimate Subscription Pack',
                'price' => 79.99,
                'image' => '/assets/images/placeholder.jpg',
                'rating' => 4.9,
                'reviews' => 512,
                'badge' => 'Best Seller'
            ],
            [
                'id' => 4,
                'name' => 'Creator Pro Bundle',
                'price' => 59.99,
                'image' => '/assets/images/placeholder.jpg',
                'rating' => 4.7,
                'reviews' => 289,
                'badge' => 'New'
            ],
        ]
    ];
    ?>
    <?= view_cell('App\Cells\FeaturedProductsCell::render', $featuredProductsData) ?>

    <!-- New Products Section -->
    <?php
    $newProductsData = [
        'title' => 'New Arrivals',
        'subtitle' => 'Fresh products added this week',
        'products' => [
            [
                'id' => 5,
                'name' => 'Latest Game Release',
                'price' => 34.99,
                'image' => '/assets/images/placeholder.jpg',
                'date' => 'Dec 1, 2025'
            ],
            [
                'id' => 6,
                'name' => 'Streaming Bundle',
                'price' => 24.99,
                'image' => '/assets/images/placeholder.jpg',
                'date' => 'Dec 2, 2025'
            ],
            [
                'id' => 7,
                'name' => 'Entertainment Pack',
                'price' => 44.99,
                'image' => '/assets/images/placeholder.jpg',
                'date' => 'Dec 3, 2025'
            ],
            [
                'id' => 8,
                'name' => 'Premium Plus',
                'price' => 19.99,
                'image' => '/assets/images/placeholder.jpg',
                'date' => 'Dec 4, 2025'
            ],
        ]
    ];
    ?>
    <?= view_cell('App\Cells\NewProductsCell::render', $newProductsData) ?>

    <!-- Promos Section -->
    <?php
    $promosData = [
        'bannerTitle' => 'Special Holiday Offer',
        'bannerDescription' => 'Celebrate with us! Grab your favorite products at unbeatable prices',
        'bannerDiscount' => '50% OFF',
        'bannerExpiry' => 'Ends Dec 15',
        'bannerCTA' => 'Shop Now',
        'bannerImage' => '/assets/images/placeholder.jpg',
        'promos' => [
            [
                'title' => 'Gaming Collection',
                'discount' => '30% OFF',
                'code' => 'GAME30',
                'image' => '/assets/images/placeholder.jpg',
                'minSpend' => 50
            ],
            [
                'title' => 'Streaming Deals',
                'discount' => '25% OFF',
                'code' => 'STREAM25',
                'image' => '/assets/images/placeholder.jpg',
                'minSpend' => 30
            ],
            [
                'title' => 'Bundle Specials',
                'discount' => '40% OFF',
                'code' => 'BUNDLE40',
                'image' => '/assets/images/placeholder.jpg',
                'minSpend' => 75
            ],
            [
                'title' => 'Subscription Plus',
                'discount' => '35% OFF',
                'code' => 'SUBS35',
                'image' => '/assets/images/placeholder.jpg',
                'minSpend' => 60
            ],
        ]
    ];
    ?>
    <?= view_cell('App\Cells\PromosCell::render', $promosData) ?>

    <!-- How It Works Section -->
    <?= view_cell('App\Cells\HowItWorksCell::render') ?>

    <!-- Stories Section -->
    <?php
    $storiesData = [
        'title' => 'Customer Stories',
        'subtitle' => 'What gamers and streamers are saying about Playpass',
        'testimonials' => [
            [
                'name' => 'Alex Chen',
                'role' => 'Professional Streamer',
                'avatar' => '/assets/images/placeholder.jpg',
                'quote' => 'Playpass made it so easy to get everything I need for my streaming setup. Best platform ever!',
                'rating' => 5,
                'badge' => 'Verified Purchase',
                'date' => 'Dec 5, 2025'
            ],
            [
                'name' => 'Jordan Martinez',
                'role' => 'Gaming Enthusiast',
                'avatar' => '/assets/images/placeholder.jpg',
                'quote' => 'The variety of products and instant delivery is unmatched. Highly recommend!',
                'rating' => 5,
                'badge' => 'Verified Purchase',
                'date' => 'Dec 4, 2025'
            ],
            [
                'name' => 'Sam Taylor',
                'role' => 'Content Creator',
                'avatar' => '/assets/images/placeholder.jpg',
                'quote' => 'Great prices, fast support, and amazing customer service. Keep it up!',
                'rating' => 4,
                'badge' => 'Verified Purchase',
                'date' => 'Dec 3, 2025'
            ],
            [
                'name' => 'Riley Johnson',
                'role' => 'Casual Gamer',
                'avatar' => '/assets/images/placeholder.jpg',
                'quote' => 'Finally found a reliable place to buy gaming bundles. Will definitely come back.',
                'rating' => 5,
                'badge' => 'Verified Purchase',
                'date' => 'Dec 2, 2025'
            ],
        ]
    ];
    ?>
    <?= view_cell('App\Cells\StoriesCell::render', $storiesData) ?>

    <!-- Customer Support Section -->
    <?= view_cell('App\Cells\CustomerSupportCell::render') ?>

</div>

<?= $this->endSection() ?>
