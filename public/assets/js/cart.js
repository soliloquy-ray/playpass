/**
 * Cart Management and Checkout System
 */

// Global cart state
let cartState = {
    items: [],
    subtotal: 0,
    discount: 0,
    pointsValue: 0,
    total: 0,
    points: 0,
    pointsToPesoRatio: 100,
    appliedVouchers: [],
    paymentMethod: 'gcash'
};

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart.js loaded');
    loadCart();
    setupCheckoutModal();
    setupEventListeners();
    
    // Initialize global variables if not set
    if (typeof window.selectedProductId === 'undefined') {
        window.selectedProductId = null;
    }
    if (typeof window.selectedPaymentMethod === 'undefined') {
        window.selectedPaymentMethod = 'gcash';
    }
});

/**
 * Load cart from server
 */
async function loadCart() {
    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/cart`, {
            method: 'GET',
            headers: headers
        });
        
        const result = await response.json();
        
        if (result.success) {
            cartState.items = result.cart.items || [];
            cartState.subtotal = result.cart.subtotal || 0;
            cartState.total = result.cart.total || 0;
            updateCartUI();
            console.log('Cart loaded:', cartState);
        }
    } catch (error) {
        console.error('Error loading cart:', error);
    }
}

/**
 * Add product to cart
 */
async function addToCart(productId, quantity = 1) {
    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/cart/add`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.items = result.cart.items || [];
            cartState.subtotal = result.cart.subtotal || 0;
            cartState.total = result.cart.total || 0;
            updateCartUI();
            return { success: true, message: result.message };
        } else {
            return { success: false, message: result.message || 'Failed to add product' };
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        return { success: false, message: error.message || 'Error adding product to cart' };
    }
}

/**
 * Remove product from cart
 */
async function removeFromCart(productId) {
    if (!confirm('Remove this item from cart?')) {
        return;
    }
    
    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/cart/remove`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ product_id: productId })
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.items = result.cart.items || [];
            cartState.subtotal = result.cart.subtotal || 0;
            cartState.total = result.cart.total || 0;
            updateCartUI();
            updateCheckoutModal();
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}

/**
 * Update product quantity
 */
async function updateCartQuantity(productId, quantity) {
    if (quantity <= 0) {
        removeFromCart(productId);
        return;
    }

    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/cart/update`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.items = result.cart.items || [];
            cartState.subtotal = result.cart.subtotal || 0;
            cartState.total = result.cart.total || 0;
            updateCartUI();
            updateCheckoutModal();
        }
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

/**
 * Open checkout modal
 */
