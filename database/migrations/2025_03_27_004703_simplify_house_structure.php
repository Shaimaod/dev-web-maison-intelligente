<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer les tables existantes liées aux maisons
        Schema::dropIfExists('house_user');
        Schema::dropIfExists('houses');

        // Créer une seule table pour la maison
        Schema::create('house', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Mettre à jour la clé étrangère house_id des objets connectés
        Schema::table('connected_objects', function (Blueprint $table) {
            $table->dropForeign(['house_id']);
            $table->foreign('house_id')->references('id')->on('house')->onDelete('cascade');
        });

        // Ajouter une colonne house_id aux utilisateurs
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('house_id')->nullable()->constrained('house')->onDelete('cascade');
            $table->string('house_role')->default('guest'); // owner, admin, guest
        });
    }

    public function down()
    {
        // Supprimer les colonnes ajoutées
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['house_id']);
            $table->dropColumn(['house_id', 'house_role']);
        });

        // Mettre à jour la clé étrangère house_id des objets connectés
        Schema::table('connected_objects', function (Blueprint $table) {
            $table->dropForeign(['house_id']);
            $table->foreign('house_id')->references('id')->on('houses')->onDelete('cascade');
        });

        // Supprimer la table house
        Schema::dropIfExists('house');

        // Recréer les tables originales
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('house_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('guest');
            $table->timestamps();
        });
    }
}; 