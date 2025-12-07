<?php

namespace App\Cells;

class TestimonialCardCell
{
    /**
     * Renders a customer testimonial card
     */
    public function renderCard(array $testimonial = []): string
    {
        $data = [
            'name' => $testimonial['name'] ?? 'User',
            'role' => $testimonial['role'] ?? 'Customer',
            'content' => $testimonial['content'] ?? 'Great experience!',
            'rating' => $testimonial['rating'] ?? 5,
            'avatar' => $testimonial['avatar'] ?? null
        ];

        return view('App\Cells\testimonial_card', $data);
    }
}
