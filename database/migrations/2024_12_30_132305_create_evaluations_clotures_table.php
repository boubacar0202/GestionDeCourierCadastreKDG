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
        Schema::create('evaluations_clotures', function (Blueprint $table) {
            $table->id();
            $table->string('chk_perimetre_cloture')->nullable();
            $table->string('nbr_longueur_avant_clo')->nullable();
            $table->string('slt_categorie_clo')->nullable();
            $table->boolean('nbr_prix_metre_carre_clo')->default(false);
            $table->string('nbr_coefficient_clo')->nullable();
            $table->integer('nbr_valeur_clo')->nullable();
            $table->integer('nbr_valeur_total_clotur')->nullable();
        
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_clotures');
    }
};
