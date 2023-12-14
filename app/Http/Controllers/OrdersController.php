<?php

namespace App\Http\Controllers;

use App\Models\UsersShoppingCart; 
use Illuminate\Http\Request;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;
use Ecpay\Sdk\Response\VerifiedArrayResponse;
use Ecpay\Sdk\Services\CheckMacValueService;

class OrdersController extends Controller
{
    public function createEcpayOrder()
    {
        // ECPay 相關程式碼
        $factory = new Factory([
            'hashKey' => env('ECPAY_HASH_KEY'),
            'hashIv' => env('ECPAY_HASH_IV'),
        ]);
        $amount = session('amount');
        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');
        
        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'WenYT' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $amount,
            'TradeDesc' => '正常交易',
            'ItemName' => 'WenYT電商-服裝首飾',
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,

            // 請參考 example/Payment/GetCheckoutResponse.php 範例開發
            'ReturnURL' => 'https://9137-123-192-82-233.ngrok-free.app/ecpay/callback',

            'ClientBackURL' => route('items.index'), // 返回商家網站按鈕

        ];
        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

        // 生成支付連結
        $paymentFormHtml = $autoSubmitFormService->generate($input, $action);

        // 將 HTML 顯示給使用者，你可以選擇返回到視圖或其他方式呈現
        return view('orders.create', compact('paymentFormHtml'));
    }

    public function ecpayCallback(Request $request)
    {

        // 取得 ECPay 回傳的所有資料
        $ecpayData = $request->all();
        \Log::info('ECPay Callback Request:', $ecpayData);

        return response('1|OK');
    
    }
// public function ecpayCallback(Request $request)
// {

//     // 取得 ECPay 回傳的所有資料
//     $ecpayData = $request->all();
//     \Log::info('ECPay Callback Request:', $ecpayData);

//     // 在這裡設定你的 HashKey、HashIV，以及使用的 Hash 方法
//     $hashKey = env('ECPAY_HASH_KEY');
//     $hashIv = env('ECPAY_HASH_IV');

//     $sortInput="ChoosePayment=Credit&EncryptType=1&ItemName=WenYT電商-服裝首飾&MerchantID=2000132&MerchantTradeDate=".$request->PaymentDate."&MerchantTradeNo=".$request->MerchantTradeNo."&PaymentType=aio&ReturnURL=https://9137-123-192-82-233.ngrok-free.app/ecpay/callback&TotalAmount=".$request->TradeAmt."&TradeDesc=正常交易";


//     \Log::info('最終排序與調整後的參數字串:'.$sortInput );

//     // 加上 HashKey 與 HashIV
//     $rawData = "HashKey={$hashKey}&{$sortInput}&HashIV={$hashIv}";
//     \Log::info('加上Hash最終排序與調整後的參數字串:'.$rawData );

//     // 進行 URL encode
//     $urlEncodedData = urlencode($rawData);
//     \Log::info('進行 URL encode後 : '.$urlEncodedData );

//     // 轉為小寫
//     $lowercaseData = strtolower($urlEncodedData);

//     // 以 SHA256 加密方式來產生雜凑值
//     $checkMacValue = hash('sha256', $lowercaseData);

//     // 將 CheckMacValue 轉為大寫
//     $checkMacValue = strtoupper($checkMacValue);
//     $checkMacValueFromEcpay = isset($ecpayData['CheckMacValue']) ? $ecpayData['CheckMacValue'] : null;

//     \Log::info('Calculated CheckMacValue : '.$checkMacValue);
//     \Log::info('FromECPay : ' .$checkMacValueFromEcpay);

//     // 驗證 CheckMacValue
//     if ($checkMacValueFromEcpay==$checkMacValue) {
//         // CheckMacValue 驗證成功，處理支付成功的邏輯
//         // ...
//         return response('1|OK');
//     } else {
//         // CheckMacValue 驗證失敗，可能有安全風險，處理錯誤邏輯
//         // ...
//         return response('CheckMacValue 驗證失敗', 400);
//     }
// }



}
