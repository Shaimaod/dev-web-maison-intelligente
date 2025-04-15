<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                // Add other columns that existed before this migration
            });

            // Copy data from the original table to the temporary table
            DB::statement('INSERT INTO users_temp SELECT id, name, email, email_verified_at, password, remember_token, created_at, updated_at FROM users');

            // Drop the original table and rename the temporary table
            Schema::drop('users');
            Schema::rename('users_temp', 'users');
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['username']); // Drop the unique index on the username column
                $table->dropColumn('surname');
                $table->dropColumn('username');
                $table->dropColumn('gender');
                $table->dropColumn('birthdate');
                $table->dropColumn('member_type');
                $table->dropColumn('photo');
                $table->dropColumn('level');
                $table->dropColumn('points');
            });
        }
    }
};
