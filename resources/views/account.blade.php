@include('header')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');
  * { box-sizing: border-box; }
  .acc-header { display: flex; align-items: center; gap: 16px; margin-bottom: 2rem; }
  .acc-avatar { width: 52px; height: 52px; border-radius: 50%; background: #4CAF50; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 500; color: #fff; flex-shrink: 0; }
  .acc-name { font-size: 17px; font-weight: 500; margin: 0; }
  .acc-role { font-size: 12px; color: #777; margin: 2px 0 0; }
  .section { margin-bottom: 2rem; }
  .section-title { font-size: 12px; font-weight: 500; color: #888; margin: 0 0 12px; text-transform: uppercase; letter-spacing: 0.06em; }
  .card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 1.25rem; width: 700px; }
  .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .field { margin-bottom: 0.875rem; }
  .field:last-of-type { margin-bottom: 0; }
  .field label { display: block; font-size: 11px; color: #777; margin-bottom: 4px; letter-spacing: 0.04em; }
  .field input { width: 100%; height: 38px; padding: 0 12px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa; font-family: 'Poppins', sans-serif; font-size: 13px; outline: none; }
  .field input:focus { border-color: #4CAF50; }
  .save-btn { height: 38px; padding: 0 20px; background: #4CAF50; color: #fff; border: none; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; margin-top: 1rem; }
  .save-btn:hover { background: #43a047; }
  .success-msg { font-size: 13px; color: #2e7d32; margin-top: 0.75rem; }
  .order-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
  .order-row:last-child { border-bottom: none; padding-bottom: 0; }
  .order-id { font-size: 13px; font-weight: 500; margin: 0; }
  .order-date { font-size: 12px; color: #888; margin: 2px 0 0; }
  .order-right { text-align: right; }
  .order-price { font-size: 13px; font-weight: 500; margin: 0; }
  .badge { display: inline-block; font-size: 11px; padding: 2px 8px; border-radius: 20px; margin-top: 3px; }
  .badge-delivered { background: #e8f5e9; color: #2e7d32; }
  .badge-pending { background: #fff8e1; color: #f57f17; }
  .badge-processing { background: #e3f2fd; color: #1565c0; }
  .no-orders { font-size: 13px; color: #aaa; text-align: center; padding: 1rem 0; }
</style>

<div id="main">
    <div class="acc-header">
        <div>
            <p class="acc-name">
                {{ $user->userData->name }} {{ $user->userData->last_name }}
            </p>
            <p class="acc-role">{{ ucfirst($user->getRoleNames()->first() ?? 'lietotājs') }}</p>
        </div>
    </div>

    <div class="section">
        <p class="section-title">Profila info</p>
        <div class="card">
            @if(session('success'))
                <p class="success-msg">{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                <div class="field-row">
                    <div class="field">
                        <label>Vārds</label>
                        <input type="text" name="name" value="{{ old('name', $user->userData->name) }}" required maxlength="16">
                    </div>
                    <div class="field">
                        <label>Uzvārds</label>
                        <input type="text" name="last_name" value="{{ old('last_name',$user->userData->last_name) }}" required maxlength="16">
                    </div>
                </div>
                <div class="field">
                    <label>E-pasts</label>
                    <input type="email" value="{{ $user->userData->email }}" disabled>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Telefona kods</label>
                        <input type="text" name="phone_code" value="{{ old('phone_code', $user->userData->phone_code) }}" placeholder="+371">
                    </div>
                    <div class="field">
                        <label>Telefona numurs</label>
                        <input type="text" name="phone_num" value="{{ old('phone_num', $user->userData->phone_num) }}" placeholder="29000000">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Valsts</label>
                        <input type="text" name="country" value="{{ old('country', $user->userData->country) }}" maxlength="32">
                    </div>
                    <div class="field">
                        <label>Pilsēta</label>
                        <input type="text" name="city" value="{{ old('city', $user->userData->city) }}" maxlength="16">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Adrese</label>
                        <input type="text" name="address" value="{{ old('address', $user->userData->address) }}" maxlength="64">
                    </div>
                    <div class="field">
                        <label>Pasta indekss</label>
                        <input type="text" name="zip" value="{{ old('zip', $user->userData->zip) }}" maxlength="7">
                    </div>
                </div>
                <button type="submit" class="save-btn">Saglabāt</button>
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