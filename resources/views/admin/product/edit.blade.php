@extends('admin.layout')
@section('css')
<style>
    .product-image-item {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid #e3e6e4;
        background: #fff;
    }
    .product-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <form action="{{ route('productEditSave', ['product' => $product->title, 'id' => $product->id]) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $product->title) }}" required>
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            @foreach (Auth::user()->shop->category as $item)
                                <option value="{{ $item->id }}" {{ $product->category_id == $item->id ? 'selected' : '' }}>
                                    {{ str_replace('-', ' ', ucwords($item->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" min="1" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea name="desc" id="desc" rows="3" class="form-control autosize @error('desc') is-invalid @enderror" required>{{ old('desc', $product->desc) }}</textarea>
                        @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-end">Save Changes</button>
                </form>
            </div>

            <div class="col-lg-6">
                <label class="form-label fw-semibold">Product Gallery</label>
                <div class="tr-upload-box mb-3" id="galleryDropZone">
                    <i class="bi bi-images fs-2 text-secondary d-block mb-1"></i>
                    <div class="fw-semibold">Select or Drop Product Images</div>
                    <small class="text-muted d-block mb-2">JPEG, PNG, WebP up to 4MB each</small>
                    <input type="file" id="galleryUploadInput" accept="image/*" multiple class="form-control form-control-sm">
                </div>
                <div class="row g-3" id="productImagesContainer"></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center">
        <a href="{{ route('products') }}" class="btn btn-outline-secondary btn-sm">Back to Products</a>
        <form id="delete-product-form" action="{{ route('productDelete', $product->id) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" onclick="confirmDeleteProduct()" class="btn btn-danger btn-sm">Delete Product</button>
    </div>
</div>
@endsection
@section('js')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productId = {{ $product->id }};
    const originalTitle = @json($product->title);
    const imagesBaseUrl = @json(asset('shop/products'));

    function loadProductImages() {
        fetch('{{ route("productGetImages") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_products: productId })
        })
        .then(res => res.json())
        .then(items => {
            const container = document.getElementById('productImagesContainer');
            container.innerHTML = '';
            items.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-4 col-sm-3';
                col.innerHTML =
                    '<div class="product-image-item">' +
                    '  <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="z-index: 5;"><i class="bi bi-trash"></i></button>' +
                    '  <img src="' + imagesBaseUrl + '/' + encodeURIComponent(item.path) + '" alt="Product image">' +
                    '</div>';
                col.querySelector('button').addEventListener('click', function () {
                    confirmDeleteImage(item.path);
                });
                container.appendChild(col);
            });
        });
    }

    function uploadFiles(files) {
        Array.from(files).forEach(file => {
            const formData = new FormData();
            formData.append('id_product', productId);
            formData.append('file', file);
            fetch('{{ route("productAddImagesSave") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData
            }).then(res => {
                if (res.ok) {
                    loadProductImages();
                    torrayToast('Image uploaded', 'success');
                } else {
                    torrayToast('Failed to upload image', 'error');
                }
            });
        });
    }

    function confirmDeleteImage(filename) {
        torrayConfirm({
            title: 'Delete image?',
            text: 'This image will be permanently removed.',
            confirmText: 'Delete',
            onConfirm: function () {
                fetch('{{ route("productDeleteImages") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ filename: filename })
                }).then(res => {
                    if (res.ok) {
                        loadProductImages();
                        torrayToast('Image deleted', 'success');
                    }
                });
            }
        });
    }

    function confirmDeleteProduct() {
        torrayConfirm({
            title: 'Delete this product?',
            text: 'This product and all its gallery images will be permanently deleted.',
            confirmText: 'Yes, delete',
            onConfirm: function () {
                document.getElementById('delete-product-form').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        torrayAutosize('.autosize');
        loadProductImages();

        const titleInput = document.getElementById('title');
        let timer = null;
        titleInput.addEventListener('input', function () {
            titleInput.value = titleInput.value.replace(/\s+/g, '-').toLowerCase();
            clearTimeout(timer);
            const val = titleInput.value.trim();
            if (!val) return;
            timer = setTimeout(function () {
                if (val === originalTitle) {
                    titleInput.classList.remove('is-invalid');
                    titleInput.classList.add('is-valid');
                    return;
                }
                fetch('{{ route("productCheck") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title: val })
                }).then(res => {
                    if (res.status === 200) {
                        titleInput.classList.add('is-invalid');
                        titleInput.classList.remove('is-valid');
                    } else if (res.status === 201) {
                        titleInput.classList.remove('is-invalid');
                        titleInput.classList.add('is-valid');
                    }
                });
            }, 150);
        });

        const uploadInput = document.getElementById('galleryUploadInput');
        const dropZone = document.getElementById('galleryDropZone');
        uploadInput.addEventListener('change', function () {
            if (uploadInput.files.length > 0) {
                uploadFiles(uploadInput.files);
                uploadInput.value = '';
            }
        });
        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropZone.classList.add('is-dragover');
        });
        dropZone.addEventListener('dragleave', function () {
            dropZone.classList.remove('is-dragover');
        });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropZone.classList.remove('is-dragover');
            if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                uploadFiles(e.dataTransfer.files);
            }
        });
    });
</script>
@endsection
