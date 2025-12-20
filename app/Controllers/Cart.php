<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CartService;

class Cart extends BaseController
{
    protected $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    /**
     * Get cart contents
     */
    public function get()
    {
        $cart = $this->cartService->getCartSummary();
        return $this->response->setJSON([
            'success' => true,
            'cart' => $cart
        ]);
    }

    /**
     * Add product to cart
     */
    public function add()
    {
        $json = $this->request->getJSON();
        $productId = (int)($json->product_id ?? 0);
        $quantity = (int)($json->quantity ?? 1);

        if (!$productId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product ID is required.'
            ]);
        }

        $result = $this->cartService->addToCart($productId, $quantity);
        return $this->response->setJSON($result);
    }

    /**
     * Remove product from cart
     */
    public function remove()
    {
        $json = $this->request->getJSON();
        $productId = (int)($json->product_id ?? 0);

        if (!$productId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product ID is required.'
            ]);
        }

        $result = $this->cartService->removeFromCart($productId);
        return $this->response->setJSON($result);
    }

    /**
     * Update product quantity
     */
    public function update()
    {
        $json = $this->request->getJSON();
        $productId = (int)($json->product_id ?? 0);
        $quantity = (int)($json->quantity ?? 1);

        if (!$productId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product ID is required.'
            ]);
        }

        $result = $this->cartService->updateQuantity($productId, $quantity);
        return $this->response->setJSON($result);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $result = $this->cartService->clearCart();
        return $this->response->setJSON($result);
    }
}

