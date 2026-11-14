<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');                 // Marque (ex: Toyota, Hyundai)
            $table->string('model');                 // Modèle (ex: Camry, Tucson)
            $table->string('plate_number')->unique(); // Immatriculation unique (ex: TG-1234-AA)
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available'); // Statut
            $table->decimal('latitude', 10, 8)->nullable();  // Latitude GPS (Lomé)
            $table->decimal('longitude', 11, 8)->nullable(); // Longitude GPS (Lomé)
            $table->string('image')->nullable();     // URL ou chemin de l'image du véhicule[cite: 1]
            $table->timestamp('last_seen_at')->nullable(); // Date de dernière mise à jour télématique
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehicles');
    }
};