<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('house_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('guest'); // owner, admin, guest
            $table->timestamps();
        });

        Schema::create('object_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_id')->constrained('connected_objects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('permissions')->nullable(); // permissions spécifiques pour cet objet
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('object_user');
        Schema::dropIfExists('house_user');
        Schema::dropIfExists('houses');
    }
}; 