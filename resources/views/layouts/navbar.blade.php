<nav class="flex bg-gray-500 p-2 justify-between items-center">
    <div class="flex items-center ml-4">
        <h1 class="text-white text-lg font-bold">WenYuanTing購物車</h1>
    </div>
    <div class="ml-auto space-x-4 flex">
        @auth
            <p class="text-lime-200	">{{ auth()->user()->name }}，歡迎回來~</p>
        @endauth

        <a class="text-white" href="{{ route('items.index') }}">首頁</a>
        @auth
            @if(auth()->user()->name==="溫沅庭")
                <a class="text-white" href="{{ route('items.create') }}">新增商品</a>
                <a class="text-white" href="">總訂單</a>
                <a class="text-white" href="{{ route('items.all') }}">所有商品</a>

            @else
                <a class="text-white"
                    href="{{ route('userShoppingCartItems', ['id' => auth()->user()->id]) }}">購物車
                    <span>{{ session('cartQuantity') }} </span></a>

            @endif
        @endauth
        @auth
            <a class="text-white" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">登出</a>
        @else
            <a class="text-white" href="{{ route('login') }}">登入</a>
            <a class="text-white" href="{{ route('register') }}">註冊帳號</a>
        @endauth
    </div>

</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
