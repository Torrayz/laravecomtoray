@extends('admin.layout')
@section('content')
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Order Summary</h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Order Code</td>
                        <td class="fw-semibold">{{ $order->order_code }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <button type="button" class="btn btn-link p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus">
                                @if($order->status == 0)
                                    <span class="badge bg-warning text-dark">Unprocessed</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-info text-dark">Confirmed</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-primary">Processed</span>
                                @elseif($order->status == 3)
                                    <span class="badge bg-danger">Pending</span>
                                @elseif($order->status == 4)
                                    <span class="badge bg-secondary">Shipping</span>
                                @elseif($order->status == 5)
                                    <span class="badge bg-success">Completed</span>
                                @endif
                                <small class="ms-1 text-primary">(Change)</small>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total</td>
                        <td class="fw-bold">${{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td>{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Note</td>
                        <td>{{ $order->note ?: '—' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-lg-8">
                <h5 class="fw-bold mb-3">Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetail as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ str_replace('-', ' ', ucwords($item->title)) }}</td>
                                <td>${{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <a href="{{ route('orders') }}" class="btn btn-outline-secondary btn-sm">Back to Orders</a>
        <form id="delete-order-form" action="{{ route('orderDelete', $order->order_code) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" onclick="confirmDeleteOrder()" class="btn btn-danger btn-sm">Delete Order</button>
    </div>
</div>

<div class="modal fade" id="modalUpdateStatus" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateStatusLabel">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('orderUpdateStatus', $order->order_code) }}" method="post">
                    @csrf
                    <div class="input-group">
                        <select class="form-select" name="status">
                            <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Unprocessed</option>
                            <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Confirmed</option>
                            <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Processed</option>
                            <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Pending</option>
                            <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>Shipping</option>
                            <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>Completed</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function confirmDeleteOrder() {
        torrayConfirm({
            title: 'Delete this order?',
            text: 'This order and its line items will be deleted permanently.',
            confirmText: 'Yes, delete',
            onConfirm: function () {
                document.getElementById('delete-order-form').submit();
            }
        });
    }
</script>
@endsection
