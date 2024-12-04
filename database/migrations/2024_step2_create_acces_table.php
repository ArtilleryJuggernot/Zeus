<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Acces', function (Blueprint $table) {
            $table->unsignedBigInteger('ressource_id');
            $table->enum('type', ['note', 'folder', 'task', 'project']);
            $table->enum('perm', ['RO', 'RW', 'F'])->nullable();
            $table->unsignedBigInteger('dest_id');

            // Définir la clé primaire composite
            $table->primary(['ressource_id', 'type', 'dest_id']);

            // Clé étrangère
            $table->foreign('dest_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Acces');
    }
};


