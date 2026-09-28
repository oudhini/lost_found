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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('depot_id')->nullable(); // Permet d'avoir des documents non liés à un dépôt (perdus);
            $table->enum('type_document', ['passport', 'permis_de_conduire', 'CNI', 'acte_de_naissance','diplome_academique','autres']);
            $table->string('numero_du_document');
            $table->string('photos');
            $table->string('lieu_de_perte');
            $table->string('nom_present_sur_le_document');
            $table->enum('status', ['perdu','restitué', 'en_attente_de_retrait']);
            $table->string('contact_info');
            $table->text('additional_info')->nullable();
        
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('cascade');

           

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
