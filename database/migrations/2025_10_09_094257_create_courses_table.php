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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')
            ->constrained('academic_years')
            ->onDelete('cascade');
            $table->unsignedSmallInteger('ordering')->default(1000);
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['academic_year_id', 'name']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
