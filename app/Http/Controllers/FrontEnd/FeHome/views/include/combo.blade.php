<div class="slider-carousel-wrap fl-wrap tet-nhat-den-noi-roi" >
    <div class="ls-combo fl-wrap" id="autoplay" data-slick='{"slidesToShow": 8, "slidesToScroll": 1,"autoplay": true ,
    "autoplaySpeed": 20000}'>
        @foreach ($lsCombo as $obj)
            <div class="slick-item anh-voi-ung">
                <div class="hotel-card fl-wrap title-sin_item chinh-sua-tiep-nha" >
                    <div class="geodir-category-img card-post vl-combo" style="border-radius: 10%">
                        <a href="{{ route('FeTour.Combo', ['alias' => @$obj['alias']]) }}"><img class="anh-ung" data-lazy="{{ getImg($obj['avatar']['relative_link']) }}" src="{{ getImg($obj['avatar']['relative_link']) }}" alt="{{ $obj['name'] }}"></a>
                        {{-- <div class="geodir-category-opt">
                        </div> --}}
                    </div>
                    <h4 class="title-sin_map vl-combo-title mb-3" style="margin-left: 10px;"><a href="{{ route('FeTour.Combo', ['alias' => @$obj['alias']]) }}"><p class="combo-su-kien">{{ $obj['name'] }}</p></a></h4>

                </div>

            </div>
        @endforeach
    </div>
</div>