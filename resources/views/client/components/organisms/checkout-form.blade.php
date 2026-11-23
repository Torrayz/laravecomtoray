<div class="container py-4">
    <form action="{{ route('clientCheckoutSave') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror bg-transparent" placeholder="Customer name" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone number</label>
            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror bg-transparent" placeholder="0812xxxxxxxx" value="{{ old('phone') }}" required>
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror bg-transparent" placeholder="Shipping address" value="{{ old('address') }}" required>
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Note (Optional)</label>
            <textarea name="note" id="note" rows="2" class="form-control autosize @error('note') is-invalid @enderror bg-transparent" placeholder="Delivery instructions...">{{ old('note') }}</textarea>
            @error('note')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary float-end px-4">Place Order</button>
    </form>
</div>
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        torrayAutosize('#note');
    });
</script>
@endpush
