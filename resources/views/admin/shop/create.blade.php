<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/torray-admin.css') }}">
</head>
<body class="py-5">
    <div class="container">
        <div class="card mx-auto" style="max-width: 820px;">
            <div class="card-header bg-white py-3">
                <h4 class="card-title m-0 fw-bold">Set Up Your Shop</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('shopCreate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label for="file-upload" class="tr-upload-box d-block" id="drop-zone">
                                <img id="file-image" src="#" alt="Preview" class="tr-upload-preview d-none">
                                <i class="bi bi-cloud-arrow-up fs-1 text-secondary d-block mb-2"></i>
                                <div class="fw-semibold">Upload Shop Logo</div>
                                <small class="text-muted d-block mb-2">PNG, JPG, WebP up to 4MB</small>
                                <input id="file-upload" type="file" name="path" accept="image/*" class="form-control form-control-sm mt-2" required>
                                @error('path')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </label>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="name_shop" class="form-label">Shop Name</label>
                                <input type="text" name="name_shop" id="name_shop" class="form-control @error('name_shop') is-invalid @enderror" placeholder="Torray Store" value="{{ old('name_shop') }}" required autofocus>
                                @error('name_shop') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="0812xxxxxxxx" value="{{ old('phone') }}" required>
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Store address" value="{{ old('address') }}" required>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Description</label>
                                <textarea name="desc" id="desc" rows="3" class="form-control autosize @error('desc') is-invalid @enderror" placeholder="Describe your storefront..." required>{{ old('desc') }}</textarea>
                                @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary float-end">Create Shop</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/torray-ui.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            torrayAutosize('#desc');
            torrayUploadPreview('#file-upload', '#file-image', '#drop-zone');
        });
    </script>
</body>
</html>
