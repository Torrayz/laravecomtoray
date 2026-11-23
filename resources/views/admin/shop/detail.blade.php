@extends('admin.layout')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('shopUpdate') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-4">
                    <label for="file-upload" class="tr-upload-box d-block" id="drop-zone">
                        <img id="file-image" src="{{ asset('shop/' . $shop->path) }}" alt="Shop Logo" class="tr-upload-preview">
                        <div class="fw-semibold">Change Shop Logo</div>
                        <small class="text-muted d-block mb-2">Click or drag image here</small>
                        <input id="file-upload" type="file" name="path" accept="image/*" class="form-control form-control-sm mt-2">
                        @error('path')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </label>
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name_shop" class="form-label">Shop Name</label>
                        <input type="text" name="name_shop" id="name_shop" class="form-control @error('name_shop') is-invalid @enderror" value="{{ old('name_shop', $shop->name_shop) }}" required autofocus>
                        @error('name_shop') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $shop->phone) }}" required>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $shop->address) }}" required>
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea name="desc" id="desc" rows="3" class="form-control autosize @error('desc') is-invalid @enderror" required>{{ old('desc', $shop->desc) }}</textarea>
                        @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Update Shop</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayAutosize('.autosize');
        torrayUploadPreview('#file-upload', '#file-image', '#drop-zone');
    });
</script>
@endsection