async function openCheckoutModal() {
    if (cartState.items.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Empty Cart',
                text: 'Your cart is empty',
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff'
            });
        } else {
            alert('Your cart is empty');
        }
        return;
    }

    const modal = document.getElementById('checkout-modal');
    if (!modal) {
        console.error('Checkout modal not found in DOM');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Checkout modal not found. Please refresh the page.',
                icon: 'error',
                background: '#1a1a1a',
                color: '#fff'
            });
        } else {
            alert('Checkout modal not found. Please refresh the page.');
        }
        return;
    }

    // Load checkout data
    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/checkout`, {
            method: 'GET',
            headers: headers
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.items = result.cart.items || [];
            cartState.subtotal = result.cart.subtotal || 0;
            cartState.points = result.points || 0;
            cartState.pointsToPesoRatio = result.points_to_peso_ratio || 100;
            
            // Set user email if available
            const emailInput = document.getElementById('checkout-email');
            if (emailInput && result.user_email) {
                emailInput.value = result.user_email;
            }
        }
    } catch (error) {
        console.error('Error loading checkout data:', error);
    }

    updateCheckoutModal();
    // Use the active class which has display: flex in CSS
    modal.classList.add('active');
    // Also set inline style as backup
    modal.style.display = 'flex';
    
    // Reset gift fields
    const giftCheckbox = document.getElementById('checkout-gift');
    const giftFields = document.getElementById('checkout-gift-fields');
    if (giftCheckbox && giftFields) {
        giftCheckbox.checked = false;
        giftFields.style.display = 'none';
        const recipientEmail = document.getElementById('checkout-recipient-email');
        const giftMessage = document.getElementById('checkout-gift-message');
        if (recipientEmail) recipientEmail.value = '';
        if (giftMessage) giftMessage.value = 'Enjoy watching!';
    }
    
    console.log('Modal opened, element:', modal, 'classes:', modal.className);
}

/**
 * Close checkout modal
 */
function closeCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
        console.log('Modal closed');
    }
}

/**
 * Update checkout modal display
 */
function updateCheckoutModal() {
    // Update items list
    const itemsList = document.getElementById('checkout-items-list');
    if (itemsList) {
        itemsList.innerHTML = cartState.items.map(item => `
            <div class="checkout-item">
                <span class="checkout-item-name">${item.name} x${item.quantity}</span>
                <span class="checkout-item-price">PHP ${item.line_total.toFixed(2)}</span>
            </div>
        `).join('');
    }

    // Update payment method
    const paymentMethod = document.getElementById('checkout-payment-method');
    if (paymentMethod) {
        paymentMethod.textContent = cartState.paymentMethod.toUpperCase();
    }

    // Update available points
    const availablePoints = document.getElementById('checkout-available-points');
    if (availablePoints) {
        availablePoints.textContent = cartState.points;
    }

    // Update total
    calculateAndUpdateTotal();
}

/**
 * Apply voucher code
 */
async function applyVoucher() {
    const voucherInput = document.getElementById('checkout-voucher-code');
    const voucherMessage = document.getElementById('checkout-voucher-message');
    
    if (!voucherInput || !voucherInput.value.trim()) {
        if (voucherMessage) {
            voucherMessage.textContent = 'Please enter a voucher code';
            voucherMessage.style.display = 'block';
            voucherMessage.style.color = '#ff4444';
        }
        return;
    }

    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/checkout/apply-voucher`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ voucher_code: voucherInput.value.trim().toUpperCase() })
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.discount = result.discount_amount || 0;
            cartState.total = result.new_total || cartState.subtotal;
            
            if (voucherMessage) {
                voucherMessage.textContent = result.message || 'Voucher applied!';
                voucherMessage.style.display = 'block';
                voucherMessage.style.color = '#4CAF50';
            }
            
            calculateAndUpdateTotal();
        } else {
            if (voucherMessage) {
                voucherMessage.textContent = result.message || 'Invalid voucher code';
                voucherMessage.style.display = 'block';
                voucherMessage.style.color = '#ff4444';
            }
        }
    } catch (error) {
        console.error('Error applying voucher:', error);
        if (voucherMessage) {
            voucherMessage.textContent = 'Error applying voucher';
            voucherMessage.style.display = 'block';
            voucherMessage.style.color = '#ff4444';
        }
    }
}

/**
 * Apply points redemption
 */
async function applyPoints() {
    const pointsInput = document.getElementById('checkout-points-input');
    const pointsValue = document.getElementById('checkout-points-value');
    
    if (!pointsInput) return;

    const pointsToRedeem = parseInt(pointsInput.value) || 0;

    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const response = await fetch(`${baseUrl}app/checkout/apply-points`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ points: pointsToRedeem })
        });

        const result = await response.json();
        
        if (result.success) {
            cartState.pointsValue = result.points_value || 0;
            
            if (pointsValue) {
                pointsValue.textContent = `₱${result.points_value.toFixed(2)}`;
            }
            
            calculateAndUpdateTotal();
        } else {
            if (typeof showAlert === 'function') {
                showAlert(result.message || 'Error applying points', 'error');
            }
        }
    } catch (error) {
        console.error('Error applying points:', error);
    }
}

/**
 * Calculate and update total
 */
function calculateAndUpdateTotal() {
    const total = Math.max(0, cartState.subtotal - cartState.discount - cartState.pointsValue);
    cartState.total = total;
    
    const totalElement = document.getElementById('checkout-total');
    if (totalElement) {
        totalElement.textContent = `PHP ${total.toFixed(2)}`;
    }
}

/**
 * Process checkout payment
 */
