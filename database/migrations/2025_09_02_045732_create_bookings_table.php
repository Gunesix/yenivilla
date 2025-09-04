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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 20)->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();
            
            $table->index(['user_id']);
            $table->index(['villa_id']);
            $table->index(['check_in', 'check_out']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
