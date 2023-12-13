<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mail</title>
</head>

<body>
    <h1>WenYT電商 - 新品上架資訊通知!!!</h1>
    <p>WenYT電商現在推出了 新品 : {{ $item->name }} 啦!! 目前優惠價格為 : {{ $item->price }}! 數量還有 {{ $item->quantity }}!! 要買要快阿!!!
    </p>

</body>

</html>
