<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;

class OrdersController extends Controller
{
    public function createEcpayOrder()
    {
        // ECPay 相關程式碼
        $factory = new Factory([
            'hashKey' => env('ECPAY_HASH_KEY'),
            'hashIv' => env('ECPAY_HASH_IV'),
        ]);
        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');

        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => 100,
            'TradeDesc' => UrlService::ecpayUrlEncode('交易描述範例'),
            'ItemName' => '範例商品一批 100 TWD x 1',
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,

            // 請參考 example/Payment/GetCheckoutResponse.php 範例開發
            'ReturnURL' => 'https://www.ecpay.com.tw/example/receive',
        ];
        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

        // 生成支付連結
        $paymentFormHtml = $autoSubmitFormService->generate($input, $action);

        // 將 HTML 顯示給使用者，你可以選擇返回到視圖或其他方式呈現
        return view('orders.create', compact('paymentFormHtml'));
    }
}
