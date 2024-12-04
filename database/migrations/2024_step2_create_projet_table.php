<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('Projet', function (Blueprint $table) {
            $table->boolean('is_finish')->default(0);
            $table->id();
            $table->string('name', 256)->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->string('type', 255)->default('none'); // Ajout de la colonne "type"
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projet');
    }
};
