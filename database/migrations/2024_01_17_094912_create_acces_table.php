<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesTable extends Migration
{
    public function up()
    {
        Schema::create('Acces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ressource_id');
            $table->enum('type', ['note', 'folder', 'task', 'project']);
            $table->enum('perm', ['RO', 'RW', 'F'])->nullable();
            $table->unsignedBigInteger('dest_id');
            $table->foreign('dest_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('acces');
    }
}
