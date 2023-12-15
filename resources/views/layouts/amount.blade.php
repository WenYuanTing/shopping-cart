<div class="flex border-t-8 border-solid justify-end">
    <div class="mr-80 mt-5">
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
            <h2 class="text-4xl font-bold mb-8 ">總金額 :</h2>
            <label class="text-2xl ml-auto font-bold mb-8" for="">
                {{ $totalAmount +80 - $discount }}</label>
            @php
                session(['amount' => $totalAmount +80 - $discount ]);
            @endphp
        </div>
        <form method="POST" action="{{ route('storeFormData') }}">
            @csrf
            <div>
                <label class="text-2xl font-bold mb-2 mr-8">收件名稱 : </label>
                <input type="text" name="recipient" required class="border-solid border-2 border-black ml-auto ">
            </div>
            <div>
                <label class="text-2xl font-bold mb-2 mr-8">聯絡電話 : </label>
                <input type="text" name="contact_number" required class="border-solid border-2 border-black">
            </div>
            <div>
                <label class="text-2xl font-bold mb-2 mr-8">收件地址 : </label>
                <input type="text" name="shipping_address" required class="border-solid border-2 border-black">
            </div>
            <div class="flex justify-center">
                <button type="submit" class="text-4xl font-bold ">結帳</button>
            </div>
        </form>


    </div>
</div>
