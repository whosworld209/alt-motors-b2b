<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder {
    public function run(): void {
        Vehicle::create([
            'brand' => 'Toyota',
            'model' => 'Camry V6',
            'plate_number' => 'TG-1234-AA',
            'status' => 'available',
            'latitude' => 6.1319, // Centre-ville Lomé
            'longitude' => 1.2228,
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=600&q=80',
            'last_seen_at' => now(),
        ]);

        Vehicle::create([
            'brand' => 'Hyundai',
            'model' => 'Tucson 4x4',
            'plate_number' => 'TG-5678-BB',
            'status' => 'rented',
            'latitude' => 6.1750, // Agoè, Lomé
            'longitude' => 1.2125,
            'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=600&q=80',
            'last_seen_at' => now(),
        ]);

        Vehicle::create([
            'brand' => 'Toyota',
            'model' => 'Land Cruiser Prado',
            'plate_number' => 'TG-9876-CC',
            'status' => 'maintenance',
            'latitude' => 6.1230, // Port de Lomé
            'longitude' => 1.2550,
            'image' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=600&q=80',
            'last_seen_at' => now(),
        ]);
    }
}