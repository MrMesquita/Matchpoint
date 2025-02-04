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
        Schema::create('court_timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained('courts')->onDelete('cascade');
            $table->integer('day_of_week');
            $table->char('start_time', 5);
            $table->char('end_time', 5);
            $table->enum('status', ['available', 'busy'])->default('available');
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('court_timetables');
    }
};