async function processCheckout() {
    const emailInput = document.getElementById('checkout-email');
    const mobileInput = document.getElementById('checkout-mobile');
    const giftCheckbox = document.getElementById('checkout-gift');
    const recipientEmailInput = document.getElementById('checkout-recipient-email');
    const giftMessageInput = document.getElementById('checkout-gift-message');
    const payButton = document.getElementById('checkout-pay-now');

    if (!emailInput || !mobileInput) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Email and mobile number fields are required',
                icon: 'error',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
        return;
    }

    const email = emailInput.value.trim();
    const mobile = mobileInput.value.trim();
    const isGift = giftCheckbox ? giftCheckbox.checked : false;
    const recipientEmail = recipientEmailInput ? recipientEmailInput.value.trim() : '';
    const giftMessage = giftMessageInput ? giftMessageInput.value.trim() : '';

    if (!email && !mobile) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Required',
                text: 'Please provide email or mobile number',
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
        return;
    }

    // Validate gift fields if gift is checked
    if (isGift && !recipientEmail) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Required',
                text: 'Please provide recipient email for gift',
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
        return;
    }

    // Disable button
    if (payButton) {
        payButton.disabled = true;
        payButton.textContent = 'Processing...';
    }

    try {
        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[csrfName] = csrfHash;

        const payload = {
            email: email,
            mobile: mobile,
            is_gift: isGift,
            recipient_email: isGift ? recipientEmail : null,
            gift_message: isGift ? giftMessage : null,
            payment_method: cartState.paymentMethod
        };

        const response = await fetch(`${baseUrl}app/checkout/process`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Clear cart
            cartState.items = [];
            cartState.subtotal = 0;
            cartState.discount = 0;
            cartState.pointsValue = 0;
            cartState.total = 0;
            
            closeCheckoutModal();
            
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Purchase Successful!',
                    html: result.pin_code ? 
                        `Your PIN is: <h2 style="color:#4CAF50">${result.pin_code}</h2>` : 
                        result.message || 'Order placed successfully!',
                    icon: 'success',
                    background: '#1a1a1a',
                    color: '#fff'
                }).then(() => {
                    window.location.href = `${baseUrl}app/account`;
                });
            } else {
                alert(result.message || 'Order placed successfully!');
                window.location.href = `${baseUrl}app/account`;
            }
        } else {
            throw new Error(result.message || 'Checkout failed');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: error.message || 'Checkout failed',
                icon: 'error',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
        
        if (payButton) {
            payButton.disabled = false;
            payButton.textContent = 'Pay Now';
        }
    }
}

/**
 * Setup checkout modal event listeners
 */
function setupCheckoutModal() {
    // Close modal on outside click
    const modal = document.getElementById('checkout-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCheckoutModal();
            }
        });
    }

    // Gift checkbox toggle
    const giftCheckbox = document.getElementById('checkout-gift');
    const giftFields = document.getElementById('checkout-gift-fields');
    if (giftCheckbox && giftFields) {
        giftCheckbox.addEventListener('change', function() {
            if (this.checked) {
                giftFields.style.display = 'block';
            } else {
                giftFields.style.display = 'none';
                // Clear gift fields when unchecked
                const recipientEmail = document.getElementById('checkout-recipient-email');
                const giftMessage = document.getElementById('checkout-gift-message');
                if (recipientEmail) recipientEmail.value = '';
                if (giftMessage) giftMessage.value = 'Enjoy watching!';
            }
        });
    }

    // Apply voucher button
    const applyVoucherBtn = document.getElementById('checkout-apply-voucher');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', applyVoucher);
    }

    // Convert points button
    const convertPointsBtn = document.getElementById('checkout-convert-points');
    if (convertPointsBtn) {
        convertPointsBtn.addEventListener('click', applyPoints);
    }

    // Pay now button
    const payButton = document.getElementById('checkout-pay-now');
    if (payButton) {
        payButton.addEventListener('click', processCheckout);
    }
}

/**
 * Setup general event listeners
 */
function setupEventListeners() {
    // Update cart UI when cart changes
    // This can be called from other parts of the app
}

/**
 * Update cart UI (cart icon, badge, etc.)
 */
function updateCartUI() {
    // Update cart badge if exists
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        const itemCount = cartState.items.reduce((sum, item) => sum + item.quantity, 0);
        cartBadge.textContent = itemCount;
        cartBadge.style.display = itemCount > 0 ? 'flex' : 'none';
    }
    
    // Update cart sidebar if open
    if (document.getElementById('cart-sidebar')?.style.display === 'flex') {
        updateCartSidebar();
    }
}

/**
 * Open cart sidebar
 */
function openCartSidebar() {
    const sidebar = document.getElementById('cart-sidebar');
    if (sidebar) {
        sidebar.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        updateCartSidebar();
        console.log('Cart sidebar opened');
    }
}

/**
 * Close cart sidebar
 */
function closeCartSidebar() {
    const sidebar = document.getElementById('cart-sidebar');
    if (sidebar) {
        sidebar.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
        console.log('Cart sidebar closed');
    }
}

