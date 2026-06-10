@include('header')

<div id="main">
    <a href="{{ route('admin.index') }}" class="back-link">{{ __('Back to admin panel') }}</a>
    <h1 style="font-size:18px;font-weight:500;margin-bottom:1.5rem;">{{ __('Edit Product') }} — {{ $product->name }}</h1>

    <div class="card">
        <p class="section-label">{{ __('Product Details') }}</p>
        <form method="POST" action="{{ route('admin.specs.update', $product->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>{{ __('Name') }}</label>
                    <input type="text" name="name" value="{{ $product->name }}" required maxlength="32">
                </div>
                <div class="field">
                    <label>{{ __('Manufacturer') }}</label>
                    <input type="text" name="manufacturer" value="{{ $product->manufacturer }}" required maxlength="64">
                </div>
                <div class="field">
                    <label>{{ __('Price') }}</label>
                    <input type="number" name="price" value="{{ $product->price }}" step="0.01" required>
                </div>
                <div class="field">
                    <label>{{ __('Previous Price') }}</label>
                    <input type="number" name="last_price" value="{{ $product->last_price }}" step="0.01" required>
                </div>
                <div class="field">
                    <label>{{ __('Category') }}</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>{{ __('Image URL') }}</label>
                    <input type="text" name="image_url" value="{{ $product->image_url }}" required>
                </div>
            </div>
            <div class="field" style="margin-bottom:12px;">
                <label>{{ __('Description') }}</label>
                <textarea name="description" required maxlength="32">{{ $product->description }}</textarea>
            </div>

            <p class="section-label">{{ __('Specifications') }}</p>
            <div id="specs-container">
                @foreach($product->specifications as $i => $spec)
                <div class="spec-row">
                    <input type="text" name="specs[{{ $i }}][key]" value="{{ $spec->key }}" placeholder="{{ __('Key') }}">
                    <input type="text" name="specs[{{ $i }}][value]" value="{{ $spec->value }}" placeholder="{{ __('Value') }}">
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">✕</button>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn" style="margin-bottom:1rem;" onclick="addSpec()">{{ __('+ Add specification') }}</button>
            <br>
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

<script>
let specIndex = {{ $product->specifications->count() }};
function addSpec() {
    const container = document.getElementById('specs-container');
    const row = document.createElement('div');
    row.className = 'spec-row';
    row.innerHTML = `
        <input type="text" name="specs[${specIndex}][key]" placeholder="${window.trans.specKey}">
        <input type="text" name="specs[${specIndex}][value]" placeholder="${window.trans.specValue}">
        <button type="button" class="btn btn-danger" onclick="removeSpec(this)">✕</button>
    `;
    container.appendChild(row);
    specIndex++;
}
function removeSpec(btn) {
    btn.closest('.spec-row').remove();
}
</script>

@include('footer')
