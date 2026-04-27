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
        Schema::create('receivedetail', function (Blueprint $table) {
            $table->id('id_receive_details');
            $table->foreignId('receive_id')->references('receive_id')->on('receive')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('teknisi_id')->nullable()->default(1)->references('teknisi_id')->on('teknisi')->onDelete('cascade');
            $table->text('product_name')->nullable();
            $table->text('serial_number')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('customer_company')->nullable();
            $table->decimal('price')->default(0);
            $table->enum('status', [
                'pending',
                'penawaran',
                'cancel',
                'selesai'
            ]);
            $table->boolean('has_customer')->default(true);            
            $table->boolean('has_garansi')->default(false);            
            $table->boolean('has_sj')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivedetail');
    }
};
