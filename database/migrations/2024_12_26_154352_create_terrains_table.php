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
            $table->string('txt_lotissement')->nullable();
            $table->string('txt_num_lotissement')->nullable();
            $table->string('txt_num_section')->nullable();
            $table->string('txt_num_parcelle')->nullable();
            $table->string('txt_num_titre')->nullable();
            $table->string('txt_titre_mere')->nullable();
            $table->decimal('nbr_surface', 10, 2)->nullable();
            $table->string('slt_document_admin')->nullable();
            $table->string('txt_num_deliberation')->nullable();
            $table->date('dt_date_deliberation')->nullable();
            $table->string('txt_nicad')->nullable()->unique();

            // Clés étrangères (Relations)
            $table->foreignId('region_id')->constrained('regions', 'id')->onDelete('cascade');
            $table->foreignId('departement_id')->constrained('departements', 'id')->onDelete('cascade');
            $table->foreignId('arrondissement_id')->constrained('arrondissements', 'id')->onDelete('cascade');
            $table->foreignId('commune_id')->constrained('communes', 'id')->onDelete('cascade');
            $table->foreignId('dossier_id')->constrained('dossiers', 'id')->onDelete('cascade');

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
