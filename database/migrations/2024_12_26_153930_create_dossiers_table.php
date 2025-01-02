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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('txt_num_dossier');
            $table->string('txt_num_dordre');
            $table->string('slt_service_rendu');
            $table->string('txt_etat_cession');
            $table->string('txt_cession_definitive');
            $table->date('dt_date_creation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
