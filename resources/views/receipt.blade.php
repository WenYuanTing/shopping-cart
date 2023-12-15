<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mail</title>
</head>

<body>
    <h1>WenYT電商 - 付款通知</h1>
    <p>謝謝您的光臨，以下是您的發票資訊</p>
    <p>----------------------------------------------</p>
    <p>發票號碼 : {{ $commonFields['invoiceNo'] }}</p>
    <p>開立發票日期 : {{ $commonFields['invoiceDate'] }}</p>
    <p>隨機碼 : {{ $commonFields['randomNumber'] }}</p>
    <p>----------------------------------------------</p>



</body>

</html>
