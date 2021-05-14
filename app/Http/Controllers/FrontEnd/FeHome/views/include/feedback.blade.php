<div class="slider-carousel-wrap text-carousel-wrap fl-wrap">
    <div class="swiper-button-prev sw-btn">
        <i class="fa fa-long-arrow-left"></i>
    </div>
    <div class="swiper-button-next sw-btn">
        <i class="fa fa-long-arrow-right"></i>
    </div>
    <div class="text-carousel single-carousel fl-wrap">
    @foreach ($lsFeedback as $obj)

        <div class="slick-item">
            <div class="text-carousel-item">
                <div class="popup-avatar">
                    <img
                        src="{{ getImg(@$obj['avatar']['relative_link']) }}"
                        alt="{{ value_show($obj['name']) }}">
                </div>
                <div class="listing-rating card-popup-rainingvis"
                    data-starrating2="5"></div>
                <div class="review-owner fl-wrap">
                    {{ \App\Elibs\Helper::showContent($obj['name']) }} - <span>{{ \App\Elibs\Helper::showContent($obj['chuc_danh']) }}</span>
                </div>
                <p>"{{ \App\Elibs\Helper::showContent($obj['content']) }}"</p>
            </div>
        </div>
    @endforeach
    </div>
</div>