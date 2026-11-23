@extends('admin.layout')
@section('button')
    <a href="{{ route('categoryCreate') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Create Category
    </a>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row g-3">
            @forelse ($category as $item)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 position-relative overflow-hidden">
                        <form id="delete-category-{{ $item->id }}" action="{{ route('categoryDelete', $item->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" style="z-index: 5;" onclick="confirmDeleteCategory({{ $item->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <img src="{{ asset('shop/products/' . $item->path) }}" alt="{{ $item->name }}" class="card-img-top" style="height: 190px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title m-0 fw-semibold">{{ str_replace('-', ' ', ucwords($item->name)) }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">No categories created yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function confirmDeleteCategory(id) {
        torrayConfirm({
            title: 'Delete category?',
            text: 'All products inside this category may also be affected.',
            confirmText: 'Yes, delete',
            onConfirm: function () {
                document.getElementById('delete-category-' + id).submit();
            }
        });
    }
</script>
@endsection
