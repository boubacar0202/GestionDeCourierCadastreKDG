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
            
            // Clés étrangères
            $table->string('txt_nicad');
            $table->string('txt_num_dossier');

            $table->date('txt_date_devaluation');
            $table->integer('txt_superficie_totale');
            $table->integer('txt_superficie_bati_sol');
            $table->string('slt_secteur');
            $table->integer('nbr_prix_metre_carre');
            $table->integer('nbr_valeur_terrain');


            $table->timestamps();

            // Déclaration des clés étrangères
            $table->foreign('txt_nicad')->references('txt_nicad')->on('terrains')->onDelete('cascade');
            $table->foreign('txt_num_dossier')->references('txt_num_dossier')->on('dossiers')->onDelete('cascade');

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
