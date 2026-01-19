<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'diet' values to 'deficit'
        DB::table('user_profiles')
            ->where('diet_goal', 'diet')
            ->update(['diet_goal' => 'deficit']);

        // Then modify the enum column if needed
        // This ensures the column definition is correct
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->enum('diet_goal', ['deficit', 'maintain', 'bulking'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back if needed
        DB::table('user_profiles')
            ->where('diet_goal', 'deficit')
            ->update(['diet_goal' => 'diet']);

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->enum('diet_goal', ['diet', 'maintain', 'bulking'])->change();
        });
    }
};
