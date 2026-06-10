@include('header')

    <div id="main">
        <div id="product-detail-wrapper">
            <a href="{{ route('products.index') }}" class="back-link">{{ __('Back to products') }}</a>

            <div id="product-detail-card">
                <div id="product-detail-image-col">
                    <div id="product-detail-image-box">
                        <img id="product-detail-image" src="{{ $product->image_url }}" alt="{{ $product->name }}"/>
                    </div>
                </div>

                <div id="product-detail-info-col">
                    <p class="product-detail-category">{{ $product->category->category ?? __('Uncategorized') }}</p>
                    <h1 id="product-detail-name">{{ $product->name }}</h1>
                    @if($product->manufacturer)
                        <p class="product-detail-manufacturer">{{ __('by') }} {{ $product->manufacturer }}</p>
                    @endif

                    <div id="product-detail-price-row">
                        @if($product->price < $product->last_price)
                            <span class="product-detail-price product-detail-price-discount">{{ number_format($product->price, 2) }}€</span>
                            <span class="product-detail-price-old">{{ number_format($product->last_price, 2) }}€</span>
                            <span class="product-detail-badge-discount">
                                -{{ round((1 - $product->price / $product->last_price) * 100) }}%
                            </span>
                        @else
                            <span class="product-detail-price">{{ number_format($product->price, 2) }}€</span>
                        @endif
                    </div>

                    <div class="product-detail-actions">
                        <form method="POST" action="{{ route('cart.add', $product->id) }}">
                            @csrf
                            <button type="submit" class="product-detail-cart-btn" data-alert="{{ __('Added to cart!') }}">{{ __('Add to cart') }}</button>
                        </form>

                        @auth
                            <form method="POST" action="{{ route('wishlist.toggle', $product->id) }}">
                                @csrf
                                <button type="submit" class="wishlist-btn {{ $isWishlisted ? 'wishlisted' : '' }}"
                                    data-alert="{{ $isWishlisted ? __('Removed from wishlist!') : __('Added to wishlist!') }}">
                                    {{ $isWishlisted ? __('Wishlisted') : __('Add to wishlist') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="wishlist-btn">
                                {{ __('Add to wishlist') }}
                            </a>
                        @endauth
                    </div>

                    @if($product->description)
                        <div class="product-detail-section">
                            <h3 class="product-detail-section-title">{{ __('Description') }}</h3>
                            <p class="product-detail-description">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($product->specifications->isNotEmpty())
                <div id="product-detail-specs-card">
                    <h3 class="product-detail-section-title">{{ __('Specifications') }}</h3>
                    <table class="product-detail-specs-table">
                        @foreach($product->specifications as $spec)
                            <tr>
                                <td class="spec-key">{{ $spec->key }}</td>
                                <td class="spec-val">{{ $spec->value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </div>

    @include('footer')
</body>
</html>
