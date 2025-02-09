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
            
            // Champs de la table
            $table->string('txt_lotissement')->nullable()->change();
            $table->string('txt_num_lotissement')->nullable()->change();
            $table->string('txt_num_section')->nullable()->change();
            $table->string('txt_num_parcelle')->nullable()->change();
            $table->string('txt_num_titre')->nullable()->change();
            $table->string('txt_titre_mere')->nullable()->change();
            $table->decimal('nbr_surface', 10, 2)->nullable()->change();
            $table->string('slt_document_admin')->nullable()->change();
            $table->string('txt_num_deliberation')->nullable()->change();
            $table->date('dt_date_deliberation')->nullable()->change();
            $table->string('txt_nicad')->nullable()->change();

            // Clés étrangères (Relations)
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('departement_id')->constrained()->onDelete('cascade');
            $table->foreignId('arrondissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('commune_id')->constrained()->onDelete('cascade');
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            
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
