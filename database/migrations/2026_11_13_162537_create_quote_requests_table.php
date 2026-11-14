<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_email');
            $table->string('vehicle_category');
            $table->integer('quantity');
            $table->integer('duration_months');
            $table->integer('annual_km')->nullable();
            $table->json('options')->nullable(); // Stocke les options cochées
            $table->string('status')->default('En étude');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('quote_requests');
    }
};