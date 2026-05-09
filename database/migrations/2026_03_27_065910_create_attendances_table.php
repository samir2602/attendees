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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();            
            $table->date('date')->nullable();            
            $table->time('work_in')->nullable();
            $table->time('work_out')->nullable();
            $table->integer('break_hours')->nullable();
            $table->integer('working_hours')->nullable();            
            $table->string('over_time')->nullable();
            $table->string('under_time')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
