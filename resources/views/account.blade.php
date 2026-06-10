@include('header')

<div id="main">
    <div class="acc-header">
        <div>
            <p class="acc-name">
                {{ $user->userData->name }} {{ $user->userData->last_name }}
            </p>
            <p class="acc-role">{{ ucfirst($user->getRoleNames()->first() ?? 'lietotājs') }}</p>
        </div>
         <form method="POST" action="{{ route('auth.logout') }}">
            @role('admin')
            <a href="{{ route("admin.index") }}" class="save-btn a-btn">Admin panel</a>
            @endrole
            @csrf
            <button type="submit" class="save-btn">Log out</button>
        </form>
    </div>

    <div class="section">
        <p class="section-title">Profile Info</p>
        <div class="card">
            @if(session('success'))
                <p class="success-msg">{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                <div class="field-row">
                    <div class="field">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->userData->name) }}" required maxlength="16">
                    </div>
                    <div class="field">
                        <label>Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name',$user->userData->last_name) }}" required maxlength="16">
                    </div>
                </div>
                <div class="field">
                    <label>E-mail</label>
                    <input type="email" value="{{ $user->userData->email }}" disabled>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Phone code</label>
                        <input type="text" name="phone_code" value="{{ old('phone_code', $user->userData->phone_code) }}" placeholder="+371">
                    </div>
                    <div class="field">
                        <label>Phone number</label>
                        <input type="text" name="phone_num" value="{{ old('phone_num', $user->userData->phone_num) }}" placeholder="29000000">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->userData->country) }}" maxlength="32">
                    </div>
                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city', $user->userData->city) }}" maxlength="16">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->userData->address) }}" maxlength="64">
                    </div>
                    <div class="field">
                        <label>Post index</label>
                        <input type="text" name="zip" value="{{ old('zip', $user->userData->zip) }}" maxlength="7">
                    </div>
                </div>
                <button type="submit" class="save-btn">Save</button>
            </form>
        </div>
    </div>

    <div class="section">
        <p class="section-title">Pasūtījumu vēsture</p>
        <div class="card">
            @forelse($orders as $order)
                <div class="order-row">
                    <div>
                        <p class="order-id">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="order-date">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="order-right">
                        <p class="order-price">€{{ number_format($order->total, 2) }}</p>
                        <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            @empty
                <p class="no-orders">Nav pasūtījumu.</p>
            @endforelse
        </div>
    </div>
</div>

@include('footer')