@extends('admin.layout')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('categorySave') }}" method="post" enctype="multipart/form-data" style="max-width: 560px;">
            @csrf
            <div class="mb-3">
                <label for="file-upload" class="tr-upload-box d-block" id="drop-zone">
                    <img id="file-image" src="#" alt="Preview" class="tr-upload-preview d-none">
                    <i class="bi bi-image fs-1 text-secondary d-block mb-2"></i>
                    <div class="fw-semibold">Upload Category Cover</div>
                    <small class="text-muted d-block mb-2">PNG, JPG, WebP up to 4MB</small>
                    <input id="file-upload" type="file" name="path" accept="image/*" class="form-control form-control-sm mt-2" required>
                    @error('path')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror
                </label>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="electronics-accessories" value="{{ old('name') }}" required autofocus>
                <div class="form-text">Spaces are automatically converted to URL-friendly hyphens.</div>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary float-end">Create Category</button>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayUploadPreview('#file-upload', '#file-image', '#drop-zone');

        const nameInput = document.getElementById('name');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let timer = null;

        nameInput.addEventListener('input', function () {
            nameInput.value = nameInput.value.replace(/\s+/g, '-').toLowerCase();
            clearTimeout(timer);
            const value = nameInput.value.trim();
            if (!value) return;

            timer = setTimeout(function () {
                fetch('{{ route("categoryCheck") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: value })
                }).then(function (res) {
                    if (res.status === 200) {
                        nameInput.classList.add('is-invalid');
                        nameInput.classList.remove('is-valid');
                    } else if (res.status === 201) {
                        nameInput.classList.remove('is-invalid');
                        nameInput.classList.add('is-valid');
                    }
                });
            }, 150);
        });
    });
</script>
@endsection
