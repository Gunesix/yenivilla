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
        Schema::create('villa_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->string('amenity_type'); // pool, wifi, parking, kitchen, etc.
            $table->string('amenity_value')->nullable(); // additional details if needed
            $table->timestamps();
            
            $table->index(['villa_id']);
            $table->index(['amenity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villa_amenities');
    }
};
