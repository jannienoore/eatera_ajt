<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('usda_fdc_id')->nullable()->index();
            $table->float('calories')->nullable();
            $table->float('protein')->nullable();
            $table->float('fat')->nullable();
            $table->float('carbohydrates')->nullable();
            $table->float('fiber')->nullable();
            $table->string('source')->default('usda');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
