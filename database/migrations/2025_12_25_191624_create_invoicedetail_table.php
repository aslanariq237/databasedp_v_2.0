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
        Schema::create('invoicedetail', function (Blueprint $table) {
            $table->id('id_invoice_detail');
            $table->foreignId('invoice_id')->references('invoice_id')->on('invoice')->onDelete('cascade');            
            $table->foreignId('id_receive_details')->references('id_receive_details')->on('receivedetail')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');            
            $table->text('product_name')->nullable();
            $table->text('serial_number')->nullable();            
            $table->enum('status', [
                'Selesai',
                'Ditolak',
                'Penawaran'                
            ])->default('selesai');
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicedetail');
    }
};
