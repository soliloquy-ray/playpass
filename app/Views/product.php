<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 800px; padding: 20px 15px;">

    <div class="brand-header-card">
        <div style="display: flex; gap: 20px;">
            <div style="width: 100px; height: 100px; background-color: #ffc107; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <?php if(!empty($brand['logo'])): ?>
                    <img src="<?= asset_url($brand['logo']) ?>" alt="<?= esc($brand['name']) ?>" style="width: 80%; height: auto;">
                <?php else: ?>
                    <span style="font-weight:bold; color:#000;"><?= substr($brand['name'], 0, 1) ?></span>
                <?php endif; ?>
            </div>

            <div style="flex: 1;">
                <div class="brand-tags">
                    <span class="tag-pill">Instant Delivery</span>
                    <span class="tag-pill">Get via E-Mail/SMS</span>
                    <span class="tag-pill">Secure Payment</span>
                </div>

                <h1 style="font-size: 1.8rem; margin-bottom: 5px;"><?= $brand['name'] ?></h1>
                <p style="color: #a0a0a0; font-size: 0.9rem; margin-bottom: 0;">
                    Get premium access instantly. Select your preferred package below.
                </p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 10px; color: #3b82f6;">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </div>

    <?= view_cell('App\Cells\SelectAmountCell::render', ['products' => $products]) ?>

    <?= view_cell('App\Cells\PaymentChannelCell::render', ['channels' => $paymentChannels]) ?>

    <?= view_cell('App\Cells\RedemptionStepsCell::render', ['brandName' => $brand['name']]) ?>

</div>

<?= $this->endSection() ?>