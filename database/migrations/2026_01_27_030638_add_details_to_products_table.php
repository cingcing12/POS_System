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
    Schema::table('products', function (Blueprint $table) {
        // We check if column exists to prevent errors
        if (!Schema::hasColumn('products', 'category_id')) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        }
        if (!Schema::hasColumn('products', 'barcode')) {
            $table->string('barcode')->nullable()->unique();
        }
        if (!Schema::hasColumn('products', 'cost_price')) {
            $table->decimal('cost_price', 10, 2)->default(0);
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
