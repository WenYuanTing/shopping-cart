<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    @include('layouts.navbar')
    <div>
        <h1 class="flex justify-center mt-10 text-5xl	">購物車</h1>
    </div>
    <div class="flex flex-wrap">
        @forelse($userShoppingCartItems as $userShoppingCartItem)
            <div class="w-1/4 p-20">
                <h2>商品名稱 : {{ $userShoppingCartItem->name }}</h2>
                <h3>商品單價 : {{ $userShoppingCartItem->price }}</h3>
                <h3>數量 : {{ $userShoppingCartItem->quantity }}</h3>
                <div class="space-x-8 ml-2">
                    <a href="#" class="increase-quantity-btn" data-item-id="{{ $userShoppingCartItem->product_id }}"
                        data-item-quantity="{{ $userShoppingCartItem->quantity }}"
                        data-user-id="{{ $userShoppingCartItem->user_id }}">增加</a>
                    <a href="#" class="decrease-quantity-btn" data-item-id="{{ $userShoppingCartItem->product_id }}"
                        data-item-quantity="{{ $userShoppingCartItem->quantity }}"
                        data-user-id="{{ $userShoppingCartItem->user_id }}">減少</a>
                </div>
                <h3>價格 :
                    {{ $userShoppingCartItem->price * $userShoppingCartItem->quantity }}
                </h3>
            </div>
        @empty
            <div>暫無商品</div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var increaseButtons = document.querySelectorAll('.increase-quantity-btn');
            var decreaseButtons = document.querySelectorAll('.decrease-quantity-btn');

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            increaseButtons.forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log("Button clicked");

                    var itemId = this.getAttribute('data-item-id');
                    var itemQuantity = this.getAttribute('data-item-quantity');
                    var userId = this.getAttribute('data-user-id');

                    fetch(`/item/increaseQuantity/${itemId}?quantity=${itemQuantity}&user_id=${userId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Quantity increased successfully');
                            // 刷新页面或更新相关元素
                            window.location.href = `/shoppingCart/${userId}`;

                        })
                        .catch(error => {
                            console.error('Error increasing quantity:', error.message);
                        });
                });
            });


            decreaseButtons.forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log("Decreased Button clicked");

                    var itemId = this.getAttribute('data-item-id');
                    var itemQuantity = this.getAttribute('data-item-quantity');
                    var userId = this.getAttribute('data-user-id');

                    fetch(`/item/decreaseQuantity/${itemId}?quantity=${itemQuantity}&user_id=${userId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Quantity decreased successfully');
                            // 刷新页面或更新相关元素
                            window.location.href = `/shoppingCart/${userId}`;

                        })
                        .catch(error => {
                            console.error('Error increasing quantity:', error.message);
                        });
                });
            });

        });

    </script>
</body>

</html>
