<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tạo mới bảng 'orders'
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_code')->unique();
            $table->unsignedBigInteger('coupon_id')->nullable(); // Thêm cột coupon_id
            $table->string('coupon_code')->nullable()->comment('Mã coupon để tra cứu nhanh');
            $table->json('billing_info');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('payment_method')->default('cod'); // cod/momo/vnpay
            $table->string('payment_status')->default('pending'); // pending/paid/failed
            $table->string('momo_transaction_id')->nullable(); // Thêm trường cho Momo
            $table->string('status')->default('pending'); // pending/processing/completed/cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            // Tạo chỉ mục cho trường user_id
            $table->index('user_id');
        });
    }

    public function down()
    {
        // Xóa bảng 'orders' khi rollback
        Schema::dropIfExists('orders');
    }
};
