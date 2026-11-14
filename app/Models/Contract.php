<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model {
    protected $fillable = [
        'reference', 'start_date', 'end_date', 
        'fleet_volume', 'monthly_ht', 'payment_status'
    ];
}
