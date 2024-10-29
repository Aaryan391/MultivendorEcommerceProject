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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('order_status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('shipping_status')->default('not_shipped')->after('payment_status');
            $table->string('order_payment_type')->default('cod');
            $table->double('order_total')->default(0);
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_phone_number');
            $table->string('customer_town_city');
            $table->string('customer_note')->nullable();
            $table->string('customer_company')->nullable();
            $table->timestamps();
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
