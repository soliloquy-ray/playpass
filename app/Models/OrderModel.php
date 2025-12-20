<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'user_id', 'subtotal', 'discount_total', 'points_redeemed_value', 'grand_total', 
        'payment_status', 'fulfillment_status', 'maya_checkout_id', 'maya_reference_number',
        'recipient_email', 'gift_message'
    ];

    // AUTOMATIC VALIDATION (The Safety Net)
    protected $validationRules = [
        'grand_total' => 'required|decimal|greater_than_equal_to[0]', // Prevents negative payout
        'user_id'     => 'required|integer',
    ];
    
    protected $validationMessages = [
        'grand_total' => [
            'greater_than_equal_to' => 'Order total cannot be negative. Please adjust vouchers.'
        ]
    ];
}