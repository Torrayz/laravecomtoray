<table id="cart" class="table table-hover align-middle">
    <thead>
        <tr>
            <th style="width:45%">Product</th>
            <th style="width:15%">Price</th>
            <th style="width:20%">Quantity</th>
            <th style="width:15%">Subtotal</th>
            @if(!request()->routeIs('clientCheckout'))
                <th style="width:5%">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @php $total = 0 @endphp
    @if(session('cart'))
        @foreach((array) session('cart') as $id => $details)
            @php $total += $details['price'] * $details['quantity'] @endphp
            <tr class="item-cart">
                <td class="fw-semibold">{{ str_replace('-', ' ', ucwords($details['title'])) }}</td>
                <td>${{ $details['price'] }}</td>
                <td>
                    @if(request()->routeIs('clientCheckout'))
                       x{{ $details['quantity'] }}
                    @else
                        <div class="tr-qty-group">
                            <button type="button" onclick="trCartMinus(this)" class="btn btn-outline-primary btn-sm">-</button>
                            <input type="text" value="{{ $details['quantity'] }}" data-id="{{ $id }}" data-price="{{ $details['price'] }}" class="form-control form-control-sm tr-qty-value" readonly>
                            <button type="button" onclick="trCartPlus(this)" data-stock="{{ $details['product_stock'] }}" class="btn btn-outline-primary btn-sm">+</button>
                        </div>
                    @endif
                </td>
                <td>$<span class="product-subtotal">{{ $details['price'] * $details['quantity'] }}</span></td>
                @if(!request()->routeIs('clientCheckout'))
                    <td class="actions">
                        <button type="button" class="btn btn-danger btn-sm" onclick="trCartDelete({{ $id }}, this)" aria-label="Remove item"><i class="bi bi-trash"></i></button>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
        <tr>
            <td>
            @if(!request()->routeIs('clientCheckout'))
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                </a>
            @endif
            </td>
            <td colspan="2"></td>
            <td>
                <strong>Total : $<span class="cart-total">{{ $total }}</span></strong>
            </td>
            @if(!request()->routeIs('clientCheckout'))
                <td>
                    @if(count((array) session('cart')) > 0)
                        <a href="{{ route('clientCheckout') }}" class="btn btn-primary btn-sm">Checkout</a>
                    @endif
                </td>
            @endif
        </tr>
    </tfoot>
</table>
