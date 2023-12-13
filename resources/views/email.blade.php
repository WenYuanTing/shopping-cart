<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mail</title>
</head>

<body>
    <h1>WenYT電商 - 商品資訊通知!!!</h1>
    <p>目前 {{ $item->name }} 下殺到 {{ $item->price }}! 數量只剩下{{ $item->quantity }}!! 還不趕快下單!!!</p>

</body>

</html>
