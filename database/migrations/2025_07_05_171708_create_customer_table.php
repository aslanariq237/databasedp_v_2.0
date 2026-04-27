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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id');
            $table->text('name');
            $table->text('code');
            $table->text('branch')->nullable();
            $table->text('company');
            $table->boolean('category')->default(true);
            //boolean 1 is for  reguler and 0 is for franchise;
            $table->boolean('is_open')->default(true);
            $table->text('pic')->nullable();
            $table->integer('telp')->default(0);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
