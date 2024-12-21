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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->string('name', 255); 
            $table->text('description')->nullable(); 
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->enum('status', ['to-do', 'in progress', 'done'])->default('to-do'); 
            $table->date('due_date'); 
            $table->timestamps(); 

     
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};