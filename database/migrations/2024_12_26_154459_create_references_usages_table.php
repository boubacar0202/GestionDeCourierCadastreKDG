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
        Schema::create('references_usages', function (Blueprint $table) {
            $table->id();
            $table->string('slt_reference_usage');
            $table->string('txt_occupan_habitaion_1')->nullable();
            $table->string('txt_activite_principal_hbt_1')->nullable();
            $table->string('txt_ninea_occupan_hbt_1')->nullable();
            $table->string('tel_tel_occupant_hbt_1')->nullable();
            $table->integer('nbr_montant_loyer_hbt_1')->nullable();
            $table->string('txt_activite_commercial')->nullable();
            $table->string('txt_occopan_commercial')->nullable();
            $table->string('txt_activite_industriel')->nullable();
            $table->string('txt_occopan_industriel')->nullable();
            $table->string('txt_activite_agricole')->nullable();
            $table->string('txt_occopan_agricole')->nullable();
            $table->string('txt_activite_professionnelle')->nullable();
            $table->string('txt_occopan_professionnelle')->nullable();
            $table->string('txt_activite_culte')->nullable();
            $table->string('txt_occopan_culte')->nullable();
            $table->string('txt_Activite_administratif')->nullable();
            $table->string('txt_occupan_administratif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references_usages');
    }
};
