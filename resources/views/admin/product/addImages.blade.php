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
<div class="card">
    <div class="card-body p-4">
        <div class="tr-upload-box mb-4" id="galleryDropZone">
            <i class="bi bi-cloud-arrow-up fs-1 text-secondary d-block mb-1"></i>
            <div class="fw-semibold">Upload Product Gallery Images</div>
            <small class="text-muted d-block mb-2">JPEG, PNG, WebP up to 4MB each</small>
            <input type="file" id="galleryUploadInput" accept="image/*" multiple class="form-control form-control-sm mx-auto" style="max-width: 340px;">
        </div>

        <div class="row g-3" id="productImagesContainer"></div>

        <hr class="my-4">
        <a href="{{ route('products') }}" class="btn btn-primary float-end">Done</a>
    </div>
</div>
@endsection
@section('js')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productId = {{ $product->id }};
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
                col.className = 'col-6 col-md-3';
                col.innerHTML =
                    '<div class="product-image-item">' +
                    '  <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" style="z-index: 5;"><i class="bi bi-trash"></i></button>' +
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

    document.addEventListener('DOMContentLoaded', function () {
        loadProductImages();
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
