<div class="list-single-hero-rating">
    @if(isset($obj['ratings']))
    <div class="rate-class-name">
        <div class="score"><strong>Very Good</strong>{{ $obj['ratings'] }} Đánh giá </div>
        <span>{{ @$obj['score'] }}</span>
    </div>
    @endif
    <!-- list-single-hero-rating-list-->
    <div class="list-single-hero-rating-list">
        @if(isset($obj['histogram']))
        @foreach($obj['histogram'] as $k => $his)
        <!-- rate item-->
        <div class="rate-item fl-wrap">
            <div class="rate-item-title fl-wrap">
                <span>
                    @if($k == 1)
                        <i class="fa fa-star"></i>
                    @elseif($k == 2)
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    @elseif($k == 3)
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    @elseif($k == 4)
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    @elseif($k == 5)
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    @endif
                </span>
            </div>
            <div class="rate-item-bg" data-percent="100%">
                <div class="rate-item-line color-bg"></div>
            </div>
            <div class="rate-item-percent">{{ $his }}</div>
        </div>
        <!-- rate item end-->
        @endforeach
        @endif
    </div>
    <!-- list-single-hero-rating-list end-->
</div>