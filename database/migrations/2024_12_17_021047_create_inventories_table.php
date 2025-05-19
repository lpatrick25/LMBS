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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->string('inventory_number');
            $table->unsignedBigInteger('item_id');
            $table->integer('beginning_inventory');
            $table->integer('ending_inventory');
            $table->date('starting_period');
            $table->date('ending_period');
            $table->integer('total_borrowed');
            $table->integer('usable_qty');
            $table->integer('damaged_qty');
            $table->integer('lost_qty');
            $table->integer('repair_qty');
            $table->integer('disposal_qty');
            $table->enum('laboratory', ['HM Laboratory', 'Science Laboratory'])->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
