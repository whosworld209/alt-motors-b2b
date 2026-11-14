<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // ex: CTR-2026-001
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('fleet_volume');
            $table->decimal('monthly_ht', 12, 2);
            $table->string('payment_status')->default('À jour');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contracts');
    }
};