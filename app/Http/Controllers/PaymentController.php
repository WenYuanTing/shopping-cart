<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ECPay\ECPay_AllInOne;

class PaymentController extends Controller
{
    public function checkout()
    {
        // 使用 ECPay SDK 創建付款請求
        $obj = new ECPay_AllInOne();
        $obj->ServiceURL = 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5'; // 綠界付款服務 URL
        $obj->HashKey = env('ECPAY_HASH_KEY');
        $obj->HashIV = env('ECPAY_HASH_IV');
        $obj->MerchantID = env('ECPAY_MERCHANT_ID');

        // 設定付款資訊等...

        // 生成支付連結
        $html = $obj->CheckOut();

        // 將 HTML 顯示給使用者
        return view('checkout', compact('html'));
    }
}
