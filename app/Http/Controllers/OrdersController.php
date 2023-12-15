<?php

namespace App\Http\Controllers;
use App\Models\Paid;
use App\Models\UserPaid;

use App\Models\UsersShoppingCart; 
use Illuminate\Http\Request;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;
use Ecpay\Sdk\Response\VerifiedArrayResponse;
use Ecpay\Sdk\Services\CheckMacValueService;

class OrdersController extends Controller
{
    public function createEcpayOrder(Request $request)
    {
        // ECPay 相關程式碼
        $factory = new Factory([
            'hashKey' => "5294y06JbISpM5x9",
            'hashIv' => "v77hoKGq4kWxNNIS",
        ]);        
        $userId=session('userId');
        $recipient = session('recipient');
        $contactNumber = session('contact_number');
        $shippingAddress = session('shipping_address');
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
            'CustomField1' =>$recipient,
            'CustomField2' =>$contactNumber,
            'CustomField3' =>$shippingAddress,
            'CustomField4' =>$userId,
            'ReturnURL' => 'https://d887-123-192-82-233.ngrok-free.app/ecpay/callback',
            'ClientBackURL' => route('orderList', ['id' => $userId]), // 返回商家網站按鈕

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

        $shoppingCartItems = UsersShoppingCart::where('user_id', $ecpayData['CustomField4'])
        ->select('name', 'price', 'quantity')
        ->get();
        $commonFields = [
            'user_id' => $ecpayData['CustomField4'],
            'MerchantTradeNo' => $ecpayData['MerchantTradeNo'],
            'PaymentDate' => $ecpayData['PaymentDate'],
            'TradeAmt' => $ecpayData['TradeAmt'],
            'TradeDate' => $ecpayData['TradeDate'],
            'RtnMsg' => $ecpayData['RtnMsg'],
            'Recipient' => $ecpayData['CustomField1'],
            'ContactNumber' => $ecpayData['CustomField2'],
            'ShippingAddress' => $ecpayData['CustomField3'],
        ];
        
        // 創建 Paid 實例並將每筆資料新增進去
        foreach ($shoppingCartItems as $item) {
            Paid::create(array_merge(
                $commonFields,
                [
                    // 添加購物車項目相關欄位
                    'ItemName' => $item->name,
                    'ItemPrice' => $item->price,
                    'ItemQuantity' => $item->quantity,
                ]
            ));
        }
        UsersShoppingCart::where('user_id', $ecpayData['CustomField4'])->delete();


        \Log::info('shoppingCartItems', $shoppingCartItems->toArray());

        return response('1|OK');
    }
}

// ECPay Callback Request: {
//     "MerchantTradeNo":"WenYT1702600942",
//     "PaymentDate":"2023/12/15 08:43:41",
//     "PaymentType":"Credit_CreditCard",
//     "RtnMsg":"交易成功",
//     "TradeAmt":"1119",
//     "TradeNo":"2312150842252399",
    //user_id
    //
// } 
