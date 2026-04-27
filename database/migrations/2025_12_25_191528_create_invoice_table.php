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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id('invoice_id');            
            $table->foreignId('receive_id')->references('receive_id')->on('receive')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers')->onDelete('cascade');            
            $table->enum('status_payment', [
                'unpaid',
                'partial',
                'full'
            ])->default('unpaid');
            $table->text('code');
            $table->text('customer_name')->nullable();
            $table->text('customer_company')->nullable();            
            $table->text('kode_toko')->nullable();
            $table->decimal('sub_total', 12, 2);
            $table->decimal('ppn', 12, 2);
            $table->decimal('grand_total',12,2);
            $table->decimal('jasa_service', 12, 2);
            $table->decimal('deposit', 12,2)->default(0);
            $table->boolean('has_sj')->default(false);
            $table->date('issue_at');
            $table->date('due_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
