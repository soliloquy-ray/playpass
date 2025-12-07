<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'user_id', 'subtotal', 'discount_total', 'grand_total', 
        'payment_status', 'maya_checkout_id'
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