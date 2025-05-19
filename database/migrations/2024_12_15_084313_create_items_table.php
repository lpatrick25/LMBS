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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('item_name', 50);
            $table->unsignedBigInteger('category_id');
            $table->integer('beginning_qty');
            $table->integer('current_qty');
            $table->enum('laboratory', ['HM Laboratory', 'Science Laboratory'])->nullable();
            $table->text('description');
            $table->string('image', 50)->default('dist/img/default.jpg');
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
