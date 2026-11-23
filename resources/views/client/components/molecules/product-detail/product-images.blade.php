@push('css')
    <link rel="stylesheet" href="{{ asset('client/components/molecules/product-detail/product-images/style.css') }}">
@endpush

<div class="tr-gallery" data-product-gallery>
    <div class="tr-gallery-stage">
        @if($dataProductimages->isNotEmpty())
            <img src="{{ asset('shop/products/' . $dataProductimages->first()->path) }}" alt="Product preview" data-gallery-main>
            @if($dataProductimages->count() > 1)
                <button type="button" class="tr-gallery-nav prev" data-gallery-prev aria-label="Previous image">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="tr-gallery-nav next" data-gallery-next aria-label="Next image">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        @else
            <div class="text-muted small">No image available</div>
        @endif
    </div>

    @if($dataProductimages->count() > 1)
        <div class="tr-gallery-thumbs">
            @foreach($dataProductimages as $row)
                <button type="button" class="tr-gallery-thumb {{ $loop->first ? 'active' : '' }}" data-gallery-thumb data-src="{{ asset('shop/products/' . $row->path) }}">
                    <img src="{{ asset('shop/products/' . $row->path) }}" alt="Thumbnail">
                </button>
            @endforeach
        </div>
    @endif
</div>

@push('js')
    <script src="{{ asset('client/components/molecules/product-detail/product-images/main.js') }}"></script>
@endpush
