@include('header')
<div id="main">
    <div class="panel-header">
        <h1 class="panel-title">{{ __('Admin panel') }}</h1>
        <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Back to Account') }}</a>
    </div>

    @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    {{-- ACTIVITY LOG --}}
    <div class="card">
        <p class="section-label">{{ __('Activity Log') }}</p>
        <table>
            <thead>
                <tr>
                    <th style="width:140px;">{{ __('Time') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Action') }}</th>
                    <th>{{ __('Details') }}</th>
                    <th style="width:120px;">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                @php
                    $actionKeys = [
                        'login_success'  => 'log_login_success',
                        'login_failed'   => 'Login Failed',
                        'logout'         => 'Logout',
                        'register'       => 'Register action',
                        'cart_add'       => 'Cart — Add',
                        'cart_remove'    => 'Cart — Remove',
                        'cart_update'    => 'Cart — Update',
                        'cart_clear'     => 'Cart — Clear',
                        'wishlist_add'   => 'Wishlist — Add',
                        'wishlist_remove'=> 'Wishlist — Remove',
                        'checkout'       => 'Checkout action',
                        'profile_update' => 'Profile Update',
                    ];
                    $key = $actionKeys[$log['action']] ?? null;
                    $label = $key ? __($key) : ucfirst(str_replace('_', ' ', $log['action']));
                    $details = collect($log['details'] ?? [])->map(fn($value, $key) => "$key: $value")->implode(', ');
                    $isError = str_contains($log['action'], 'failed');
                @endphp
                <tr>
                    <td style="font-size:12px;color:#888;white-space:nowrap;">{{ $log['time'] }}</td>
                    <td style="font-size:12px;">
                        {{ $log['email'] }}
                    </td>
                    <td>
                        <span class="log-badge {{ $isError ? 'log-badge-error' : 'log-badge-ok' }}">{{ $label }}</span>
                    </td>
                    <td style="font-size:12px;color:#555;">{{ $details ?: '—' }}</td>
                    <td style="font-size:11px;color:#aaa;">{{ $log['ip'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#aaa;font-size:13px;padding:16px;">{{ __('No logs yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($logPages > 1)
        <div class="log-pagination">
            @if($logPage > 1)
                <a href="{{ request()->fullUrlWithQuery(['log_page' => $logPage - 1]) }}" class="log-page-btn">{{ __('Prev') }}</a>
            @else
                <span class="log-page-btn log-page-disabled">{{ __('Prev') }}</span>
            @endif

            <span class="log-page-info">{{ __('Page :page of :pages (:total entries)', ['page' => $logPage, 'pages' => $logPages, 'total' => $logTotal]) }}</span>

            @if($logPage < $logPages)
                <a href="{{ request()->fullUrlWithQuery(['log_page' => $logPage + 1]) }}" class="log-page-btn">{{ __('Next') }}</a>
            @else
                <span class="log-page-btn log-page-disabled">{{ __('Next') }}</span>
            @endif
        </div>
        @endif
    </div>

    {{-- ORDERS --}}
    <div class="card">
        <p class="section-label">{{ __('All Orders') }}</p>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Customer') }}</th>
                    <th>{{ __('Items') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight:500;font-size:13px;">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td style="font-size:13px;color:#888;">{{ $order->created_at->format('d M Y') }}</td>
                    <td style="font-size:13px;">
                        @if($order->user && $order->user->userData)
                            {{ $order->user->userData->name }} {{ $order->user->userData->last_name }}
                            <br><span style="color:#aaa;font-size:11px;">{{ $order->user->email }}</span>
                        @else
                            <span style="color:#aaa;">{{ __('Unknown') }}</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#555;">
                        @foreach($order->items as $item)
                            <div>{{ $item->product->name ?? __('Removed') }} x{{ $item->quantity }}</div>
                        @endforeach
                    </td>
                    <td style="font-weight:500;font-size:13px;">€{{ number_format($order->total, 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.order.status', $order->id) }}" style="display:flex;gap:6px;align-items:center;">
                            @csrf
                            <select name="status" class="admin-status-select">
                                @foreach(['pending','processing','delivered','cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst(__($status)) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="padding:4px 10px;font-size:12px;">{{ __('Save') }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;font-size:13px;padding:16px;">{{ __('No orders yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PRODUCT LIST --}}
    <div class="card">
        <p class="section-label">{{ __('Products') }}</p>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Categories') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Last price') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <img class="product-thumbnail" src="{{ $product->image_url }}" alt="{{ $product->name }}"/>
                    </td>
                    <td>
                        <p class="product-name">{{ $product->name }}</p>
                        <p class="product-cat">{{ $product->manufacturer }}</p>
                    </td>
                    <td style="font-size:13px;color:#888;">{{ $product->category->category ?? '—' }}</td>
                    <td style="font-weight:500;font-size:13px;">€{{ number_format($product->price, 2) }}</td>
                    <td style="font-weight:500;font-size:13px;">€{{ number_format($product->last_price, 2) }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="{{ route('admin.specs', $product->id) }}" class="btn">{{ __('Edit') }}</a>
                            <form method="POST" action="{{ route('admin.destroy', $product->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ADD PRODUCT --}}
    <div class="card">
        <p class="section-label">{{ __('Add a product') }}</p>
        <form method="POST" action="{{ route('admin.store') }}" id="add-form">
            @csrf
            <div class="form-grid">
                <div class="field"><label>{{ __('Name') }}</label><input type="text" name="name" required maxlength="32"></div>
                <div class="field"><label>{{ __('Manufacturer') }}</label><input type="text" name="manufacturer" required maxlength="64"></div>
                <div class="field"><label>{{ __('Price') }}</label><input type="number" name="price" step="0.01" required></div>
                <div class="field">
                    <label>{{ __('Category') }}</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>{{ __('Image URL') }}</label><input type="text" name="image_url" required></div>
            </div>
            <div class="field" style="margin-bottom:12px;"><label>{{ __('Description') }}</label><textarea name="description" required maxlength="32"></textarea></div>

            <p class="section-label">{{ __('Specifications') }}</p>
            <div id="specs-container">
                <div class="spec-row">
                    <input type="text" name="specs[0][key]" placeholder="{{ __('Key') }}">
                    <input type="text" name="specs[0][value]" placeholder="{{ __('Value') }}">
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">X</button>
                </div>
            </div>
            <button type="button" class="btn" style="margin-bottom:1rem;" onclick="addSpec()">{{ __('Add specifications') }}</button>
            <br>
            <button type="submit" class="btn btn-primary">{{ __('Add product') }}</button>
        </form>
    </div>
</div>

<script>
let specIndex = 1;
function addSpec() {
    const container = document.getElementById('specs-container');
    const row = document.createElement('div');
    row.className = 'spec-row';
    row.innerHTML = `
        <input type="text" name="specs[${specIndex}][key]" placeholder="${window.trans.specKey}">
        <span style="color:#aaa;font-size:13px;"></span>
        <input type="text" name="specs[${specIndex}][value]" placeholder="${window.trans.specValue}">
        <button type="button" class="btn btn-danger" onclick="removeSpec(this)">X</button>
    `;
    container.appendChild(row);
    specIndex++;
}
function removeSpec(btn) {
    btn.closest('.spec-row').remove();
}
</script>

@include('footer')
