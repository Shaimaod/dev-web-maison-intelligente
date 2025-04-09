<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameObjectsToConnectedObjects extends Migration
{
    public function up()
    {
        Schema::rename('objects', 'connected_objects');
    }

    public function down()
    {
        Schema::rename('connected_objects', 'objects');
    }
}

