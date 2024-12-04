<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possede_categorie', function (Blueprint $table) {
            $table->id();
            $table->integer('ressource_id')->nullable(); // Corrigé pour permettre NULL
            $table->enum('type_ressource', ['folder', 'note', 'task', 'project'])->nullable();
            $table->unsignedBigInteger('categorie_id')->index();
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('possede_categorie');
    }
};
