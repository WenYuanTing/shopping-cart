<div class="flex border-t-8 border-solid justify-end">
    <div class="mr-80 mt-10">
        <div class="flex justify-center">
            <p class="text-2xl font-bold mb-2 mr-8">商品金額 : </p>
            <label class="text-2xl ml-auto" for=""> {{ $totalAmount }}</label>
        </div>
        <div class="flex">
            <p class="text-2xl font-bold mb-2">運費 : </p>
            <label class="text-2xl ml-auto" for="">80</label>
        </div>
        <div class="flex">
            <p class="text-2xl font-bold mb-2">折扣 : </p>
            @php
                $discount = 0;
                if( $totalAmount >=1000){
                $discount=80;
                }
            @endphp
            <label class="text-2xl ml-auto" for="">{{ -$discount }}</label>
        </div>

        <div class="flex">
            <h2 class="text-4xl font-bold mb-8">總金額 : {{ $totalAmount +80 - $discount }}</h2>
        </div>
        <div class="flex justify-center">
            <a class="text-4xl font-bold" href="{{ route('createEcpayOrder') }}">結帳</a>

        </div>
    </div>
</div>
