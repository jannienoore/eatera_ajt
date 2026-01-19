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
        Schema::create('food_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_id')->constrained('foods')->cascadeOnDelete();
            $table->float('quantity')->default(1);
            $table->enum('meal_type', [
            'breakfast',
            'lunch',
            'dinner',
            'snack'
            ]);
            $table->date('eaten_at');
            $table->timestamps();
            $table->index(['user_id', 'eaten_at', 'meal_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_journals');
    }
};
