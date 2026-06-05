@include('header')

<style>
  .card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 1.25rem; }
  .section-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #888; margin-bottom: 12px; }
  .spec-row { display: flex; gap: 8px; margin-bottom: 8px; align-items: center; }
  .spec-row input { flex: 1; height: 36px; padding: 0 10px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa; font-size: 13px; outline: none; font-family: 'Poppins', sans-serif; }
  .spec-row input:focus { border-color: #4CAF50; }
  .btn { display: inline-flex; align-items: center; gap: 5px; height: 32px; padding: 0 12px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; border: 1px solid #ddd; background: #fff; color: #333; }
  .btn-danger { background: #fff5f5; color: #e53935; border-color: #fca5a5; }
  .btn-primary { background: #4CAF50; color: #fff; border-color: #4CAF50; }
  .back-link { font-size: 13px; color: #4CAF50; text-decoration: none; display: inline-block; margin-bottom: 1rem; }
</style>

<div id="main">
    <a href="{{ route('admin.index') }}" class="back-link">← Atpakaļ uz admin paneli</a>
    <h1 style="font-size:18px;font-weight:500;margin-bottom:1.5rem;">Specifikācijas — {{ $product->name }}</h1>

    <div class="card">
        <p class="section-label">Rediģēt specifikācijas</p>
        <form method="POST" action="{{ route('admin.specs.update', $product->id) }}">
            @csrf
            <div id="specs-container">
                @foreach($product->specifications as $i => $spec)
                <div class="spec-row">
                    <input type="text" name="specs[{{ $i }}][key]" value="{{ $spec->key }}" placeholder="Atslēga">
                    <span style="color:#aaa;font-size:13px;">→</span>
                    <input type="text" name="specs[{{ $i }}][value]" value="{{ $spec->value }}" placeholder="Vērtība">
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">✕</button>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn" style="margin-bottom:1rem;" onclick="addSpec()">+ Pievienot spec</button>
            <br>
            <button type="submit" class="btn btn-primary">Saglabāt</button>
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