<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新商品</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    @include('layouts.navbar')
    <div>
        <h1 class="flex justify-center mt-10 text-5xl	">更新商品</h1>
    </div>
    <div class="flex justify-center items-center mt-20">

        <form method="POST"
            action="{{ route('items.update',['id'=>$item->id]) }}"
            class="w-1/2">
            @csrf
            @method('PUT')
            <div>
                <label for="name">商品名稱 : </label>
                <input type="text" name="name" id="name" value="{{ $item->name }}"
                    class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none">
            </div>

            <div>
                <label for="description">商品描述 : </label>
                <textarea name="description" id="description" rows="5" placeholder="介紹一下商品.."
                    class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none"> {{ $item->description }}</textarea>
            </div>

            <div>
                <label for="price">商品價格 : </label>
                <input type="number" name="price" id="price" value="{{ $item->price }}"
                    class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none">
            </div>
            <div>
                <label for="quantity">商品庫存 : </label>
                <input type="number" name="quantity" id="quantity" value="{{ $item->quantity }}"
                    class="shadow-sm appearance-none border w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none">
            </div>

            <div>
                <label for="push_notification">推播通知：</label>
                <input type="checkbox" name="push_notification" id="push_notification" value="1"
                    {{ $item->push_notification ? 'checked' : '' }}>
            </div>
            <!-- 隱藏的表單欄位，用來記錄價格 -->
            <input type="hidden" name="original_price" value="{{ $item->price }}">


            <div class="flex justify-center items-center mt-5">
                <button type="submit" class="border-double border-4 "> 更新商品 </button>
            </div>
            <!-- <div class="flex justify-center items-center mt-5">
                <button type="submit" value="updateAndPush" class="border-double border-4 "> 推播並更新商品 </button>
            </div> -->

        </form>
    </div>
</body>

</html>
