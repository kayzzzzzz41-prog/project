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
        Schema::create('kaspi_payments', function (Blueprint $table) {
                $table->id();
                $table->string('txn_id')->unique();
                $table->string('prv_txn_id')->nullable();
                $table->string('account');
                $table->decimal('sum', 10, 2);
                $table->string('txn_date')->nullable();
                $table->integer('result')->default(0);
                $table->string('command');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaspi_payments');
    }
};
