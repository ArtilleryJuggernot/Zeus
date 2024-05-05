<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePossedeCategorieTable extends Migration
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
            $table->foreignId('ressource_id');
            $table->enum('type_ressource', ['folder', 'note', 'task', 'project'])->nullable();
            $table->foreignId('categorie_id')->index(); // Assurez-vous d'avoir un index sur la colonne
            $table->foreignId('owner_id');
            $table->timestamps();

            //$table->foreign('categorie_id')->references('category_id')->on('categories')->onDelete('cascade');
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
}
