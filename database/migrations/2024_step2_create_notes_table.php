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
        Schema::create('notes', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('owner_id')->constrained('users');
            $table->string('path')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->string('note_key', 32)->nullable(); // Ajout de la colonne "note_key"

            // Supprimez cette ligne si vous utilisez l'id automatique
            // $table->primary('note_id');

            // Indice pour la colonne owner_id
            $table->index('owner_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
};
