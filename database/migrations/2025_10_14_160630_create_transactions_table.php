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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');

            $table->foreignId('transaction_item_id')
                ->nullable() // cho phép null
                ->constrained('transaction_items')
                ->onDelete('set null');

            $table->text('description')->nullable();
            $table->enum('type', ['income', 'expense']);
            $table->unsignedBigInteger('amount');
            $table->string('file_name')->nullable();
            $table->string('in_charge')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
