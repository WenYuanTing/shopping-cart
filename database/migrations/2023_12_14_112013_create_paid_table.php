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
        Schema::create('paid', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // 设置为可为空
            $table->string('MerchantTradeNo');
            $table->timestamp('PaymentDate')->nullable(); 
            $table->integer('TradeAmt');
            $table->timestamp('TradeDate')->nullable(); 
            $table->string('RtnMsg');
            $table->string('Recipient');
            $table->string('ContactNumber');
            $table->string('ShippingAddress');
            $table->string('ItemName');
            $table->integer('ItemPrice');
            $table->integer('ItemQuantity');
            $table->string('invoiceNo'); // 新增欄位
            $table->timestamp('invoiceDate')->nullable(); // 新增欄位
            $table->string('randomNumber'); // 新增欄位
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paid');
    }
};
