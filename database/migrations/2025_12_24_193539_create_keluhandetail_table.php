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
        Schema::create('keluhandetail', function (Blueprint $table) {
            $table->id('id_keluhan_detail');
            $table->foreignId('id_receive_details')
                ->references('id_receive_details')
                ->on('receivedetail')
                ->onDelete('cascade');

            $table->foreignId('receive_id')
                ->references('receive_id')
                ->on('receive')
                ->onDelete('cascade');

            $table->foreignId('keluhan_id')
                ->nullable()
                ->references('id')
                ->on('keluhan')
                ->onDelete('cascade');
            
            $table->text('keluhan')->nullable();
            $table->boolean('has_done')->default(false);
            $table->date('issue_at');
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhandetail');
    }
};
