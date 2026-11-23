@push('css')
<style>
    .tr-qty-group {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .tr-qty-value {
        width: 56px;
        text-align: center;
        font-weight: 600;
    }
</style>
@endpush
<div class="container py-2">
    <div class="d-none d-md-block">
        <x-molecules.carts.desktop />
    </div>
    <div class="d-block d-md-none">
        <x-molecules.carts.mobile />
    </div>
</div>
@push('js')
<script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function syncCartTotal(total) {
            document.querySelectorAll('.cart-total').forEach(el => {
                el.textContent = total;
            });
        }

        function updateQuantity(productId, quantity, rowEl, price) {
            fetch('{{ route("clientUpdateCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
            .then(res => res.json())
            .then(data => {
                if (typeof data.total !== 'undefined') {
                    syncCartTotal(data.total);
                }
                const sub = rowEl.querySelector('.product-subtotal');
                if (sub) {
                    sub.textContent = price * quantity;
                }
            });
        }

        window.trCartPlus = function (btn) {
            const group = btn.closest('.tr-qty-group');
            const input = group.querySelector('.tr-qty-value');
            const row = btn.closest('.item-cart');
            const stock = parseInt(btn.getAttribute('data-stock'), 10);
            const id = parseInt(input.getAttribute('data-id'), 10);
            const price = parseInt(input.getAttribute('data-price'), 10);
            let qty = parseInt(input.value, 10) || 1;

            if (qty < stock) {
                qty += 1;
                input.value = qty;
                updateQuantity(id, qty, row, price);
            } else {
                torrayToast('Maximum available stock reached.', 'error');
            }
        };

        window.trCartMinus = function (btn) {
            const group = btn.closest('.tr-qty-group');
            const input = group.querySelector('.tr-qty-value');
            const row = btn.closest('.item-cart');
            const id = parseInt(input.getAttribute('data-id'), 10);
            const price = parseInt(input.getAttribute('data-price'), 10);
            let qty = parseInt(input.value, 10) || 1;

            if (qty > 1) {
                qty -= 1;
                input.value = qty;
                updateQuantity(id, qty, row, price);
            }
        };

        window.trCartDelete = function (id, btn) {
            const row = btn.closest('.item-cart');
            fetch('{{ route("clientDeleteCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if (typeof data.total !== 'undefined') {
                    syncCartTotal(data.total);
                }
                const countEl = document.getElementById('cartCount');
                if (countEl && typeof data.cartCount !== 'undefined') {
                    countEl.textContent = data.cartCount;
                }
                if (row) row.remove();
                torrayToast('Item removed from cart.', 'success');
            });
        };
    })();
</script>
@endpush
