<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numDossier');

            // Champs de la table
            $table->string('txt_lotissement');
            $table->string('txt_num_lotissement');
            $table->string('txt_num_section');
            $table->string('txt_num_parcelle');
            $table->string('txt_num_titre');
            $table->string('txt_titre_mere');
            $table->integer('nbr_surface');
            $table->string('slt_document_admin');
            $table->string('txt_num_deliberation');
            $table->date('dt_date_deliberation');
            $table->string('txt_nicad');

            // Clés étrangères (Relations)
            $table->foreignId('region_id')->nullable()->constrained('regions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('departement_id')->nullable()->constrained('departements')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('arrondissement_id')->nullable()->constrained('arrondissements')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('commune_id')->nullable()->constrained('communes')->onUpdate('cascade')->onDelete('cascade');
 
            // Clé étrangère pour dossiers
            $table->foreign('numDossier')->references('id')->on('dossiers')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('terrains');
    }
};
