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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('doctor_profile_id')->constrained('doctor_profiles')->cascadeOnDelete();
            $table->enum('weekday',['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
