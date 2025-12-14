<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class PaymentChannelCell extends Cell
{
    // These properties match the keys you pass in view_cell()
    public $channels = []; 

    public function render(): string
    {
        // Passes data to the view file you created earlier
        return view('App\Cells\payment_channel', [
            'channels' => $this->channels
        ]);
    }
}