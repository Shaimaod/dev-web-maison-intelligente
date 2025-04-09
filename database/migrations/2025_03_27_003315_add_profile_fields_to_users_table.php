<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable();            // Prénom
            $table->string('username')->unique()->nullable(); // Pseudonyme (login)
            $table->string('gender')->nullable();             // Sexe / Genre
            $table->date('birthdate')->nullable();            // Date de naissance
            $table->string('member_type')->nullable();        // Type de membre
            $table->string('photo')->nullable();              // Photo de profil (path vers le fichier)
            $table->string('level')->default('débutant');     // Niveau utilisateur
            $table->float('points')->default(0);              // Points accumulés
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'username',
                'gender',
                'birthdate',
                'member_type',
                'photo',
                'level',
                'points',
            ]);
        });
    }
};
