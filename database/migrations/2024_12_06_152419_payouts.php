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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservation')->constrained('reservations'); 
            $table->decimal('amount', 8, 2); 
            $table->date('date'); 
            $table->string('method'); 
            $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])->default('PENDING'); 
            $table->timestamps();
            $table->softDeletes();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
