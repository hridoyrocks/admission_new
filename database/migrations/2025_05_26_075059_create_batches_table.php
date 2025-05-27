<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->boolean('is_active')->default(false);
            $table->date('start_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};