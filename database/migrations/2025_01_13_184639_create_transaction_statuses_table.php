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
        Schema::create('transaction_statuses', function (Blueprint $table) {
            $table->id('transaction_status_id');
            $table->string('transaction_no', 9);
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->enum('status', ['Okay', 'Lost', 'Damaged', 'For Repair', 'For Disposal']);
            $table->timestamps();

            $table->foreign('transaction_no')->references('transaction_no')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_statuses');
    }
};
