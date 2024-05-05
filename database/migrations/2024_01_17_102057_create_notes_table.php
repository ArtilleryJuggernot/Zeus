<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
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
}
