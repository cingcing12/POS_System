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
        // Foreign Keys (Assuming you have categories/suppliers tables)
        $table->foreignId('category_id')->nullable(); 
        $table->foreignId('supplier_id')->nullable();
        
        $table->string('barcode')->unique()->nullable();
        $table->decimal('cost_price', 10, 2)->default(0);
        $table->decimal('sale_price', 10, 2);
        $table->integer('qty')->default(0);
        $table->string('image_url')->nullable(); // Stores Cloudinary Link
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
