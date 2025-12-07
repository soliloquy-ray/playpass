<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playpass Checkout</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="max-width: 800px; margin: 40px auto; padding: 0 15px;">
    <h1 style="margin-bottom: 30px; text-align: center;">Confirm Your Purchase</h1>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Product Summary -->
        <div class="card">
            <h3 style="margin-top: 0;">Order Summary</h3>
            
            <div style="background-color: var(--secondary); border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: center;">
                <img src="/images/products/<?= esc($product['image'] ?? 'default.png') ?>" 
                     alt="Product" style="width: 100%; height: 200px; object-fit: cover; border-radius: 6px; margin-bottom: 15px;">
                <h4 style="margin: 0 0 5px 0;"><?= esc($product['name']) ?></h4>
                <p style="color: var(--text-muted); margin: 0;"><?= esc($product['description'] ?? '') ?></p>
            </div>

            <div style="background-color: var(--secondary); padding: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Subtotal:</span>
                    <span>₱ <span id="display-subtotal"><?= esc($product['price']) ?></span></span>
                </div>
                <div class="hidden" id="discount-row-checkout" style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--success);">
                    <span>Discount:</span>
                    <span>- ₱ <span id="display-discount-checkout">0.00</span></span>
                </div>
                <div style="border-top: 1px solid var(--border-color); padding-top: 10px; display: flex; justify-content: space-between;">
                    <strong>TOTAL:</strong>
                    <strong style="color: var(--success); font-size: 1.2rem;">₱ <span id="display-total-checkout"><?= esc($product['price']) ?></span></strong>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="card">
            <h3 style="margin-top: 0;">Complete Your Order</h3>

            <form id="checkout-form">
                <input type="hidden" id="user-id" value="<?= session()->get('id') ?>">
                <input type="hidden" id="product-id" value="<?= esc($product['id']) ?>">

                <div class="form-group">
                    <label for="recipient">Send to (Mobile/Email)</label>
                    <input type="text" id="recipient" name="recipient" class="input-dark" 
                           placeholder="09xxxxxxxxx or email@example.com" required>
                </div>

                <!-- Voucher Section -->
                <div class="form-group">
                    <label>Promo Code (Optional)</label>
                    <div class="input-group">
                        <input type="text" id="voucher-code" name="voucher_code" class="input-dark" 
                               placeholder="ENTERCODE">
                        <button type="button" id="btn-apply-voucher" class="btn btn-secondary">Apply</button>
                    </div>
                    <p id="voucher-message" style="font-size: 0.85rem; margin-top: 8px; display: none;"></p>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label>Payment Method</label>
                    <select id="payment-method" name="payment_method" class="input-dark" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card">Credit/Debit Card</option>
                        <option value="gcash">GCash</option>
                        <option value="maya">Maya</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <!-- Terms -->
                <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; text-transform: none; letter-spacing: normal; margin-bottom: 20px;">
                    <input type="checkbox" id="terms" name="terms" required style="margin-top: 2px; flex-shrink: 0;">
                    <span style="font-size: 0.85rem;">
                        I agree to the <a href="#" style="color: var(--primary);">Terms of Service</a> and 
                        <a href="#" style="color: var(--primary);">Privacy Policy</a>
                    </span>
                </label>

                <button type="submit" id="btn-pay" class="btn btn-primary btn-large" style="width: 100%;">
                    💳 Confirm Payment
                </button>

                <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 15px;">
                    🔒 Your payment is secure and encrypted
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    const baseUrl = "<?= base_url() ?>";
    const csrfName = "<?= csrf_token() ?>";
    let csrfHash = "<?= csrf_hash() ?>"; // Token changes on every request
</script>
<script src="/assets/js/checkout.js"></script>

<?= $this->endSection() ?>

</body>
</html>