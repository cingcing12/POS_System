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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('national_id')->nullable()->unique();
            
            // 🟢 UPDATED: Changed from 'string' to 'json' for Weekly Matrix
            $table->json('week_schedule')->nullable(); 
            
            $table->string('photo_url')->nullable(); // Profile Picture
            $table->string('nid_photo_url')->nullable(); // ID Card Scan
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 
                'dob', 
                'address', 
                'national_id', 
                'week_schedule', // 🟢 Make sure to drop the correct column name
                'photo_url', 
                'nid_photo_url'
            ]);
        });
    }
};