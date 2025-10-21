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
        Schema::create('regulation_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')
                ->constrained('regulations')
                ->cascadeOnDelete();
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['regulation_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulation_roles');
    }
};
