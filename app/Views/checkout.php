<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Playpass Checkout</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> </head>
<body>
    <?= $this->renderSection('content') ?>
    
    <script>
        const baseUrl = "<?= base_url() ?>";
        const csrfName = "<?= csrf_token() ?>";
        let csrfHash = "<?= csrf_hash() ?>"; // Token changes on every request
    </script>
    <script src="/assets/js/checkout.js"></script>
</body>
</html>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="checkout-container">
    <h1>Confirm Purchase</h1>
    
    <div class="card product-card">
        <img src="/images/products/<?= esc($product['image'] ?? 'default.png') ?>" alt="Product">
        <h3><?= esc($product['name']) ?></h3>
        <p class="price-tag">₱ <span id="base-price"><?= esc($product['price']) ?></span></p>
    </div>

    <form id="checkout-form">
        <input type="hidden" id="user-id" value="<?= session()->get('id') ?>">
        <input type="hidden" id="product-id" value="<?= esc($product['id']) ?>">

        <div class="form-group">
            <label>Send to (Mobile/Email)</label>
            <input type="text" id="recipient" class="input-dark" placeholder="0917..." required>
        </div>

        <div class="voucher-section">
            <label>Promo Code</label>
            <div class="input-group">
                <input type="text" id="voucher-code" class="input-dark" placeholder="ENTER CODE">
                <button type="button" id="btn-apply-voucher" class="btn-secondary">Apply</button>
            </div>
            <p id="voucher-message" class="text-small"></p>
        </div>

        <hr>

        <div class="totals">
            <div class="row">
                <span>Subtotal:</span>
                <span>₱ <span id="display-subtotal"><?= esc($product['price']) ?></span></span>
            </div>
            <div class="row discount-row hidden">
                <span>Discount:</span>
                <span class="text-green">- ₱ <span id="display-discount">0.00</span></span>
            </div>
            <div class="row grand-total">
                <span>TOTAL:</span>
                <span>₱ <span id="display-total"><?= esc($product['price']) ?></span></span>
            </div>
        </div>

        <button type="submit" id="btn-pay" class="btn-primary btn-large">
            CONFIRM PAYMENT
        </button>
    </form>
</div>
<?= $this->endSection() ?>