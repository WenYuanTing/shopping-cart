<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>訂單</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    @include('layouts.navbar')
    <div>
        <h1 class="flex justify-center mt-10 text-5xl	">訂單頁面</h1>
    </div>
    <div class="flex flex-wrap">
        <?php
        $orderGroups = $paidDatas->groupBy('MerchantTradeNo');
        ?>

        @foreach($orderGroups as $orderNo => $orderItems)
            @if($orderGroups->isEmpty())
                <div>暫無訂單</div>
            @else
                <div class=" m-10 p-3 border-solid border-2 border-black ">

                    <!-- 在這裡放入您的程式碼 -->
                    <h2>訂單編號 : {{ $orderNo }}</h2>
                    <p>---------------------------------</p>

                    <p class="mb-4">訂單商品 : </p>

                    @foreach($orderItems as $paidData)
                        <!-- 每筆資料都顯示的數據 -->
                        <p> {{ $paidData->ItemName }} x {{ $paidData->ItemQuantity }} </p>
                    @endforeach

                    <!-- 總價格、交易狀況等其他訂單相關資訊 -->
                    <p class="mt-2">總價格 : {{ $orderItems->first()->TradeAmt }}</p>
                    <p>---------------------------------</p>
                    <p>交易狀況 : {{ $orderItems->first()->RtnMsg }} </p>
                    <p>付款日期 : {{ $orderItems->first()->PaymentDate }}</p>
                    <p>收件人 : {{ $orderItems->first()->Recipient }}</p>
                    <p>收件地址 : {{ $orderItems->first()->ShippingAddress }}</p>
                    <p>聯絡電話 : {{ $orderItems->first()->ContactNumber }}</p>
                </div>



            @endif

        @endforeach



    </div>
</body>

</html>
