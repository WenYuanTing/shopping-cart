<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mail</title>
</head>

<body>
    <h1>WenYT電商 - 購物車商品資訊通知!!!</h1>
    <p>您購物車內的 {{ $item->name }} 目前價格下殺到 {{ $item->price }}! 數量只剩下{{ $item->quantity }}!! 趕快來結帳吧!!!</p>

</body>

</html>
