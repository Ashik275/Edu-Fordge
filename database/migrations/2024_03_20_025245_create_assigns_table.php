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
        Schema::create('assigns', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->unsignedBigInteger('sub_id');
            $table->unsignedBigInteger('class_id');
            $table->time('time');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sub_id')->references('id')->on('subjects');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('teacher_id')->references('id')->on('teachers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigns');
    }
};
