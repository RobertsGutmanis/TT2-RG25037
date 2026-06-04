@include('header')

    <div id="main">
        <br>
        <h1 class="title">Products</h1>
        <br>
        <form method="GET" action="{{ route('products.index') }}">
            <div class="filters">
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="">Kārtot</option>
                    <option value="price-low-high" {{ request('sort') == 'price-low-high' ? 'selected' : '' }}>Price: low to high</option>
                    <option value="price-high-low" {{ request('sort') == 'price-high-low' ? 'selected' : '' }}>Price: high to low</option>
                    <option value="rating-low-high" {{ request('sort') == 'rating-low-high' ? 'selected' : '' }}>Alphabetically</option>
                    <option value="rating-high-low" {{ request('sort') == 'rating-high-low' ? 'selected' : '' }}>Reverse alphabetically</option>
                </select>
            </div>
            <div>
                <label for="search-input" id="search-label">Search: </label>
                <input type="text" name="search-nosaukums" placeholder="Name" id="search-input" value="{{ request('search-nosaukums') }}">
                <button type="submit">Search</button>
            </div>
        </form>
         @if(request('nosaukums') && $products->isEmpty())
            <p class="search-error">No products found!</p>
         @endif
        <div id="products-container">
            @foreach($products as $product)
            <div class="product-item">
                <img class="product-image" src={{ $product->image_url }} alt={{ $product->name }}/>
                <h2 class="product-title">{{ $product->name }}</h2>
                <div class="product-footer">
                    @if($product->price < $product->last_price)
                        <p class="product-price product-price-discount">{{ $product->price }}</p>
                        <p class="product-price-old">{{ $product->last_price }}</p>
                    @elseif($product->price >= $product->last_price)
                        <p class="product-price">{{ $product->price }}</p>
                    @endif
                    <input type="button" class="product-button" value="Add to cart" id="add-{{ $product->id }}">
                </div>
            </div>
        @endforeach
        </div>
    </div>

    @include('footer')
</body>
</html>