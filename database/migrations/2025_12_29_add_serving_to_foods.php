<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->float('serving_size')->nullable()->default(100)->comment('Serving size in grams');
            $table->string('serving_description')->nullable()->comment('e.g., 1 medium apple, 1 slice');
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn(['serving_size', 'serving_description']);
        });
    }
};
