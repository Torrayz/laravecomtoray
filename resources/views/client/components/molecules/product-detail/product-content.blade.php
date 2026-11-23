@push('css')
<style>
    .autosize:focus { box-shadow: none !important; }
    #count { width: 84px; text-align: center; }
    @media screen and (max-width: 767px) {
        #input_div { display: flex; justify-content: space-between; gap: 10px; }
        .add-to-cart, #count { width: 100% !important; }
    }
</style>
@endpush

<div>
    <h3 class="mt-md-0 mt-4 fw-bold">{{ str_replace('-', ' ', ucwords($dataProductContent->title)) }}</h3>
    <hr/>
    <h5 class="fw-bold text-dark">${{ $dataProductContent->price }}</h5>
    @if($dataProductContent->category)
        <p>Category :
            <a href="{{ route('clientCategoryProducts', $dataProductContent->category->name) }}">
                {{ str_replace('-', ' ', ucwords($dataProductContent->category->name)) }}
            </a>
        </p>
    @endif
    <p class="mb-1"><b>Description</b></p>
    <div class="mb-3">
        <textarea class="form-control autosize bg-transparent border-0 p-0" readonly>{{ $dataProductContent->desc }}</textarea>
    </div>
    <p><b>Stock : {{ $dataProductContent->stock }}</b></p>

    @if($dataProductContent->stock > 0)
        <div id="input_div" class="d-flex align-items-center gap-2">
            <button type="button" id="moins" class="btn btn-outline-primary">-</button>
            <input type="text" value="1" id="count" class="form-control" readonly>
            <button type="button" id="plus" data-stok="{{ $dataProductContent->stock }}" class="btn btn-outline-primary">+</button>
        </div>
        <button type="button" class="btn btn-primary mt-4 add-to-cart px-4" data-id-product="{{ $dataProductContent->id }}" data-quantity="1">
            Add to cart
        </button>
    @else
        <span class="badge bg-secondary">Out of Stock</span>
    @endif
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayAutosize('.autosize');

        const countEl = document.getElementById('count');
        const plusBtn = document.getElementById('plus');
        const minusBtn = document.getElementById('moins');
        const addBtn = document.querySelector('.add-to-cart');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!countEl || !plusBtn || !minusBtn || !addBtn) return;

        let count = 1;
        const maxStock = parseInt(plusBtn.getAttribute('data-stok'), 10) || 0;

        plusBtn.addEventListener('click', function () {
            if (count < maxStock) {
                count += 1;
                countEl.value = count;
                addBtn.setAttribute('data-quantity', count);
            }
        });

        minusBtn.addEventListener('click', function () {
            if (count > 1) {
                count -= 1;
                countEl.value = count;
                addBtn.setAttribute('data-quantity', count);
            }
        });

        addBtn.addEventListener('click', function () {
            const productId = addBtn.getAttribute('data-id-product');
            const quantity = parseInt(addBtn.getAttribute('data-quantity'), 10) || 1;

            fetch('{{ route("clientAddToCart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
            .then(async res => {
                const data = await res.json();
                const cartCountEl = document.getElementById('cartCount');
                if (cartCountEl && typeof data.cartCount !== 'undefined') {
                    cartCountEl.textContent = data.cartCount;
                }
                count = 1;
                countEl.value = 1;
                addBtn.setAttribute('data-quantity', 1);

                if (res.status === 200) {
                    torrayToast('Added to cart.', 'success');
                } else if (res.status === 201) {
                    torrayToast('Updated cart quantity.', 'success');
                } else {
                    torrayToast('Maximum stock quantity reached.', 'error');
                }
            })
            .catch(() => {
                torrayToast('Could not update cart.', 'error');
            });
        });
    });
</script>
@endpush
