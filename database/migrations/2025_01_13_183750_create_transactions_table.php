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
            $table->string('transaction_no', 9)->primary();
            $table->unsignedBigInteger('item_id');
            $table->string('user_id', 7);
            $table->integer('quantity');
            $table->date('date_of_usage');
            $table->date('date_of_return');
            $table->time('time_of_return');
            $table->enum('status', ['Pending', 'Confirmed', 'Released', 'Returned', 'Rejected', 'Cancelled']);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict')->onUpdate('cascade');
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
