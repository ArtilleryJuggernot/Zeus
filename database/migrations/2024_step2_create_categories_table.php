<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('category_name', 50);
            $table->string('color', 15)->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
