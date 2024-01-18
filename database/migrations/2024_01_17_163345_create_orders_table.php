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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedFloat('purchased_amount')->default(0);
            $table->unsignedFloat('exchange_rate', 14, 6)->default(0);
            $table->unsignedFloat('paid_amount')->default(0);
            $table->unsignedFloat('surcharge_percentage')->default(0);
            $table->unsignedFloat('surcharge_amount')->default(0);
            $table->unsignedFloat('discount_percentage')->default(0);
            $table->unsignedFloat('discount_amount')->default(0);
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
