<!-- Cart Sidebar -->
<div id="cart-sidebar" class="cart-sidebar">
    <div class="cart-sidebar-overlay" onclick="closeCartSidebar()"></div>
    <div class="cart-sidebar-content">
        <!-- Cart Header -->
        <div class="cart-sidebar-header">
            <h2 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700;">Shopping Cart</h2>
            <button type="button" onclick="closeCartSidebar()" style="background: transparent; border: none; color: #a0a0a0; font-size: 1.5rem; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="cart-sidebar-body" id="cart-sidebar-items">
            <div class="cart-empty-state" id="cart-empty-state" style="display: none; text-align: center; padding: 40px 20px;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #555; margin-bottom: 20px;"></i>
                <p style="color: #a0a0a0; margin: 0;">Your cart is empty</p>
            </div>
            <div id="cart-items-list">
                <!-- Cart items will be populated by JavaScript -->
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="cart-sidebar-footer">
            <div class="cart-totals" style="padding: 16px; border-top: 1px solid #2a2a35;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="color: #a0a0a0;">Subtotal:</span>
                    <span style="color: white; font-weight: 600;" id="cart-sidebar-subtotal">PHP 0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                    <span style="color: white; font-size: 1.1rem; font-weight: 700;">Total:</span>
                    <span style="color: #d8369f; font-size: 1.3rem; font-weight: 800;" id="cart-sidebar-total">PHP 0.00</span>
                </div>
            </div>
            <button type="button" id="cart-checkout-btn" class="btn btn-primary" onclick="proceedToCheckout()" style="width: 100%; padding: 16px; background: #d8369f; color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;" disabled>
                Checkout
            </button>
        </div>
    </div>
</div>

<style>
.cart-sidebar {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 400px;
    z-index: 10001;
    pointer-events: none;
}

.cart-sidebar[style*="display: flex"],
.cart-sidebar.show {
    display: flex !important;
    pointer-events: all;
}

.cart-sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 10001;
}

.cart-sidebar-content {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 400px;
    background: #151521;
    z-index: 10002;
    display: flex;
    flex-direction: column;
    box-shadow: -4px 0 12px rgba(0, 0, 0, 0.5);
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.cart-sidebar[style*="display: flex"] .cart-sidebar-content {
    transform: translateX(0);
}

.cart-sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #2a2a35;
}

.cart-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.cart-sidebar-footer {
    border-top: 1px solid #2a2a35;
}

.cart-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #2a2a35;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 60px;
    height: 60px;
    background: #1a1a1a;
    border-radius: 8px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #555;
}

.cart-item-details {
    flex: 1;
    min-width: 0;
}

.cart-item-name {
    color: white;
    font-size: 0.95rem;
    font-weight: 500;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-item-price {
    color: #a0a0a0;
    font-size: 0.85rem;
    margin-bottom: 8px;
}

.cart-item-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #1a1a1a;
    border-radius: 6px;
    padding: 4px 8px;
}

.cart-item-quantity button {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 1rem;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background 0.2s;
}

.cart-item-quantity button:hover {
    background: #2a2a35;
}

.cart-item-quantity span {
    color: white;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.cart-item-remove {
    background: transparent;
    border: none;
    color: #ff4444;
    cursor: pointer;
    font-size: 1rem;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background 0.2s;
}

.cart-item-remove:hover {
    background: rgba(255, 68, 68, 0.1);
}

@media (max-width: 768px) {
    .cart-sidebar {
        max-width: 100%;
    }
    
    .cart-sidebar-content {
        max-width: 100%;
    }
}
</style>

