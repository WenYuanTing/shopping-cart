<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    @include('layouts.navbar')
    <div>
        <h1 class="flex justify-center mt-10 text-5xl	">當前所有商品</h1>
    </div>
    <div class="flex flex-wrap">
        @forelse($items as $item)
            <div class="w-1/4 p-20">
                <form action="/items/{{ $item->id }}/activate" method="POST">
                    @csrf
                    <h2>商品名稱 : {{ $item->name }}</h2>
                    <p>商品描述 : {{ $item->description }}</p>
                    <h3>商品價格 : {{ $item->price }}</h3>
                    <h3>目前庫存 : {{ $item->quantity }}</h3>
                    <a
                        href="{{ route('items.edit', ['id' => $item->id]) }}">更新資訊</a>
                    @if($item->is_active)
                        <button onclick="activateItem({{ $item->id }})">下架商品</button>
                    @else
                        <button onclick="activateItem({{ $item->id }})">上架商品</button>
                    @endif
                </form>
            </div>
        @empty
            <div>暫無商品</div>
        @endforelse
    </div>


    <script>
        function activateItem(itemId) {
            console.log(itemId)
            fetch('/items/' + itemId + '/activate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        itemId: itemId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // 處理回應，例如顯示成功訊息或重新載入商品列表等
                })
                .catch(error => {
                    console.error(error);
                    // 處理錯誤情況
                });
        }

    </script>


</body>

</html>
