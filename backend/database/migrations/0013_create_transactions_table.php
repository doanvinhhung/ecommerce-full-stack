<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('transaction_code')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // momo/cod/vnpay
            $table->string('status')->default('pending'); // pending/success/failed
            $table->string('momo_transaction_id')->nullable(); // ID giao dịch từ Momo
            $table->json('payment_info')->nullable(); // Thông tin thanh toán chi tiết
            $table->timestamps();

            // Tạm thời chỉ đánh index
            $table->index('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};