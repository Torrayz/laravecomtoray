@extends('admin.layout')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('producSave') }}" method="post" style="max-width: 680px;">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="wireless-mechanical-keyboard" value="{{ old('title') }}" required>
                <div class="form-text">Spaces are automatically converted to URL-friendly hyphens.</div>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="" selected disabled>Select Category</option>
                    @foreach (Auth::user()->shop->category as $item)
                        <option value="{{ $item->id }}" {{ old('category') == $item->id ? 'selected' : '' }}>
                            {{ str_replace('-', ' ', ucwords($item->name)) }}
                        </option>
                    @endforeach
                </select>
                @error('category') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" min="1" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="100" value="{{ old('price') }}" required>
                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-sm-6">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="10" value="{{ old('stock') }}" required>
                    @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="desc" class="form-label">Description</label>
                <textarea name="desc" id="desc" rows="3" class="form-control autosize @error('desc') is-invalid @enderror" placeholder="Product details..." required>{{ old('desc') }}</textarea>
                @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary float-end">Save &amp; Upload Images</button>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayAutosize('.autosize');

        const titleInput = document.getElementById('title');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let timer = null;

        titleInput.addEventListener('input', function () {
            titleInput.value = titleInput.value.replace(/\s+/g, '-').toLowerCase();
            clearTimeout(timer);
            const val = titleInput.value.trim();
            if (!val) return;

            timer = setTimeout(function () {
                fetch('{{ route("productCheck") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title: val })
                }).then(function (res) {
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
    });
</script>
@endsection
