<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>購物車</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    @include('layouts.navbar')
    <div>
        <h1 class="flex justify-center mt-10 text-5xl mb-10">發票訊息</h1>
    </div>
    <div class="flex items-center mx-auto justify-center">
        <div>
            <p class="text-2xl">商品名稱 : {{ $itemData[0] }}</p>
            <p class="text-2xl">商品數量 : {{ $itemData[1] }}{{ $itemData[2] }}</p>
            <p class="text-2xl">總金額 : {{ $itemData[3] }}</p>
        </div>
    </div>
</body>

</html>
