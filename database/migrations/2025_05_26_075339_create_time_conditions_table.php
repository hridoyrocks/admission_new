<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('profession');
            $table->boolean('is_fixed')->default(true);
            $table->string('fixed_time')->nullable();
            $table->json('score_rules')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_conditions');
    }
};
