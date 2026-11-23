@push('css')
<style>
    .about-text h4 {
        font-weight: 400;
        width: 80%;
        margin: 0 auto;
    }
    @media screen and (max-width:576px) {
        .about-text h4 {
            width: 100%;
            font-size: 1rem;
        }
    }
</style>
@endpush

<div class="py-md-5 py-3">
    <div class="container about-text text-center">
        <h1 class="fw-bold mt-4">About Us</h1>
        <h4 class="text-muted mt-2">{{ $shop->desc }}</h4>
    </div>

    <div class="container mt-4">
        <div class="tr-about-banner text-center">
            <p class="text-uppercase small fw-semibold mb-2 opacity-75">{{ $shop->name_shop }}</p>
            <h2 class="fw-bold mb-2">Curated Everyday Goods</h2>
            <p class="mb-0 opacity-75">Direct fulfillment, transparent stock availability, and straightforward order tracking.</p>
        </div>
    </div>
</div>
