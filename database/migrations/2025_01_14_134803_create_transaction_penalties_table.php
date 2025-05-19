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
        Schema::create('transaction_penalties', function (Blueprint $table) {
            $table->id('transaction_penalty_id');
            $table->string('transaction_no', 9);
            $table->unsignedBigInteger('item_id');
            $table->string('user_id', 7);
            $table->integer('quantity');
            $table->decimal('amount', 10,2)->default(0.0);
            $table->enum('status', ['Lost', 'Damaged']);
            $table->enum('remarks', ['Replace', 'Pay']);
            $table->timestamps();

            $table->foreign('transaction_no')->references('transaction_no')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_penalties');
    }
};
