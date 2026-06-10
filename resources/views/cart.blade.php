@include('header')

<div id="main">
    <div class="cart-page-wrapper">
        <h1 class="title">Cart</h1>

        @if(empty($cart))
            <div class="wishlist-empty">
                <p>Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Browse products</a>
            </div>
        @else
            <div class="cart-layout">
                <div class="cart-items-col">
                    @foreach($cart as $id => $item)
                        <div class="cart-row">
                            <img class="cart-item-img" src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">
                            <div class="cart-item-info">
                                <p class="cart-item-name">{{ $item['name'] }}</p>
                                <p class="cart-item-unit">{{ number_format($item['price'], 2) }}€ each</p>
                            </div>
                            <div class="cart-item-controls">
                                <form method="POST" action="{{ route('cart.update', $id) }}" class="cart-qty-form">
                                    @csrf
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="cart-qty-btn">-</button>
                                    <span class="cart-qty-num">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="cart-qty-btn">+</button>
                                </form>
                            </div>
                            <p class="cart-item-subtotal">{{ number_format($item['price'] * $item['quantity'], 2) }}€</p>
                            <form method="POST" action="{{ route('cart.remove', $id) }}">
                                @csrf
                                <button type="submit" class="cart-remove-btn">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary-col">
                    <div class="cart-summary-card">
                        <h3 class="cart-summary-title">Order summary</h3>
                        <div class="cart-summary-row">
                            <span>Items ({{ array_sum(array_column($cart, 'quantity')) }})</span>
                            <span>{{ number_format($total, 2) }}€</span>
                        </div>
                        <div class="cart-summary-divider"></div>
                        <div class="cart-summary-row cart-summary-total">
                            <span>Total</span>
                            <span>{{ number_format($total, 2) }}€</span>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('checkout.store') }}">
                                @csrf
                                <button type="submit" class="cart-checkout-btn">Proceed to checkout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="cart-checkout-btn" style="display:block;text-align:center;text-decoration:none;">Log in to checkout</a>
                        @endauth
                        <form method="POST" action="{{ route('cart.clear') }}" style="margin-top: 10px;">
                            @csrf
                            <button type="submit" class="cart-clear-btn">Clear cart</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@include('footer')
