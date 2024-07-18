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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable(); // Store multiple big product images or we can also manage another table where we can store type(Product,Category,Subcategory,Brand etc.) wise images
            $table->enum('status', ['publish', 'unpublish'])->default('publish');
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
