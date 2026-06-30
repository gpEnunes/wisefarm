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
        Schema::create('crop_soil_suitabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete();
            $table->enum('soil_type', ['clay', 'loam', 'sandy', 'silt']);
            $table->enum('suitability', ['ideal', 'suitable', 'marginal', 'unsuitable']);
            $table->unique(['crop_id', 'soil_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_soil_suitabilities');
    }
};
