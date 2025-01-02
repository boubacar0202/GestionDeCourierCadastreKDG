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
        Schema::create('evaluations_amenagements', function (Blueprint $table) {
            $table->id();
            $table->string('txt_designation_am')->nullable();
            $table->string('nbr_valeur_unitaire_am')->nullable();
            $table->string('nbr_quantile_am')->nullable();
            $table->boolean('slt_coeficien_am')->default(false);
            $table->string('nbr_valeur_am')->nullable();
            $table->integer('nbr_valeur_totale_ap')->nullable();
        
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_amenagements');
    }
};
