@php $total = 0 @endphp
@if(session('cart'))
    @foreach((array) session('cart') as $id => $details)
    @php $total += $details['price'] * $details['quantity'] @endphp
    <div class="card item-cart mt-2 position-relative">
        @if(!request()->routeIs('clientCheckout'))
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="trCartDelete({{ $id }}, this)" aria-label="Remove item"><i class="bi bi-trash"></i></button>
        @endif
        <div class="card-body">
            <h6 class="fw-bold">{{ str_replace('-', ' ', ucwords($details['title'])) }}</h6>
            <div class="row g-2">
                <div class="col-6">
                    <small class="text-muted d-block">Price</small>
                    <span class="fw-bold">${{ $details['price'] }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Subtotal</small>
                    <span class="fw-bold">$<span class="product-subtotal">{{ $details['price'] * $details['quantity'] }}</span></span>
                </div>
                <div class="col-12 mt-2">
                    <small class="text-muted d-block mb-1">Quantity {{ request()->routeIs('clientCheckout') ? ': x'.$details['quantity'] : '' }}</small>
                    @if(!request()->routeIs('clientCheckout'))
                        <div class="tr-qty-group">
                            <button type="button" onclick="trCartMinus(this)" class="btn btn-outline-primary btn-sm">-</button>
                            <input type="text" value="{{ $details['quantity'] }}" data-id="{{ $id }}" data-price="{{ $details['price'] }}" class="form-control form-control-sm tr-qty-value" readonly>
                            <button type="button" onclick="trCartPlus(this)" data-stock="{{ $details['product_stock'] }}" class="btn btn-outline-primary btn-sm">+</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

<div class="card mt-3 mb-4">
    <div class="card-body">
        <h5><strong>Total : $<span class="cart-total">{{ $total }}</span></strong></h5>
        @if(!request()->routeIs('clientCheckout'))
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                </a>
                @if(count((array) session('cart')) > 0)
                    <a href="{{ route('clientCheckout') }}" class="btn btn-sm btn-primary">Checkout</a>
                @endif
            </div>
        @endif
    </div>
</div>