/**
 * Update cart sidebar display
 */
function updateCartSidebar() {
    const itemsList = document.getElementById('cart-items-list');
    const emptyState = document.getElementById('cart-empty-state');
    const checkoutBtn = document.getElementById('cart-checkout-btn');
    const subtotalEl = document.getElementById('cart-sidebar-subtotal');
    const totalEl = document.getElementById('cart-sidebar-total');
    
    if (!itemsList) return;
    
    if (cartState.items.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
        itemsList.innerHTML = '';
        if (checkoutBtn) checkoutBtn.disabled = true;
        if (subtotalEl) subtotalEl.textContent = 'PHP 0.00';
        if (totalEl) totalEl.textContent = 'PHP 0.00';
        return;
    }
    
    if (emptyState) emptyState.style.display = 'none';
    if (checkoutBtn) checkoutBtn.disabled = false;
    
    // Render cart items
    itemsList.innerHTML = cartState.items.map(item => `
        <div class="cart-item" data-product-id="${item.product_id}">
            <div class="cart-item-image">
                ${item.thumbnail_url ? 
                    `<img src="${baseUrl}${item.thumbnail_url}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">` :
                    `<i class="fas fa-box" style="font-size: 1.5rem;"></i>`
                }
            </div>
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">PHP ${item.price.toFixed(2)} each</div>
                <div class="cart-item-actions">
                    <div class="cart-item-quantity">
                        <button type="button" onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})" title="Decrease">-</button>
                        <span>${item.quantity}</span>
                        <button type="button" onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})" title="Increase">+</button>
                    </div>
                    <button type="button" class="cart-item-remove" onclick="removeFromCart(${item.product_id})" title="Remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    // Update totals
    if (subtotalEl) subtotalEl.textContent = `PHP ${cartState.subtotal.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `PHP ${cartState.total.toFixed(2)}`;
}

/**
 * Proceed to checkout from cart sidebar
 */
function proceedToCheckout() {
    if (cartState.items.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Empty Cart',
                text: 'Your cart is empty',
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
        return;
    }
    
    closeCartSidebar();
    setTimeout(() => {
        openCheckoutModal();
    }, 300);
}

/**
 * Handle checkout button click from product page
 * Adds selected product to cart and shows cart sidebar
 */
async function handleCheckout() {
    console.log('handleCheckout called');
    console.log('window.selectedProductId:', window.selectedProductId);
    
    // Check if product is selected
    const selectedProductId = window.selectedProductId;
    
    if (!selectedProductId) {
        console.warn('No product selected');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Select Product',
                text: 'Please select a product variant first',
                icon: 'warning',
                background: '#1a1a1a',
                color: '#fff',
                timer: 2000
            });
        } else {
            alert('Please select a product variant first');
        }
        return;
    }

    // Check if payment method is selected (optional, default to gcash)
    const selectedPayment = window.selectedPaymentMethod || 'gcash';
    if (typeof cartState !== 'undefined') {
        cartState.paymentMethod = selectedPayment;
    }

    console.log('Adding product to cart:', selectedProductId, 'with payment method:', selectedPayment);

    // Add product to cart
    try {
        const result = await addToCart(selectedProductId, 1);
        console.log('addToCart result:', result);
        
        if (result && result.success !== false) {
            // Show success message and open cart sidebar
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Added to Cart!',
                    text: 'Product added to your cart',
                    icon: 'success',
                    background: '#1a1a1a',
                    color: '#fff',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
            
            // Small delay to ensure cart is updated, then open cart sidebar
            setTimeout(() => {
                console.log('Opening cart sidebar...');
                openCartSidebar();
            }, 500);
        } else {
            throw new Error(result?.message || 'Failed to add product to cart');
        }
    } catch (error) {
        console.error('Error in handleCheckout:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: error.message || 'Error adding product to cart',
                icon: 'error',
                background: '#1a1a1a',
                color: '#fff'
            });
        } else {
            alert(error.message || 'Error adding product to cart');
        }
    }
}

// Export functions for global use
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;
window.updateCartQuantity = updateCartQuantity;
window.openCheckoutModal = openCheckoutModal;
window.closeCheckoutModal = closeCheckoutModal;
window.openCartSidebar = openCartSidebar;
window.closeCartSidebar = closeCartSidebar;
window.proceedToCheckout = proceedToCheckout;
window.handleCheckout = handleCheckout;

