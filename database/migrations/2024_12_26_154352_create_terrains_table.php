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
        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->string('txt_lotissement');
            $table->string('txt_num_lotissement');
            $table->string('txt_num_section');
            $table->string('txt_num_parcelle');
            $table->string('txt_num_titre');
            $table->integer('nbr_surface');
            $table->string('slt_document_admin');
            $table->string('txt_num_deliberation');
            $table->date('dt_date_deliberation');
            $table->string('txt_nicad');
        
            // Foreign keys for location hierarchy
            $table->foreignId('localite_id')->constrained('localites')->onDelete('cascade');
        
            // Foreign key for dossier
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terrains');
    }
};
