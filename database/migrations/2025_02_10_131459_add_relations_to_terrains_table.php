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
            $table->unsignedBigInteger('slt_region')->nullable();
            $table->unsignedBigInteger('slt_departement')->nullable();
            $table->unsignedBigInteger('slt_arrondissement')->nullable();
            $table->unsignedBigInteger(column: 'slt_commune')->nullable();
            $table->unsignedBigInteger(column: 'txt_num_dossier')->nullable();



            // Ajout des clés étrangères pour les relations
            $table->foreign('slt_region')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('slt_departement')->references('id')->on('departements')->onDelete('cascade');
            $table->foreign('slt_arrondissement')->references('id')->on('arrondissements')->onDelete('cascade');
            $table->foreign('slt_commune')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('txt_num_dossier')->references('id')->on('dosssiers')->onDelete('cascade');

        });
    }



    public function down(): void
    {
        
        Schema::table('terrains', function (Blueprint $table) {
            // Suppression des clés étrangères
            $table->dropForeign(['slt_region']);
            $table->dropForeign(['slt_departement']);
            $table->dropForeign(['slt_arrondissement']);
            $table->dropForeign(['slt_commune']);
            $table->dropForeign(['txt_num_dossier']);

            // Suppression des colonnes
            $table->dropColumn(['slt_region', 'slt_departement', 'slt_arrondissement', 'slt_commune', 'txt_num_dossier']);
       
        });
    }
};
