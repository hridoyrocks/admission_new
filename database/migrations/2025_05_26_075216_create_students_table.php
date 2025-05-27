<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->enum('course_type', ['academic', 'gt']);
            $table->enum('profession', ['student', 'job_holder', 'housewife']);
            $table->integer('score');
            $table->foreignId('class_session_id')->nullable()->constrained('class_sessions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

