@extends('admin.layout')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="tr-stat-card">
            <div class="tr-stat-icon teal"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="text-muted small fw-semibold">Completed Sales</div>
                <div class="fs-5 fw-bold">${{ number_format($sales, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="tr-stat-card">
            <div class="tr-stat-icon indigo"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="text-muted small fw-semibold">Completed Orders</div>
                <div class="fs-5 fw-bold">{{ $order }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="tr-stat-card">
            <div class="tr-stat-icon emerald"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="text-muted small fw-semibold">Products</div>
                <div class="fs-5 fw-bold">{{ $product }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('category') }}" class="text-decoration-none text-reset">
            <div class="tr-stat-card">
                <div class="tr-stat-icon amber"><i class="bi bi-tags"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Categories</div>
                    <div class="fs-5 fw-bold">{{ $category }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="m-0 fw-bold">Recent Orders</h5>
        <input type="search" id="orderSearch" class="form-control form-control-sm" style="max-width: 240px;" placeholder="Filter orders...">
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableOrdersHome">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newOrder as $row)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $row->order_code }}</td>
                        <td>{{ $row->name }}</td>
                        <td>${{ number_format($row->total, 0, ',', '.') }}</td>
                        <td>
                            @if($row->status == 0)
                                <span class="badge bg-warning text-dark">Unprocessed</span>
                            @elseif($row->status == 1)
                                <span class="badge bg-info text-dark">Confirmed</span>
                            @elseif($row->status == 2)
                                <span class="badge bg-primary">Processed</span>
                            @elseif($row->status == 3)
                                <span class="badge bg-danger">Pending</span>
                            @elseif($row->status == 4)
                                <span class="badge bg-secondary">Shipping</span>
                            @elseif($row->status == 5)
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('orderDetail', $row->order_code) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayTableFilter('#orderSearch', '#tableOrdersHome');
    });
</script>
@endsection
