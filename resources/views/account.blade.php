@include('header')

<div id="main">
    <div class="acc-header">
        <div>
            <p class="acc-name">
                {{ $user->userData->name }} {{ $user->userData->last_name }}
            </p>
            <p class="acc-role">{{ ucfirst($user->getRoleNames()->first() ?? 'user') }}</p>
        </div>
         <form method="POST" action="{{ route('auth.logout') }}">
            @role('admin')
            <a href="{{ route("admin.index") }}" class="save-btn a-btn">{{ __('Admin panel') }}</a>
            @endrole
            @csrf
            <button type="submit" class="save-btn">{{ __('Log out') }}</button>
        </form>
    </div>

    <div class="section">
        <p class="section-title">{{ __('Profile Info') }}</p>
        <div class="card">
            @if(session('success'))
                <p class="success-msg">{{ session('success') }}</p>
            @endif
            @if(session('error'))
                <p class="error-msg">{{ session('error') }}</p>
            @endif

            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                <div class="field-row">
                    <div class="field">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" value="{{ $user->userData->name}}" required maxlength="16">
                    </div>
                    <div class="field">
                        <label>{{ __('Last name') }}</label>
                        <input type="text" name="last_name" value="{{ $user->userData->last_name}}" required maxlength="16">
                    </div>
                </div>
                <div class="field">
                    <label>{{ __('E-mail') }}</label>
                    <input type="email" name="email" value="{{ $user->email }}" required maxlength="255">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>{{ __('Phone code') }}</label>
                        <input type="text" name="phone_code" value="{{ $user->userData->phone_code }}" placeholder="+371">
                    </div>
                    <div class="field">
                        <label>{{ __('Phone number') }}</label>
                        <input type="text" name="phone_num" value="{{ $user->userData->phone_num }}" placeholder="29000000">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>{{ __('Country') }}</label>
                        <input type="text" name="country" value="{{ $user->userData->country }}" maxlength="32">
                    </div>
                    <div class="field">
                        <label>{{ __('City') }}</label>
                        <input type="text" name="city" value="{{ $user->userData->city }}" maxlength="16">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>{{ __('Address') }}</label>
                        <input type="text" name="address" value="{{ $user->userData->address }}" maxlength="64">
                    </div>
                    <div class="field">
                        <label>{{ __('Post index') }}</label>
                        <input type="text" name="zip" value="{{ $user->userData->zip }}" maxlength="7">
                    </div>
                </div>
                <button type="submit" class="save-btn">{{ __('Save') }}</button>
            </form>
        </div>
    </div>

    <div class="section">
        <p class="section-title">{{ __('Order History') }}</p>
        <div class="card">
            @forelse($orders as $order)
                <div class="order-row">
                    <div>
                        <p class="order-id">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="order-date">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="order-right">
                        <p class="order-price">€{{ number_format($order->total, 2) }}</p>
                        <span class="badge badge-{{ $order->status }}">{{ ucfirst(__($order->status)) }}</span>
                    </div>
                </div>
                @if($order->items->isNotEmpty())
                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item-row">
                                @if($item->product && $item->product->image_url)
                                    <img class="order-item-img" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                @endif
                                <span class="order-item-name">{{ $item->product->name ?? __('Product removed') }}</span>
                                <span class="order-item-qty">x{{ $item->quantity }}</span>
                                <span class="order-item-price">€{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @empty
                <p class="no-orders">{{ __('No orders yet.') }}</p>
            @endforelse
        </div>
    </div>
</div>

@include('footer')
