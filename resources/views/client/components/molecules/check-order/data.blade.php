@push('css')
<style>
    .order-info > tbody > tr {
        height: 35px !important;
    }
</style>
@endpush
<div class="container py-3">
    <div class="card bg-white border">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 col-12">
                    <table class="order-info">
                        <tr>
                            <td><b>Status</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td><b>Order Code</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td><b><u>{{ $order->order_code }}</u></b></td>
                        </tr>
                        <tr>
                            <td><b>Total</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td><b><u>${{ $order->total }}</u></b></td>
                        </tr>
                        <tr>
                            <td><b>Name</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td>{{ $order->name }}</td>
                        </tr>
                        <tr>
                            <td><b>Phone</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td>{{ $order->phone }}</td>
                        </tr>
                        <tr>
                            <td><b>Address</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td>{{ $order->address }}</td>
                        </tr>
                        <tr>
                            <td><b>Note</b></td>
                            <td>&nbsp; : &nbsp;</td>
                            <td>{{ $order->note }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-8 col-12">
                    <h4>Order Detail</h4>
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Title</td>
                                    <td>Price</td>
                                    <td>Quantity</td>
                                    <td>Sub Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDetail as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ str_replace('-', ' ', ucwords($item->title)) }}</td>
                                    <td>${{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ $item->price * $item->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-block d-md-none">
                        @foreach($orderDetail as $row)
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold">{{ str_replace('-', ' ', ucwords($row->title)) }}</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Price</small>
                                            <p class="fw-bold mb-1">${{ $row->price }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Sub Total</small>
                                            <p class="fw-bold mb-1">${{ $row->price * $row->quantity }}</p>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted">Quantity</small>
                                            <p class="fw-bold mb-0">x {{ $row->quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card bg-white mt-3 border">
        <div class="card-body d-flex justify-content-between">
            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Home
            </a>
            <a href="{{ route('clientProducts') }}" class="btn btn-sm btn-primary">
                Browse More Products
            </a>
        </div>
    </div>
</div>
