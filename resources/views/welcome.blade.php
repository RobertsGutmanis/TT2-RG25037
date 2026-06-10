@include('header')

<section id="hero">
    <div id="hero-content">
        <h1 id="hero-headline">Lorem ipsum dolor sit amet.</h1>
        <p id="hero-sub">consectetur adipiscing elit. Donec orci neque, tincidunt et dui quis, convallis facilisis lectus. Nam mi mauris,</p>
        <div id="hero-actions">
            <a href="{{ route('products.index') }}" class="hero-btn hero-btn-primary">{{ __('All products') }}</a>
            @guest
                <a href="{{ route('auth.register') }}" class="hero-btn hero-btn-outline">{{ __('Create account') }}</a>
            @endguest
        </div>
    </div>
</section>
@if($featured->isNotEmpty())
<section id="landing-products">
    <div class="landing-section-header">
        <h2 class="landing-section-title">{{ __('Latest products') }}</h2>
        <a href="{{ route('products.index') }}" class="landing-see-all">{{ __('See all') }}</a>
    </div>
    <div id="products-container">
        @foreach($featured as $product)
            <a class="product-item" href="{{ route('products.show', $product->id) }}">
                <img class="product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}"/>
                <h2 class="product-title">{{ $product->name }}</h2>
                <div class="product-footer">
                    @if($product->price < $product->last_price)
                        <div class="product-price-group">
                            <p class="product-price product-price-discount">{{ number_format($product->price, 2) }}€</p>
                            <p class="product-price-old">{{ number_format($product->last_price, 2) }}€</p>
                        </div>
                    @else
                        <p class="product-price">{{ number_format($product->price, 2) }}€</p>
                    @endif
                    <input type="button" class="product-button" value="{{ __('Add to cart') }}" id="add-{{ $product->id }}">
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@include('footer')
