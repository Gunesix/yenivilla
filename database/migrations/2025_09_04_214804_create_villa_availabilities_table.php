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
        Schema::create('villa_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['available', 'blocked', 'booked'])->default('available');
            $table->text('notes')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable(); // Override default price for specific dates
            $table->integer('minimum_stay')->nullable(); // Override minimum stay for specific dates
            $table->timestamps();
            
            // Ensure unique date per villa
            $table->unique(['villa_id', 'date']);
            $table->index(['villa_id', 'date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villa_availabilities');
    }
};
