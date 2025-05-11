<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kiểm tra và thêm khóa ngoại cho orders
        if (Schema::hasTable('orders') && Schema::hasTable('users')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });
        }

        // Kiểm tra và thêm khóa ngoại cho order_product
        if (Schema::hasTable('order_product') && Schema::hasTable('orders') && Schema::hasTable('products')) {
            Schema::table('order_product', function (Blueprint $table) {
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');
                
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            });
        }

        // Kiểm tra và thêm khóa ngoại cho transactions
        if (Schema::hasTable('transactions') && Schema::hasTable('orders')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');
            });
        }

        // Thêm các khóa ngoại khác nếu cần...
    }

    public function down()
    {
        // Xóa khóa ngoại theo thứ tự ngược lại
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};