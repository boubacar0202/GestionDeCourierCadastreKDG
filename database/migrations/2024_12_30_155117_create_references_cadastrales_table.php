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
        Schema::create('references_cadastrales', function (Blueprint $table) {
            $table->id();
            $table->boolean('rd_immatricalation_terrain');
            $table->string('slt_dependant_domaine');
            $table->string('slt_bornage');
            $table->string('ussu_bornage');
            $table->string('txt_titre_mere');
            $table->string('slt_lf');
            $table->string('txt_num_requisition');
            $table->string('txt_surface_bornage');
            $table->string('dt_date_bornage');
            $table->string('txt_nom_geometre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references_cadastrales');
    }
};
