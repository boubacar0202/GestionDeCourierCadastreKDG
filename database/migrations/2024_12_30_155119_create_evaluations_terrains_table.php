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
        Schema::create('evaluations_terrains', function (Blueprint $table) {
            $table->id();
            $table->date('txt_date_devaluation');
            $table->integer('txt_superficie_totale');
            $table->integer('txt_superficie_bati_sol');
            $table->string('slt_secteur');
            $table->integer('nbr_prix_metre_carré');
            $table->integer('nbr_valeur_terrain');
        
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_terrains');
    }
};
