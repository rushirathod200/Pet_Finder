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
        Schema::create('lost_pet_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usersdatas')->cascadeOnDelete();
            $table->string('pet_name');
            $table->string('city');
            $table->string('last_seen_area')->nullable();
            $table->text('description');
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index(['city', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_pet_requests');
    }
};
