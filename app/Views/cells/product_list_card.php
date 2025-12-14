<div class="product-list-card" style="
    background-color: <?= $product['bg_color'] ?? '#1a1a1a' ?>; 
    border-radius: 12px; 
    overflow: hidden; 
    display: flex; 
    flex-direction: column;
    aspect-ratio: 1/1;
    border: 1px solid #333;
    position: relative;
    transition: transform 0.2s;">
    
    <a href="/app/buy/<?= $product['brand_id'] ?>" style="text-decoration:none; display:block; height:100%; display:flex; flex-direction:column;">
        
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px;">
             <?php if(!empty($product['logo'])): ?>
                <img src="<?= $product['logo'] ?>" alt="<?= $product['name'] ?>" style="max-width: 80%; max-height: 80px; object-fit: contain;">
             <?php else: ?>
                <h3 style="color:white;"><?= $product['name'] ?></h3>
             <?php endif; ?>
        </div>

        <div style="
            background-color: #ff0055; 
            color: white; 
            text-align: center; 
            padding: 12px; 
            font-weight: bold; 
            text-transform: uppercase;
            font-size: 0.9rem;
            margin: 10px;
            border-radius: 6px;">
            Buy Now
        </div>
    </a>
</div>