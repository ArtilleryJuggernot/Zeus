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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Match the SQL table structure
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('task_name', 100);
            $table->text('description')->default('');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->tinyInteger('is_finish')->default(0);
            $table->dateTime('finished_at')->nullable();
            $table->string('type', 255)->default('none');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
