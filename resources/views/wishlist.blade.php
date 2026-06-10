@include('header')

<div id="main">
    <br>
    <h1 class="title">Wishlist</h1>
    <br>

    @if($items->isEmpty())
        <div class="wishlist-empty">
            <p>Your wishlist is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Browse products</a>
        </div>
    @else
        <div id="products-container">
            @foreach($items as $item)
                @php $product = $item->product; @endphp
                <div class="wishlist-card">
                    <a class="wishlist-card-link" href="{{ route('products.show', $product->id) }}">
                        <div class="wishlist-card-image-box">
                            <img class="wishlist-card-image" src="{{ $product->image_url }}" alt="{{ $product->name }}"/>
                        </div>
                        <h2 class="product-title">{{ $product->name }}</h2>
                        @if($product->manufacturer)
                            <p class="wishlist-card-manufacturer">{{ $product->manufacturer }}</p>
                        @endif
                        <div class="product-footer">
                            @if($product->price < $product->last_price)
                                <p class="product-price product-price-discount">{{ number_format($product->price, 2) }}€</p>
                                <p class="product-price-old">{{ number_format($product->last_price, 2) }}€</p>
                            @else
                                <p class="product-price">{{ number_format($product->price, 2) }}€</p>
                            @endif
                        </div>
                    </a>
                    <div class="wishlist-card-footer">
                        <form method="POST" action="{{ route('cart.add', $product->id) }}">
                            @csrf
                            <button type="submit" class="product-button">Add to cart</button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.toggle', $product->id) }}">
                            @csrf
                            <button type="submit" class="wishlist-remove-btn" title="Remove from wishlist">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@include('footer')
