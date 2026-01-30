<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            // Add the missing column
            $table->decimal('unit_price', 10, 2)->after('qty')->default(0);
        });
    }

    public function down()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};