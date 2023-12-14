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
        <h1 class="flex justify-center mt-10 text-5xl	">商店首頁</h1>
    </div>
    <div class="flex flex-wrap">
        @forelse($items as $item)
            @if($item->is_active)
                <div class="w-1/4 p-20">
                    <h2>商品名稱 : {{ $item->name }}</h2>
                    <p>商品描述 : {{ $item->description }}</p>
                    <h3>商品價格 : {{ $item->price }}</h3>
                    <h3 class="mb-4">目前庫存 : {{ $item->quantity }}</h3>
                    @auth
                        @if(auth()->user()->name!="溫沅庭")
                            <a href="{{ route('itemAddToCart', $item->id) }}">[
                                加入購物車 ]</a>


                        @endif

                    @else
                        <a href="{{ route('login') }}">登入以加入購物車</a>
                    @endauth
                </div>

            @endif

        @empty
            <div>暫無商品</div>
        @endforelse

    </div>
</body>

</html>
