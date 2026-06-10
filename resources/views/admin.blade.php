@include('header')
<div id="main">
    <div class="panel-header">
        <h1 class="panel-title">Admin panel</h1>
        <a href="{{ route('login') }}" class="btn btn-primary">Back to Account</a>
    </div>

    @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    {{-- PRODUCT LIST --}}
    <div class="card">
        <p class="section-label">Products</p>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Categories</th>
                    <th>Price</th>
                    <th>Last price</th>
                    <th>Actions</th>
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
                            <a href="{{ route('admin.specs', $product->id) }}" class="btn">Edit</a>
                            <form method="POST" action="{{ route('admin.destroy', $product->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
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
        <p class="section-label">Add a product</p>
        <form method="POST" action="{{ route('admin.store') }}" id="add-form">
            @csrf
            <div class="form-grid">
                <div class="field"><label>Name</label><input type="text" name="name" required maxlength="32"></div>
                <div class="field"><label>Manufacturer</label><input type="text" name="manufacturer" required maxlength="64"></div>
                <div class="field"><label>Price</label><input type="number" name="price" step="0.01" required></div>
                <div class="field">
                    <label>Category</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>Image URL</label><input type="text" name="image_url" required></div>
            </div>
            <div class="field" style="margin-bottom:12px;"><label>Description</label><textarea name="description" required maxlength="32"></textarea></div>

            <p class="section-label">Specifications</p>
            <div id="specs-container">
                <div class="spec-row">
                    <input type="text" name="specs[0][key]" placeholder="Key">
                    <input type="text" name="specs[0][value]" placeholder="Value">
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">X</button>
                </div>
            </div>
            <button type="button" class="btn" style="margin-bottom:1rem;" onclick="addSpec()">Add specifications</button>
            <br>
            <button type="submit" class="btn btn-primary">Add product</button>
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
        <input type="text" name="specs[${specIndex}][key]" placeholder="Key">
        <span style="color:#aaa;font-size:13px;"></span>
        <input type="text" name="specs[${specIndex}][value]" placeholder="Value">
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