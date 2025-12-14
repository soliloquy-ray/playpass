<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1000px; padding: 30px 15px;">
    
    <!-- NEW Products Section -->
    <?php
    $newProductsData = [
        'title' => 'New Arrivals',
        'subtitle' => 'Fresh products added this week',
        'products' => array_map(function($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['thumbnail_url'] ?? '/assets/images/placeholder.jpg',
                'date' => date('M j, Y', strtotime($p['created_at']))
            ];
        }, $newProducts ?? [])
    ];
    ?>
    <?= view_cell('App\Cells\NewProductsCell::render', ['title' => 'NEW', 'data' => $newProductsData]) ?>

    <!-- PRODUCTS Section with Filter -->
    <div style="margin-top: 40px; margin-bottom: 20px;">
        <h2 style="color: #3b82f6; margin-bottom: 15px;">PRODUCTS</h2>
        
        <div class="filter-bar">
            <span style="color: #fff; font-weight: bold; margin-right: 10px;">Filter</span>
            
            <div class="filter-dropdown">
                <button class="filter-button" id="filter-duration-btn">
                    <span>Duration</span>
                    <span class="filter-caret">◀</span>
                </button>
                <div class="filter-menu" id="filter-duration-menu">
                    <button class="filter-option" data-value="">All</button>
                    <button class="filter-option" data-value="3 Day">3 Days</button>
                    <button class="filter-option" data-value="7 Day">7 Days</button>
                    <button class="filter-option" data-value="30 Day">30 Days</button>
                </div>
            </div>

            <div class="filter-dropdown">
                <button class="filter-button" id="filter-price-btn">
                    <span>Price</span>
                    <span class="filter-caret">◀</span>
                </button>
                <div class="filter-menu" id="filter-price-menu">
                    <button class="filter-option" data-value="">All</button>
                    <button class="filter-option" data-value="low">Under ₱50</button>
                    <button class="filter-option" data-value="mid">₱50 - ₱100</button>
                    <button class="filter-option" data-value="high">Above ₱100</button>
                </div>
            </div>

            <div class="filter-dropdown">
                <button class="filter-button" id="filter-brand-btn">
                    <span>Brand</span>
                    <span class="filter-caret">◀</span>
                </button>
                <div class="filter-menu" id="filter-brand-menu">
                    <button class="filter-option" data-value="">All</button>
                    <?php foreach($brands as $brand): ?>
                        <button class="filter-option" data-value="<?= $brand['id'] ?>"><?= esc($brand['name']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="products-grid" class="products-grid">
    </div>

    <div id="loading-spinner" style="text-align: center; padding: 40px; display: none;">
        <div class="spinner"></div>
    </div>
    
    <div id="end-message" style="text-align: center; color: #666; display: none; padding: 20px;">
        End of results.
    </div>

    <!-- Promos Section -->
    <?php
    // Debug: Verify promos variable exists
    // Uncomment to debug: var_dump('$promos exists:', isset($promos), 'is_array:', is_array($promos ?? null), 'count:', count($promos ?? []));
    
    // Map database promos to view format
    $mappedPromos = [];
    if (isset($promos) && is_array($promos) && count($promos) > 0) {
        $mappedPromos = array_map(function($promo) {
            return [
                'title' => $promo['name'] ?? 'Promo',
                'image' => !empty($promo['icon']) ? $promo['icon'] : '/assets/icons/percent.png'
            ];
        }, $promos);
    }
    
    // Fallback promos if no database promos
    $fallbackPromos = [
        ['title' => 'Playpass Points', 'image' => '/assets/icons/crown.png'], 
        ['title' => 'Discount Vouchers', 'image' => '/assets/icons/ticket.png'],
        ['title' => 'Brand Packs', 'image' => '/assets/icons/box.png'],
        ['title' => 'Flash Deals', 'image' => '/assets/icons/flash.png'],
        ['title' => 'Buy More Save More', 'image' => '/assets/icons/basket.png'],
        ['title' => 'New Brand Promo', 'image' => '/assets/icons/percent.png'],
        ['title' => 'Gift & Earn', 'image' => '/assets/icons/gift.png'],
        ['title' => 'Streak Rewards', 'image' => '/assets/icons/medal.png'],
        ['title' => 'Mini-Games', 'image' => '/assets/icons/gamepad.png'],
        ['title' => 'Refer a Friend', 'image' => '/assets/icons/users.png'],
        ['title' => 'Birthday Bonus', 'image' => '/assets/icons/cake.png'],
        ['title' => 'Seasonal Promo', 'image' => '/assets/icons/calendar.png'],
    ];
    
    // Use mapped promos if available, otherwise use fallback
    // Ensure we always have promos to display
    if (count($mappedPromos) > 0) {
        $finalPromos = $mappedPromos;
    } else {
        $finalPromos = $fallbackPromos;
    }
    
    // Safety check: if finalPromos is somehow empty, use fallback
    if (empty($finalPromos) || !is_array($finalPromos) || count($finalPromos) == 0) {
        $finalPromos = $fallbackPromos;
    }
    
    $promosData = [
        'title' => 'PROMOS',
        'promos' => $finalPromos
    ];
    ?>
    <?= view_cell('App\Cells\PromosCell::render', $promosData) ?>

    <!-- Customer Support Section -->
    <?= view_cell('App\Cells\CustomerSupportCell::render') ?>

</div>

<style>
    /* Filter Styles */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-dropdown {
        position: relative;
        display: inline-block;
    }

    .filter-button {
        background-color: #1a1a1a;
        color: #e0e0e0;
        border: 1px solid #ff0055;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        outline: none;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 120px;
        justify-content: space-between;
    }

    .filter-button:hover {
        background-color: #252525;
    }

    .filter-caret {
        font-size: 0.7rem;
        color: #999;
        transform: rotate(-90deg);
        transition: transform 0.2s;
    }

    .filter-dropdown.active .filter-caret {
        transform: rotate(90deg);
    }

    .filter-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #1a1a1a;
        border: 1px solid #ff0055;
        border-radius: 4px;
        margin-top: 4px;
        min-width: 100%;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .filter-dropdown.active .filter-menu {
        display: block;
    }

    .filter-option {
        display: block;
        width: 100%;
        padding: 10px 16px;
        background: none;
        border: none;
        color: #e0e0e0;
        text-align: left;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .filter-option:hover {
        background-color: #252525;
    }

    .filter-option.active {
        background-color: #ff0055;
        color: white;
    }

    /* Grid Layout: 3 Columns per screenshot */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    @media (max-width: 768px) {
        .products-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<script>
    let offset = 0;
    let isLoading = false;
    let hasMore = true;
    
    // Selectors
    const grid = document.getElementById('products-grid');
    const spinner = document.getElementById('loading-spinner');
    const endMsg = document.getElementById('end-message');
    
    // Filter state
    let filterState = {
        duration: '',
        price: '',
        brand: ''
    };

    // Initialize filter dropdowns
    const filterDropdowns = document.querySelectorAll('.filter-dropdown');
    filterDropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.filter-button');
        const menu = dropdown.querySelector('.filter-menu');
        const options = menu.querySelectorAll('.filter-option');
        
        // Toggle dropdown
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const isActive = dropdown.classList.contains('active');
            
            // Close all other dropdowns
            filterDropdowns.forEach(d => d.classList.remove('active'));
            
            // Toggle current
            if (!isActive) {
                dropdown.classList.add('active');
            }
        });
        
        // Handle option clicks
        options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Update button text
                const filterType = button.id.replace('-btn', '').replace('filter-', '');
                const value = option.dataset.value;
                const text = option.textContent;
                
                // Update state
                filterState[filterType] = value;
                
                // Update button text
                button.querySelector('span:first-child').textContent = text;
                
                // Update active state
                options.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                
                // Close dropdown
                dropdown.classList.remove('active');
                
                // Reset and reload
                offset = 0;
                hasMore = true;
                grid.innerHTML = '';
                endMsg.style.display = 'none';
                loadProducts();
            });
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        filterDropdowns.forEach(d => d.classList.remove('active'));
    });

    // Initial Load
    loadProducts();

    // Infinite Scroll
    window.addEventListener('scroll', () => {
        if (isLoading || !hasMore) return;
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 400) {
            loadProducts();
        }
    });

    function loadProducts() {
        if (isLoading) return;
        isLoading = true;
        spinner.style.display = 'block';

        // Build Query String
        const params = new URLSearchParams({
            offset: offset,
            duration: filterState.duration,
            price: filterState.price,
            brand: filterState.brand
        });

        fetch(`/app/products/fetch?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                spinner.style.display = 'none';
                if(data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                    offset += data.count;
                }
                if(!data.hasMore) {
                    hasMore = false;
                    if(offset > 0) endMsg.style.display = 'block';
                }
            })
            .catch(err => console.error(err))
            .finally(() => isLoading = false);
    }
</script>

<?= $this->endSection() ?>