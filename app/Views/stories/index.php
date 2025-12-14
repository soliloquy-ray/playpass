<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1000px; padding: 20px 15px;">
    
    <h1 style="margin-bottom: 20px; font-size: 1.8rem; font-weight: 800;">Stories</h1>

    <div class="filters-bar">
        <button class="filter-btn active" data-cat="all">All</button>
        <button class="filter-btn" data-cat="promo">Promos</button>
        <button class="filter-btn" data-cat="event">Events</button>
        <button class="filter-btn" data-cat="story">Stories</button>
        <button class="filter-btn" data-cat="trailer">Trailers</button>
    </div>

    <div id="stories-grid" class="stories-grid-container">
        </div>

    <div id="loading-spinner" style="text-align: center; padding: 30px; display: none;">
        <div class="spinner"></div>
    </div>
    
    <div id="end-message" style="text-align: center; color: #666; display: none; padding: 20px; font-size: 0.9rem;">
        No more stories to load.
    </div>

</div>

<style>
    /* --- Grid Layout Fix --- */
    .stories-grid-container {
        display: grid;
        /* 2 columns on mobile, 4 columns on desktop */
        grid-template-columns: repeat(2, 1fr); 
        gap: 15px;
    }
    
    /* Desktop: 4 columns */
    @media (min-width: 768px) {
        .stories-grid-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    /* Only stack on extremely small devices (e.g. Galaxy Fold folded) */
    @media(max-width: 320px) {
        .stories-grid-container { grid-template-columns: 1fr; }
    }

    /* --- Filters --- */
    .filters-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        overflow-x: auto;
        padding-bottom: 5px;
        -webkit-overflow-scrolling: touch; /* Smooth scroll on mobile */
    }
    
    .filters-bar::-webkit-scrollbar { display: none; } /* Hide scrollbar for clean look */

    .filter-btn {
        background: #1a1a1a;
        border: 1px solid #d8369f; /* Red/pink border */
        color: #fff;
        padding: 6px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
        white-space: nowrap; /* Prevent text wrapping */
    }
    .filter-btn.active, .filter-btn:hover {
        border-color: #d8369f; /* Pink highlight */
        background-color: rgba(216, 54, 159, 0.2);
        color: white;
    }

    /* --- Card Styling (Pixel Perfect Match) --- */
    .story-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s, opacity 0.2s;
    }
    
    .story-card-link:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    
    .story-card {
        background: #121212; /* Darker card bg */
        border: none;
        border-radius: 4px; /* Slightly sharper corners per screenshot */
        overflow: hidden;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .story-image-wrapper {
        position: relative;
        aspect-ratio: 16/9;
        background: #222;
    }
    
    .story-image-wrapper img {
        width: 100%; 
        height: 100%; 
        object-fit: cover;
        display: block;
    }

    /* The "TRAILER" badge on the image */
    .trailer-badge {
        position: absolute;
        bottom: 8px; 
        left: 8px;
        background: #fbbf24; /* Yellow */
        color: #000;
        font-weight: 800; 
        font-size: 0.65rem;
        padding: 3px 6px; 
        border-radius: 2px;
        text-transform: uppercase;
    }

    .story-content { 
        padding: 12px 10px; 
    }

    .story-meta {
        display: flex; 
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px; 
        font-size: 0.75rem;
    }

    .story-category { 
        font-weight: 700; 
        text-transform: uppercase; 
    }
    
    .story-time {
        color: #888;
    }

    /* Colors for Categories */
    .text-yellow-500 { color: #fbbf24; } /* Trailer */
    .text-red-500 { color: #ff0055; }    /* Promo/News */
    .text-purple-500 { color: #c084fc; } /* Event */
    .text-blue-500 { color: #60a5fa; }   /* Story */
    .text-white { color: #fff; }

    .story-title { 
        font-size: 0.9rem; 
        line-height: 1.4;
        margin-bottom: 6px; 
        color: #eee;
        font-weight: 600;
        /* Limit to 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .story-excerpt { 
        color: #888; 
        font-size: 0.8rem; 
        margin: 0; 
        line-height: 1.4;
        /* Limit to 3 lines */
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Animation */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeIn 0.3s ease forwards; }
</style>

<script>
    let currentCategory = 'all'; // Default to 'all' to show all stories
    let offset = 0;
    let isLoading = false;
    let hasMore = true;

    const grid = document.getElementById('stories-grid');
    const spinner = document.getElementById('loading-spinner');
    const endMsg = document.getElementById('end-message');

    // 1. Load Initial Data
    loadStories();

    // 2. Filter Click Handler
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update UI
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Reset Logic
            currentCategory = this.dataset.cat;
            offset = 0;
            hasMore = true;
            grid.innerHTML = ''; // Clear grid
            endMsg.style.display = 'none';
            
            loadStories();
        });
    });

    // 3. Infinite Scroll Handler
    window.addEventListener('scroll', () => {
        if (isLoading || !hasMore) return;
        
        // Trigger when within 300px of bottom
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
            loadStories();
        }
    });

    function loadStories() {
        if (isLoading) return;
        isLoading = true;
        spinner.style.display = 'block';

        const categoryParam = currentCategory === 'all' ? 'all' : currentCategory;
        fetch(`/app/stories/fetch?category=${categoryParam}&offset=${offset}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                spinner.style.display = 'none';
                
                if (data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                    offset += data.count;
                } else if (offset === 0) {
                    // No stories found on first load
                    grid.innerHTML = '<p style="color: #888; text-align: center; grid-column: 1 / -1; padding: 20px;">No stories found.</p>';
                }
                
                if (!data.hasMore) {
                    hasMore = false;
                    // Only show end message if we actually loaded something
                    if(offset > 0) endMsg.style.display = 'block';
                }
            })
            .catch(err => {
                console.error('Error loading stories:', err);
                spinner.style.display = 'none';
                if (offset === 0) {
                    grid.innerHTML = '<p style="color: #ff0055; text-align: center; grid-column: 1 / -1; padding: 20px;">Error loading stories. Please try again.</p>';
                }
            })
            .finally(() => {
                isLoading = false;
            });
    }
</script>

<?= $this->endSection() ?>