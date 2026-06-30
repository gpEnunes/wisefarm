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
        Schema::create('crop_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('faostat_item_code')->nullable()->index();
            $table->text('description')->nullable();
            $table->decimal('optimal_temp_min', 5, 2)->nullable();
            $table->decimal('optimal_temp_max', 5, 2)->nullable();
            $table->decimal('ph_min', 4, 2)->nullable();
            $table->decimal('ph_max', 4, 2)->nullable();
            $table->integer('annual_rainfall_min')->nullable();
            $table->integer('annual_rainfall_max')->nullable();
            $table->enum('drought_tolerance', ['low', 'medium', 'high'])->nullable();
            $table->enum('frost_tolerance', ['low', 'medium', 'high'])->nullable();
            $table->timestamp('faostat_imported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_profiles');
    }
};
