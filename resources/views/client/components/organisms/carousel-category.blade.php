<div class="tr-category-strip mt-3">
    <div class="tr-category-track" id="categoryTrack">
        @foreach ($category as $item)
            <div class="tr-category-slide">
                <x-molecules.category-card :path="$item->path" :name="$item->name" width="280px" />
            </div>
        @endforeach
    </div>
</div>
