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
        Schema::create('arrivees', function (Blueprint $table) {
            $table->id();
            $table->string('txt_numdordre');
            $table->index('txt_numdordre');  
            $table->string('txt_caractere')->nullable();
            $table->date('dt_datearrivee');
            $table->string('txt_numcourier');
            $table->date('dt_datecourier');
            $table->string('txt_reference');
            $table->string('txt_categorie');
            $table->string('txt_designation');
            $table->date('dt_date')->nullable();
            $table->string('txt_heure')->nullable();
            $table->string('txt_lieu')->nullable();
            $table->string('txt_nombrepiece');
            $table->string('txt_objet');
            $table->string('txt_expediteur');
            $table->string('txt_agenttraiteur');
            $table->string('txt_observation')->nullable(); 

            
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivees');
    }
};
