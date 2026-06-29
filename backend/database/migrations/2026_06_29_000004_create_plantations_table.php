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
        Schema::create('plantations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crop_id')->constrained();
            $table->date('planted_at');
            $table->date('expected_harvest_at')->nullable();
            $table->date('harvested_at')->nullable();
            $table->decimal('area_ha', 8, 2)->nullable();
            $table->decimal('yield_kg', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantations');
    }
};
