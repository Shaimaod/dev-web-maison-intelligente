<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('connected_objects', function (Blueprint $table) {
            // Champs pour l'éclairage
            $table->integer('brightness')->nullable()->comment('Intensité lumineuse en pourcentage');
            $table->string('color', 7)->nullable()->comment('Couleur en format hexadécimal');

            // Champs pour la sécurité
            $table->string('surveillance_mode')->nullable()->comment('Mode de surveillance');
            $table->integer('sensitivity')->nullable()->comment('Sensibilité de détection');

            // Champs pour l'audio
            $table->integer('volume')->nullable()->comment('Volume en pourcentage');
            $table->string('audio_source')->nullable()->comment('Source audio');
        });
    }

    public function down()
    {
        Schema::table('connected_objects', function (Blueprint $table) {
            $table->dropColumn([
                'brightness',
                'color',
                'surveillance_mode',
                'sensitivity',
                'volume',
                'audio_source'
            ]);
        });
    }
}; 