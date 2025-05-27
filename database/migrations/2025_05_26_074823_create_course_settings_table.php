<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_settings', function (Blueprint $table) {
            $table->id();
            $table->string('duration');
            $table->string('classes');
            $table->decimal('fee', 10, 2);
            $table->string('materials');
            $table->string('mock_tests');
            $table->text('additional_info')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('contact_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_settings');
    }
};