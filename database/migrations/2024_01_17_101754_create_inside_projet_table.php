<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsideProjetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inside_projet', function (Blueprint $table) {
            $table->id(); // Si vous souhaitez avoir une clé primaire automatique
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('projet_id');
            $table->integer('pos');
            // Indice pour la colonne projet_id
            $table->index('projet_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inside_projet');
    }
}
