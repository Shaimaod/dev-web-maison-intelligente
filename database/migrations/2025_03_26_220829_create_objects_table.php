<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectsTable extends Migration
{
    public function up()
    {
        Schema::create('connected_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->string('room')->nullable();               // Pièce où se trouve l'objet
            $table->string('brand')->nullable();              // Marque (ex : Phillips)
            $table->string('type')->nullable();               // Type d'objet (caméra, thermostat...)
            $table->string('status')->default('Inactif');     // Actif / Inactif
            $table->string('connectivity')->nullable();       // Wifi / Bluetooth
            $table->string('battery')->nullable();            // Pourcentage batterie
            $table->string('mode')->nullable();               // Automatique / Manuel
            $table->string('current_temp')->nullable();       // Température actuelle (si pertinent)
            $table->string('target_temp')->nullable();        // Température cible (si pertinent)
            $table->string('last_interaction')->nullable();   // Dernière interaction
            $table->json('settings')->nullable();             // Paramètres spécifiques à l'objet
            $table->json('schedule')->nullable();             // Programmation horaire
            $table->boolean('is_automated')->default(false);  // Si l'objet peut être automatisé
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('connected_objects');
    }
}
