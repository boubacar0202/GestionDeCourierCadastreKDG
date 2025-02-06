<?php

use App\Models\Region;
use App\Models\Terrain;
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
            $table->string('numDossier')->unique();
            $table->integer('numDordre');
            $table->string('serviceRendu');
            $table->string('etatCession');
            $table->string('cessionDefinitive');
            $table->date('dateCreation');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('numDossier');
        });
    }

};
