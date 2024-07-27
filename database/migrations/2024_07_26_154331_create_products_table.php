<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('QuantityAvailable');
            $table->integer('CategoryID');
            $table->integer('AdminID');
            $table->boolean('IsCustomizable');
            $table->boolean('HasNutritionalInfo');
            $table->string('image')->nullable(); // Add this line
            $table->string('vendor')->nullable(); // Add this line
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
