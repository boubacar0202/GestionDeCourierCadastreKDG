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
        Schema::create('evaluations_batis', function (Blueprint $table) {
            $table->id();
            $table->string('slt_type_residence')->nullable();
            $table->string('rd_type_maissons')->nullable();
            $table->boolean('chk_bati_principal')->default(false);
            $table->string('slt_cat')->nullable();
            $table->integer('nbr_prix_metre_carre')->nullable();
            $table->integer('nbr_surface_bati_sol')->nullable();
            $table->integer('nbr_niveau')->nullable();
            $table->integer('nbr_surface_brute')->nullable();
            $table->integer('nbr_surface_utile')->nullable();
            $table->string('slt_coeff')->nullable();
            $table->integer('nbr_surface_corriger')->nullable();
            $table->integer('nbr_valeur')->nullable();
            $table->integer('txt_valeur_terrain_bati')->nullable();
        
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_batis');
    }
};
