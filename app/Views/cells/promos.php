<section class="promos">
    <div class="promo-banner">
        <div class="promo-content">
            <h2><?= $bannerTitle ?></h2>
            <p><?= $bannerDescription ?></p>
            <div class="promo-discount"><?= $bannerDiscount ?></div>
            <p class="promo-expiry"><?= $bannerExpiry ?></p>
            <button class="btn btn-cta"><?= $bannerCTA ?></button>
        </div>
        <div class="promo-image">
            <img src="<?= $bannerImage ?>" alt="<?= $bannerTitle ?>" loading="lazy">
        </div>
    </div>

    <div class="promo-cards">
        <?php foreach ($promos as $promo): ?>
            <div class="promo-card">
                <img src="<?= $promo['image'] ?>" alt="<?= $promo['title'] ?>" loading="lazy">
                <div class="promo-info">
                    <h3><?= $promo['title'] ?></h3>
                    <div class="promo-discount-badge"><?= $promo['discount'] ?></div>
                    <p class="promo-code">Code: <strong><?= $promo['code'] ?></strong></p>
                    <p class="promo-condition">Min spend: $<?= $promo['minSpend'] ?></p>
                    <button class="btn btn-outline">Use Code</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
