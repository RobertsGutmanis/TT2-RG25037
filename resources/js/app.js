document.getElementById('nav-toggle').addEventListener('click', function () {
    document.getElementById('nav-links').classList.toggle('open');
});

document.querySelectorAll('.product-button[id^="add-"]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const productId = this.id.replace('add-', '');
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/cart/add/' + productId;
        form.innerHTML = '<input type="hidden" name="_token" value="' + token + '">';
        document.body.appendChild(form);
        alert('Added to cart!');
        form.submit();
    });
});

document.querySelectorAll('[data-alert]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        alert(this.getAttribute('data-alert'));
    });
});
