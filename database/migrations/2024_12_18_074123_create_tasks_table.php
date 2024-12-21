<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->string('google_event_id')->nullable();
            $table->string('access_token')->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable(); 
            $table->timestamps(); 

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['access_token', 'token_expires_at']);
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('google_event_id');
        });
    }
};