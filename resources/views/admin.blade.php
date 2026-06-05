@include('header')
<div id="main">
    <div class="panel-header">
        <h1 class="panel-title">Admin panel</h1>
        <span class="badge-admin">Admin</span>
    </div>

    @if(session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    {{-- PRODUCT LIST --}}
    <div class="card">
        <p class="section-label">Produkti</p>
        <table>
            <thead>
                <tr>
                    <th>Nosaukums</th>
                    <th>Kategorija</th>
                    <th>Cena</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <p class="product-name">{{ $product->name }}</p>
                        <p class="product-cat">{{ $product->manufacturer }}</p>
                    </td>
                    <td style="font-size:13px;color:#888;">{{ $product->category->category ?? '—' }}</td>
                    <td style="font-weight:500;font-size:13px;">€{{ number_format($product->price, 2) }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="{{ route('admin.specs', $product->id) }}" class="btn">Specifikācijas</a>
                            <form method="POST" action="{{ route('admin.destroy', $product->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Dzēst produktu?')">Dzēst</button>
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
        <p class="section-label">Pievienot produktu</p>
        <form method="POST" action="{{ route('admin.store') }}" id="add-form">
            @csrf
            <div class="form-grid">
                <div class="field"><label>Nosaukums</label><input type="text" name="name" required maxlength="32"></div>
                <div class="field"><label>Ražotājs</label><input type="text" name="manufacturer" required maxlength="64"></div>
                <div class="field"><label>Cena</label><input type="number" name="price" step="0.01" required></div>
                <div class="field"><label>Iepriekšējā cena</label><input type="number" name="last_price" step="0.01" required></div>
                <div class="field">
                    <label>Kategorija</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label>Attēla URL</label><input type="text" name="image_url" required></div>
            </div>
            <div class="field" style="margin-bottom:12px;"><label>Apraksts</label><textarea name="description" required maxlength="32"></textarea></div>

            <p class="section-label">Specifikācijas</p>
            <div id="specs-container">
                <div class="spec-row">
                    <input type="text" name="specs[0][key]" placeholder="Atslēga (piem. Svars)">
                    <span style="color:#aaa;font-size:13px;">→</span>
                    <input type="text" name="specs[0][value]" placeholder="Vērtība (piem. 500g)">
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">✕</button>
                </div>
            </div>
            <button type="button" class="btn" style="margin-bottom:1rem;" onclick="addSpec()">+ Pievienot spec</button>
            <br>
            <button type="submit" class="btn btn-primary">+ Pievienot produktu</button>
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
        <input type="text" name="specs[${specIndex}][key]" placeholder="Atslēga">
        <span style="color:#aaa;font-size:13px;">→</span>
        <input type="text" name="specs[${specIndex}][value]" placeholder="Vērtība">
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