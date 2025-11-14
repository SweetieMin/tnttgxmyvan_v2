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
        Schema::create('pusher_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_id');
            $table->string('key');
            $table->string('secret');
            $table->string('cluster');
            $table->integer('port')->default(443);
            $table->string('scheme')->default('https');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pusher_settings');
    }
};
