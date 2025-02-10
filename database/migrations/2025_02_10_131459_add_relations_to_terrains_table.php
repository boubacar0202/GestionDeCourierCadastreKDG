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
        Schema::table('terrains', function (Blueprint $table) {
            // Ajout des colonnes pour les relations
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('departement_id')->nullable();
            $table->unsignedBigInteger('arrondissement_id')->nullable();
            $table->unsignedBigInteger(column: 'commune_id')->nullable();
            $table->unsignedBigInteger(column: 'dossier_id')->nullable();



            // Ajout des clés étrangères pour les relations
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('departement_id')->references('id')->on('departements')->onDelete('cascade');
            $table->foreign('arrondissement_id')->references('id')->on('arrondissements')->onDelete('cascade');
            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('dossier_id')->references('id')->on('dosssiers')->onDelete('cascade');

        });
    }



    public function down(): void
    {
        
        Schema::table('terrains', function (Blueprint $table) {
            // Suppression des clés étrangères
            $table->dropForeign(['region_id']);
            $table->dropForeign(['departement_id']);
            $table->dropForeign(['arrondissement_id']);
            $table->dropForeign(['commune_id']);
            $table->dropForeign(['dossier_id']);

            // Suppression des colonnes
            $table->dropColumn(['region_id', 'departement_id', 'arrondissement_id', 'commune_id', 'dossier_id']);
       
        });
    }
};
