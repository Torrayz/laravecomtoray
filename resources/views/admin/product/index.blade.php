@extends('admin.layout')
@section('button')
    <a href="{{ route('productCreate') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="m-0 fw-bold">Products</h5>
        <input type="search" id="productSearchInput" class="form-control form-control-sm" style="max-width: 240px;" placeholder="Filter products...">
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableProducts">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $row)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ str_replace('-', ' ', ucwords($row->title)) }}</td>
                        <td>{{ $row->category ? str_replace('-', ' ', ucwords($row->category->name)) : '—' }}</td>
                        <td>${{ number_format($row->price, 0, ',', '.') }}</td>
                        <td>{{ $row->stock }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('productEdit', $row->title) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No products found.</td>
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
        torrayTableFilter('#productSearchInput', '#tableProducts');
    });
</script>
@endsection
