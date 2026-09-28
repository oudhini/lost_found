<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('depots', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable(); // Ajouter une colonne latitude
            $table->decimal('longitude', 11, 8)->nullable(); // Ajouter une colonne longitude
            $table->string('opening_hours')->nullable(); // Ajouter une colonne pour les heures d'ouverture
            $table->enum('statut',['actif','inactif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('depots', function (Blueprint $table) {
            //
        });
    }
};
