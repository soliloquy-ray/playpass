<?php

namespace App\Libraries;

use App\Models\ProductModel;

class CartService
{
    protected $productModel;
    protected $session;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Get the entire cart
     * 
     * @return array
     */
    public function getCart(): array
    {
        return $this->session->get('cart') ?? [];
    }

    /**
     * Add product to cart
     * 
     * @param int $productId
     * @param int $quantity
     * @return array
     */
    public function addToCart(int $productId, int $quantity = 1): array
    {
        $product = $this->productModel->find($productId);
        
        if (!$product || !$product['is_active']) {
            return ['success' => false, 'message' => 'Product not found or unavailable.'];
        }

        $cart = $this->getCart();
        
        // Check if product already in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // If not found, add new item
        if (!$found) {
            $cart[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => (float)$product['price'],
                'name' => $product['name'],
                'thumbnail_url' => $product['thumbnail_url'] ?? null,
            ];
        }

        $this->session->set('cart', $cart);

        return [
            'success' => true,
            'message' => 'Product added to cart.',
            'cart' => $this->getCartSummary()
        ];
    }

    /**
     * Remove product from cart
     * 
     * @param int $productId
     * @return array
     */
    public function removeFromCart(int $productId): array
    {
        $cart = $this->getCart();
        $cart = array_filter($cart, function($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        $this->session->set('cart', array_values($cart)); // Re-index array

        return [
            'success' => true,
            'message' => 'Product removed from cart.',
            'cart' => $this->getCartSummary()
        ];
    }

    /**
     * Update product quantity in cart
     * 
     * @param int $productId
     * @param int $quantity
     * @return array
     */
    public function updateQuantity(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }

        $cart = $this->getCart();
        $found = false;

        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return ['success' => false, 'message' => 'Product not found in cart.'];
        }

        $this->session->set('cart', $cart);

        return [
            'success' => true,
            'message' => 'Cart updated.',
            'cart' => $this->getCartSummary()
        ];
    }

    /**
     * Clear entire cart
     * 
     * @return array
     */
    public function clearCart(): array
    {
        $this->session->remove('cart');
        return [
            'success' => true,
            'message' => 'Cart cleared.',
            'cart' => $this->getCartSummary()
        ];
    }

    /**
     * Get cart summary with totals
     * 
     * @return array
     */
    public function getCartSummary(): array
    {
        $cart = $this->getCart();
        $items = [];
        $subtotal = 0;
        $itemCount = 0;

        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $subtotal += $lineTotal;
            $itemCount += $item['quantity'];

            $items[] = [
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'line_total' => $lineTotal,
                'thumbnail_url' => $item['thumbnail_url'] ?? null,
            ];
        }

        return [
            'items' => $items,
            'item_count' => $itemCount,
            'subtotal' => round($subtotal, 2),
            'total' => round($subtotal, 2), // Will be updated with discounts
        ];
    }

    /**
     * Get product IDs in cart (for voucher validation)
     * 
     * @return array
     */
    public function getProductIds(): array
    {
        $cart = $this->getCart();
        return array_column($cart, 'product_id');
    }

    /**
     * Check if cart is empty
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }
}

