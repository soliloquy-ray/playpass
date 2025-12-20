<?php

namespace App\Cells;

class ProductCell
{
    /**
     * Renders the product card view.
     * Logic for badges and formatting happens here.
     */
    public function renderCard(array $product): string
    {
        // Calculate logic before sending to view
        $data = [
            'product'      => $product,
            'formattedPrice' => number_format($product['price'], 2),
            'showPoints'   => $product['points_to_earn'] > 0,
            'isBundle'     => (bool) ($product['is_bundle'] ?? false),
            // Fallback image logic
            'image'        => !empty($product['thumbnail_url']) 
                                ? $product['thumbnail_url'] 
                                : null
        ];

        return view('cells/product_card', $data);
    }
}