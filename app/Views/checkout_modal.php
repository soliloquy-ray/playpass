<!-- Checkout Modal -->
<div id="checkout-modal" class="modal">
    <div class="modal-content" style="max-width: 600px; background: #151521; border-radius: 16px; padding: 0;">
        <!-- Modal Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px; border-bottom: 1px solid #2a2a35;">
            <h2 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700;">Checkout</h2>
            <button type="button" class="modal-close" onclick="closeCheckoutModal()" style="background: transparent; border: none; color: #a0a0a0; font-size: 1.5rem; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div style="padding: 24px; max-height: 70vh; overflow-y: auto;">
            <!-- Items List -->
            <div style="margin-bottom: 24px;">
                <h3 style="color: white; font-size: 1rem; font-weight: 600; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Item</h3>
                <div id="checkout-items-list">
                    <!-- Items will be populated by JavaScript -->
                </div>
            </div>

            <!-- Payment Method -->
            <div style="margin-bottom: 24px;">
                <h3 style="color: white; font-size: 1rem; font-weight: 600; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Payment</h3>
                <div id="checkout-payment-method" style="color: #a0a0a0; font-size: 0.95rem;">
                    <!-- Payment method will be populated by JavaScript -->
                </div>
            </div>

            <!-- Contact Information -->
            <div style="margin-bottom: 24px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Email</label>
                    <input type="email" id="checkout-email" class="input-dark" placeholder="Email" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Mobile Number</label>
                    <input type="text" id="checkout-mobile" class="input-dark" placeholder="09XXXXXXXXXXX" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white;">
                </div>
                <label style="display: flex; align-items: center; gap: 8px; color: #a0a0a0; font-size: 0.9rem; cursor: pointer; margin-bottom: 16px;">
                    <input type="checkbox" id="checkout-gift" style="width: 18px; height: 18px; cursor: pointer; accent-color: #d8369f;">
                    <span>Gift to friend</span>
                </label>
                
                <!-- Gift Fields (Hidden by default) -->
                <div id="checkout-gift-fields" style="display: none;">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Recipient Email</label>
                        <input type="email" id="checkout-recipient-email" class="input-dark" placeholder="Email" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white;">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Message</label>
                        <textarea id="checkout-gift-message" class="input-dark" placeholder="Enjoy watching!" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white; min-height: 80px; resize: vertical; font-family: inherit;">Enjoy watching!</textarea>
                    </div>
                </div>
            </div>

            <!-- Points Redemption -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">
                    Use my <span id="checkout-available-points">0</span> points
                </label>
                <div style="display: flex; gap: 8px;">
                    <input type="number" id="checkout-points-input" min="0" value="0" class="input-dark" placeholder="0" style="flex: 1; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white;">
                    <button type="button" id="checkout-convert-points" class="btn btn-secondary" style="padding: 12px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Convert</button>
                </div>
                <p id="checkout-points-value" style="color: #a0a0a0; font-size: 0.85rem; margin-top: 8px; margin-bottom: 0;">₱0.00</p>
            </div>

            <!-- Voucher Code -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; color: white; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Do you have a voucher code?</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="checkout-voucher-code" class="input-dark" placeholder="Voucher Code" style="flex: 1; padding: 12px; background: #1a1a1a; border: 1px solid #2a2a35; border-radius: 8px; color: white; text-transform: uppercase;">
                    <button type="button" id="checkout-apply-voucher" class="btn btn-secondary" style="padding: 12px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Apply</button>
                </div>
                <p id="checkout-voucher-message" style="color: #a0a0a0; font-size: 0.85rem; margin-top: 8px; margin-bottom: 0; display: none;"></p>
            </div>

            <!-- Total -->
            <div style="border-top: 1px solid #2a2a35; padding-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: white; font-size: 1.1rem; font-weight: 600;">Total</span>
                <span id="checkout-total" style="color: #d8369f; font-size: 1.5rem; font-weight: 800;">PHP 0.00</span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 24px; border-top: 1px solid #2a2a35;">
            <button type="button" id="checkout-pay-now" class="btn btn-primary" style="width: 100%; padding: 16px; background: #d8369f; color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                Pay Now
            </button>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: none; /* Hidden by default */
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.modal.active {
    display: flex; /* Show when active class is added */
}

.modal-content {
    background: #151521;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-close:hover {
    background: #2a2a35;
    color: white;
}

#checkout-items-list .checkout-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #2a2a35;
}

#checkout-items-list .checkout-item:last-child {
    border-bottom: none;
}

#checkout-items-list .checkout-item-name {
    color: white;
    font-size: 0.95rem;
}

#checkout-items-list .checkout-item-price {
    color: #a0a0a0;
    font-size: 0.95rem;
}
</style>

