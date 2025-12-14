<section class="featured-products" style="margin-bottom: 40px; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #3b82f6; font-weight: bold; margin: 0;"><?= $title ?></h2>
        </div>

    <div class="scrolling-wrapper" style="
        display: flex; 
        gap: 15px; 
        overflow-x: auto; 
        padding-bottom: 10px; 
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;">
        
        <?php foreach ($products as $product): ?>
            <div class="featured-card" style="
                flex: 0 0 200px; /* Fixed width, won't shrink */
                background-color: <?= $product['bg_color'] ?? '#1a1a1a' ?>; 
                border-radius: 12px; 
                overflow: hidden; 
                display: flex; 
                flex-direction: column;
                aspect-ratio: 1/1;
                border: 1px solid #333;
                position: relative;">
                
                <?php if(isset($product['badge'])): ?>
                    <span style="position: absolute; top: 10px; right: 10px; background: #ff0055; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: bold;">
                        <?= $product['badge'] ?>
                    </span>
                <?php endif; ?>

                <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="max-width: 80%; max-height: 80px; object-fit: contain;">
                </div>

                <a href="/app/buy/<?= $product['id'] ?>" style="
                    background-color: #1e1e2e; 
                    color: #ff0066; 
                    text-align: center; 
                    padding: 12px; 
                    text-decoration: none; 
                    font-weight: bold; 
                    text-transform: uppercase;
                    display: block;
                    font-size: 0.9rem;">
                    BUY NOW
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>