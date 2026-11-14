<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model {
    protected $fillable = [
        'company_name', 'contact_email', 'vehicle_category', 
        'quantity', 'duration_months', 'annual_km', 'options', 'status'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}