@include('header')

<div id="main">
    <div id="products-page">

        <div id="products-topbar">
            <h1 class="title">Products</h1>
            <div id="products-topbar-controls">
                <form method="GET" action="{{ route('products.index') }}" id="products-form">
                    @foreach(request('categories', []) as $cat)
                        <input type="hidden" name="categories[]" value="{{ $cat }}">
                    @endforeach
                    @if(request('price_min'))
                        <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                    @endif
                    @if(request('price_max'))
                        <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                    @endif
                    @if(request('on_sale'))
                        <input type="hidden" name="on_sale" value="1">
                    @endif

                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="">Sort</option>
                        <option value="price-low-high" {{ request('sort') == 'price-low-high' ? 'selected' : '' }}>Price: low to high</option>
                        <option value="price-high-low" {{ request('sort') == 'price-high-low' ? 'selected' : '' }}>Price: high to low</option>
                        <option value="name-asc"       {{ request('sort') == 'name-asc'       ? 'selected' : '' }}>Alphabetically</option>
                        <option value="name-desc"      {{ request('sort') == 'name-desc'      ? 'selected' : '' }}>Reverse alphabetically</option>
                    </select>
                </form>

                <form method="GET" action="{{ route('products.index') }}" id="search-form">
                    @foreach(request('categories', []) as $cat)
                        <input type="hidden" name="categories[]" value="{{ $cat }}">
                    @endforeach
                    @if(request('price_min'))
                        <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                    @endif
                    @if(request('price_max'))
                        <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                    @endif
                    @if(request('on_sale'))
                        <input type="hidden" name="on_sale" value="1">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <input type="text" name="nosaukums" placeholder="Search by name" id="search-input" value="{{ request('nosaukums') }}">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        @if(request('nosaukums') && $products->isEmpty())
            <p class="search-error">No products found!</p>
        @endif

        <div id="products-layout">
            <div id="products-grid-area">
                <div id="products-container">
                    @foreach($products as $product)
                        <a class="product-item" href="{{ route('products.show', $product->id) }}">
                            <img class="product-image" src={{ $product->image_url }} alt={{ $product->name }}/>
                            <h2 class="product-title">{{ $product->name }}</h2>
                            <div class="product-footer">
                                @if($product->price < $product->last_price)
                                    <p class="product-price product-price-discount">{{ number_format($product->price, 2) }}€</p>
                                    <p class="product-price-old">{{ number_format($product->last_price, 2) }}€</p>
                                @else
                                    <p class="product-price">{{ number_format($product->price, 2) }}€</p>
                                @endif
                                <input type="button" class="product-button" value="Add to cart" id="add-{{ $product->id }}">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <aside id="filter-panel">
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    @if(request('nosaukums'))
                        <input type="hidden" name="nosaukums" value="{{ request('nosaukums') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <p class="filter-panel-title">Filters</p>

                    @if($categories->isNotEmpty())
                        <div class="filter-section">
                            <p class="filter-section-label">Category</p>
                            @foreach($categories as $cat)
                                <label class="filter-checkbox-label">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                        {{ in_array($cat->id, request('categories', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    {{ $cat->category }}
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <div class="filter-section">
                        <p class="filter-section-label">Price range</p>
                        <div class="filter-price-row">
                            <input type="number" name="price_min" placeholder="Min" min="0" step="0.01"
                                value="{{ request('price_min') }}" class="filter-price-input">
                            <span class="filter-price-sep">-</span>
                            <input type="number" name="price_max" placeholder="Max" min="0" step="0.01"
                                value="{{ request('price_max') }}" class="filter-price-input">
                        </div>
                    </div>

                    <div class="filter-section">
                        <p class="filter-section-label">Deals</p>
                        <label class="filter-checkbox-label">
                            <input type="checkbox" name="on_sale" value="1"
                                {{ request('on_sale') ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            On sale only
                        </label>
                    </div>

                    <button type="submit" class="filter-apply-btn">Apply</button>

                    @if(request()->hasAny(['categories', 'price_min', 'price_max', 'on_sale']))
                        <a href="{{ route('products.index', array_filter(['nosaukums' => request('nosaukums'), 'sort' => request('sort')])) }}"
                           class="filter-clear-link">Clear filters</a>
                    @endif
                </form>
            </aside>
        </div>

    </div>
</div>

@include('footer')
