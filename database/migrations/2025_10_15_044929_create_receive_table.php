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
        Schema::create('receive', function (Blueprint $table) {
            $table->id('receive_id');
            $table->text('name');
            $table->string('code');            
            $table->enum('status_payment', [
                'unpaid',
                'partial',
                'full'
            ])->default('unpaid');
            $table->decimal('sub_total', 12, 2);
            $table->decimal('ppn', 12, 2);
            $table->decimal('grand_total', 12, 2);
            $table->decimal('deposit', 12, 2);
            $table->decimal('jasa_service', 12, 2);        
            $table->boolean('has_pajak_service')->default(true);
            $table->boolean('has_invoice')->default(false);
            $table->boolean('has_faktur')->default(false);
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
        Schema::dropIfExists('receive');
    }
};
