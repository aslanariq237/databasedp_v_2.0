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
        Schema::create('detailsuratjalan', function (Blueprint $table) {
            $table->id('id_surat_jalan');
            $table->foreignId('suratjalan_id')
                ->references('suratjalan_id')
                ->on('suratjalan')
                ->onDelete('cascade');

            $table->foreignId('invoice_id')
                ->references('invoice_id')
                ->on('invoice')
                ->onDelete('cascade');

            $table->foreignId('id_invoice_detail')
                ->references('id_invoice_detail')
                ->on('invoicedetail')
                ->onDelete('cascade');
                
            $table->foreignId('id_receive_details')
                ->references('id_receive_details')
                ->on('receivedetail')
                ->onDelete('cascade');                            

            $table->foreignId('customer_id')
                ->nullable()
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
                
            $table->text('product_name')->nullable();
            $table->text('serial_number')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailsuratjalan');
    }
};
