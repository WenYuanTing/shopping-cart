<nav class="flex bg-gray-500 p-2 justify-between items-center">
    <div class="flex items-center ml-4">
        <h1 class="text-white text-lg font-bold">WenYuanTing購物車</h1>
    </div>
    <div class="ml-auto space-x-4">
        <a class="text-white" href="{{ route('items.index') }}">首頁</a>
        <a class="text-white" href="{{ route('items.create') }}">新增商品</a>
        <a class="text-white" href="">購物車</a>
        <a class="text-white" href="">總清單</a>
        <a class="text-white" href="{{ route('items.all') }}">所有商品</a>
    </div>
</nav>
