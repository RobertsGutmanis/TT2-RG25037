<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RG25037</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="cart-menu">
        <div class="cart-menu-items"></div>
        <div class="cart-menu-footer">
            <p class="cart-menu-price-total">Total price: </p>
            <a href="./grozs.html" class="go-to-cart">To cart!</a>
        </div>
    </div>
    <div id="google_translate_element"></div>
    <nav>
        <div id="nav-bar">
            <ul id="nav-links">
                <li><a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a></li>
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Account') }}</a></li>
                @endguest
                <li><a class="nav-link" href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                <li><a class="nav-link" href="{{ route('cart.index') }}">{{ __('Cart') }}</a></li>
                <li><a class="nav-link" href="{{ route('wishlist.index') }}">{{ __('Wishlist') }}</a></li>
            </ul>
            <div id="nav-right">
                <div id="lang-switcher">
                    <a href="{{ route('language.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}">EN</a>
                    <span class="lang-sep">|</span>
                    <a href="{{ route('language.switch', 'lv') }}" class="lang-btn {{ app()->getLocale() === 'lv' ? 'lang-active' : '' }}">LV</a>
                </div>
                <a id="nav-user" href="{{ route('login') }}">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/></svg>
                </a>
                <a id="nav-cart" href="{{ route('cart.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 42 42"><path fill="currentColor" d="M40.5 12.5c0-1.48-.311-2-1.872-2H11.726l-.801-5c-.109-1.46-.85-2-2.421-2H2.501C1.02 3.5.5 3.99.5 5.5v1c0 1.551.52 2 2.001 2h3.722l3.282 19c.35 1.04 1.311 1.95 3.001 2h22.012c1.75 0 2.57-.359 3.002-2l2.98-15zm-7.023 12H13.696l-1.471-9h22.951l-1.699 9zm-19.97 12a4 4 0 0 0 4.002 4a4 4 0 1 0 0-8a4 4 0 0 0-4.002 4zm13.007 0a4 4 0 0 0 4.002 4a4 4 0 1 0 0-8a4 4 0 0 0-4.002 4z"/></svg>
                </a>
                <button id="nav-toggle" aria-label="Open menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>
    <script>
        window.trans = {
            addedToCart: "{{ __('Added to cart!') }}",
            addedToWishlist: "{{ __('Added to wishlist!') }}",
            removedFromWishlist: "{{ __('Removed from wishlist!') }}",
            specKey: "{{ __('Key') }}",
            specValue: "{{ __('Value') }}"
        };
    </script>
