<?php

namespace App\Http\Controllers;
use App\Models\Paid;
use App\Models\UserPaid;
use App\Models\User;
use App\Models\UsersShoppingCart; 
use Illuminate\Http\Request;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;
use Ecpay\Sdk\Response\VerifiedArrayResponse;
use Ecpay\Sdk\Services\CheckMacValueService;
use App\Mail\LaravelMail;
use Illuminate\Support\Facades\Mail;

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
            'ReturnURL' => 'https://7465-123-192-82-233.ngrok-free.app/ecpay/callback',
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

        if($ecpayData['RtnCode']==1){
            $shoppingCartItems = UsersShoppingCart::where('user_id', $ecpayData['CustomField4'])
            ->select('name', 'price', 'quantity')
            ->get();
    
        \Log::info('shoppingCartItems:', $shoppingCartItems->toArray());
        \Log::info('交易成功!!!');
        //-----------------------發票-------------------------
        $factory = new Factory([
            'hashKey' => 'ejCk326UnaZWKisg',
            'hashIv' => 'q9jcZX8Ib9LM8wYk',
        ]);
        $postService = $factory->create('PostWithAesJsonResponseService');

        $itemCount = 1;
        $itemAmount = $request->TradeAmt;
        $saleAmount = $request->TradeAmt;
        $data = [
            'MerchantID' => '2000132',
            'RelateNumber' => 'Test' . time(),
            'CustomerPhone' => $ecpayData['CustomField2'],
            'Print' => '0',
            'Donation' => '0',
            'CarrierType' => '1',
            'TaxType' => '1',
            'SalesAmount' => $saleAmount,
            'Items' => [
                [
                    'ItemName' => 'WenYT電商-服裝首飾',
                    'ItemCount' => $itemCount,
                    'ItemWord' => '個',
                    'ItemPrice' => $request->TradeAmt ,
                    'ItemTaxType' => '1',
                    'ItemAmount' => $itemAmount,
                ],
                
            ],
            'InvType' => '07'
        ];
        \Log::info('ECPay Data:', $data);

        $input = [
            'MerchantID' => '2000132',
            'RqHeader' => [
                'Timestamp' => time(),
                'Revision' => '3.0.0',
            ],
            'Data' => $data,
        ];
        $url = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/Issue';

        $response = $postService->post($input, $url);

        \Log::info('開立發票結果為: ', $response);

        $invoiceNo = $response['Data']['InvoiceNo'] ?? null;
        $invoiceDate = $response['Data']['InvoiceDate'] ?? null;
        $randomNumber = $response['Data']['RandomNumber'] ?? null;

        \Log::info('InvoiceNo: ' . $invoiceNo);
        \Log::info('InvoiceDate: ' . $invoiceDate);
        \Log::info('RandomNumber: ' . $randomNumber);
        //-----------------------發票-------------------------

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
            'invoiceNo'=>$invoiceNo,
            'invoiceDate'=>$invoiceDate,
            'randomNumber'=>$randomNumber,
        ];
        \Log::info('$commonFields', $commonFields);

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
        $user=User::find($ecpayData['CustomField4']);
        $userEmail=$user->email;
        $mail = new LaravelMail($commonFields);

// 发送邮件
Mail::to($userEmail)->send($mail->setCustomView('receipt')->with('commonFields', $commonFields));

       


        return response('1|OK');
        }

    }
    public function handleReceiptSearch(Request $request){
        $factory = new Factory([
            'hashKey' => 'ejCk326UnaZWKisg',
            'hashIv' => 'q9jcZX8Ib9LM8wYk',
        ]);
        $postService = $factory->create('PostWithAesJsonResponseService');
        
        $data = [
            'MerchantID' => '2000132',
            'InvoiceNo' => $request->input('invoiceNumber'),
            'InvoiceDate' => $request->input('invoiceDate'),
        ];
        $input = [
            'MerchantID' => '2000132',
            'RqHeader' => [
                'Timestamp' => time(),
                'Revision' => '3.0.0',
            ],
            'Data' => $data,
        ];
        $url = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/GetIssue';
        
        $response = $postService->post($input, $url);
        $items = $response['Data']['Items'][0];

        $itemName = $items['ItemName'];
        $itemCount = $items['ItemCount'];
        $itemWord = $items['ItemWord'];
        $itemPrice = $items['ItemPrice'];
        $itemData=[$itemName, $itemCount, $itemWord, $itemPrice];

        return view('receiptDetail',['itemData'=>$itemData]);

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


// [2023-12-15 09:09:28] local.INFO: 開立發票結果為:  
// {"Data":
//     {
//         "RtnCode":1,
//         "RtnMsg":"開立發票成功",
//         "InvoiceNo":"KH22054554",
//         "InvoiceDate":"2023-12-15 17:09:28",
//         "RandomNumber":"8702"
//     },
//     "MerchantID":2000132,
//     "PlatformID":0,
//     "RpHeader":
//     {
//         "Timestamp":1702631370,
//         "RqID":"fec5bbe9-2515-48c5-820f-16ebd4aebf07",
//         "Revision":"3.0.0"
//     },
//     "TransCode":1,
//     "TransMsg":
//     "Success"
// } 
