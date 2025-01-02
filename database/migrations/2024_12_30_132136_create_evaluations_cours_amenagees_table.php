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
        Schema::create('evaluations_cours_amenagees', function (Blueprint $table) {
            $table->id();
            $table->string('chk_cours_amenager_totale')->nullable();
            $table->string('nbr_surface_ca_total')->nullable();
            $table->string('slt_categorie_ca_total')->nullable();
            $table->boolean('nbr_prix_metre_carre_ca_total')->default(false);
            $table->string('nbr_coefficient_ca_total')->nullable();
            $table->integer('nbr_valeur_ca_total')->nullable();
            $table->integer('nbr_valeur_total_cours')->nullable();
        
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_cours_amenagees');
    }
};
