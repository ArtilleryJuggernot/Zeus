<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habit_possede', function (Blueprint $table) {
            $table->integer('habit_id');
            $table->integer('day_id');
            $table->time('start')->nullable();
            $table->time('stop')->nullable();

            $table->primary(['habit_id', 'day_id']);
            // Optionnel : Ajout de clés étrangères si tu veux l'intégrité référentielle
            // $table->foreign('habit_id')->references('id')->on('habitude')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('habit_possede');
    }
};