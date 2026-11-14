<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        Contract::create([
            'reference' => 'CTR-2026-001',
            'start_date' => '2026-01-15',
            'end_date' => '2028-01-15',
            'fleet_volume' => 3,
            'monthly_ht' => 1150000,
            'payment_status' => 'À jour',
        ]);
    }
}
