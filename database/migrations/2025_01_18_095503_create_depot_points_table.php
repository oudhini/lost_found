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
        Schema::create('depots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // Nom du point de dépôt (ex : Bureau de poste central)
            $table->string('address'); // Adresse du point de dépôt
            $table->string('contact')->nullable(); // Contact téléphonique ou email
            $table->unsignedBigInteger('gerant_id')->nullable();
            $table->foreign('gerant_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_points');
    }
};
