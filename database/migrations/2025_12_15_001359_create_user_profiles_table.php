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
       Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // locked (diisi saat register)
        $table->enum('gender', ['male', 'female']);
        $table->date('date_of_birth');

        // editable
        $table->float('weight');
        $table->float('height');
        $table->enum('diet_goal', ['deficit', 'maintain', 'bulking']);

        // auto-calculated
        $table->integer('target_calories');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
