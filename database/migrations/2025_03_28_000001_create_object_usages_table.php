<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('object_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_id')->constrained('connected_objects')->onDelete('cascade');
            $table->float('energy_consumption')->comment('Consommation énergétique en kWh');
            $table->integer('duration')->comment('Durée d\'utilisation en minutes');
            $table->string('status')->comment('État de l\'objet pendant l\'utilisation');
            $table->float('efficiency_score')->comment('Score d\'efficacité (0-100)');
            $table->boolean('maintenance_needed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('object_usages');
    }
}; 