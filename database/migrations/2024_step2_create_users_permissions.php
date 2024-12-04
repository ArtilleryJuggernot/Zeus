<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id('permission_id'); // auto_increment primary key
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key to users
            $table->string('resource_type', 10)->nullable(); // Resource type
            $table->integer('resource_id')->nullable(); // Resource ID
            $table->string('permission_type', 15)->nullable(); // Permission type
            $table->timestamps(); // Optional: adds created_at and updated_at
        });

        Schema::table('user_permissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
