@if(!$agent->isMobile())
<div class="col-md-4 col-xl-3 mb-3">
    <article class="geodir-category-listing fl-wrap">
        <div class="geodir-category-img">
            @if(isset($countdown) && $countdown === true)
                <div class="sale-window big-sale counter_tour counter-{{ $obj['_id'] }}" data-time="{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}" data-id="counter-{{ $obj['_id'] }}" id="counter-{{ $obj['_id'] }}"></div>
                {{--<script>
                    updateTimer("{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}", 'counter-{{ $obj["_id"] }}')
                </script>--}}
            @endif
            <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"><img
                        src="{{ \App\Http\Models\Media::getImageSrc($obj['avatar']['relative_link']) }}"
                        alt="{{ \App\Elibs\Helper::showContent($obj['name']) }}"></a>
            <div class="geodir-category-opt">
                <div class="listing-rating card-popup-rainingvis"
                     data-starrating2="{{ @$obj['score'] }}"></div>
                @if(isset($obj['score']))
                    <div class="rate-class-name">
                        @if(isset($obj['ratings']))
                            <div class="score">
                                <strong>Very Good</strong>{{ $obj['ratings'] }} Đánh giá
                            </div>
                        @endif
                        <span>{{ $obj['score'] }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="geodir-category-content fl-wrap">
            <div class="geodir-category-content-title fl-wrap">
                <div class="geodir-category-content-title-item">
                    <h3 class="title-sin_map">
                        <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ \App\Elibs\Helper::showContent($obj['name']) }}</a>
                    </h3>
                    {{-- <div class="geodir-category-location fl-wrap sp-line-1"style="text-align: left;">
                            @if(!empty($obj['dia_diem_den']))
                                @foreach($obj['dia_diem_den'] as $ls => $location)
                                    <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                       class="me-2" style="font-size: 12.5px" >
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}
                                    </a>
                                @endforeach
                            @endif
                    </div> --}}
                </div>
            </div>
            {{--<p>{{ \App\Elibs\Helper::showContent(@$obj['mo_ta_ngan']) }}</p>--}}
            <ul class="facilities-list fl-wrap">
                <li class="thong-tin css-thong-tin"><i class="fal fa-calendar-alt"></i>
                    @if(isset($groupTourNhieuLichKhoiHanh[$obj['_id']]))
                        @foreach($groupTourNhieuLichKhoiHanh[$obj['_id']] as $item)
                            {{ date_time_show($item['ngay_khoi_hanh'], 'd/m') }}@if(!$loop->last),@endif
                        @endforeach
                    @else
                    &nbsp;{{ (@$obj['ngay_khoi_hanh'] && @$obj['tour_hang_tuan'] == \App\Http\Models\Tour::TOURLE) ? date_time_show($obj['ngay_khoi_hanh']) : show_tuan(@$obj['thoi_gian_khoi_hanh_hang_tuan']) }}
                    @endif
                </li><br>
                <li class="thong-tin"><i class="fal fa-calendar-alt"></i>
                    {{ \App\Elibs\Helper::showContent(@$obj['so_ngay_di_tour']) }}
                </li>
            </ul>
            <div class="geodir-category-footer fl-wrap">
                <div class="geodir-category-price" style="color:#ff5f01;">
                    Giá từ: 
                    <span style="color: #ff5f01;font-size: 14px;">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_nguoi_lon']) }}</span>@if(@$obj['gia_niem_yet'] > $obj['gia_nguoi_lon'])  &nbsp;&nbsp; <del style="color: #807a7a; text-decoration: line-through;margin-right: 18px;">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_niem_yet']) }}</del> @endif
                </div>
                <div class="geodir-opt-list">
                    <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">
                    ĐẶT NGAY
                        {{-- <span class="geodir-opt-tooltip">Đặt ngay</span> --}}
                            </a>
                </div>
            </div>
        </div>
    </article>
</div>

@else
    @include('FE::FeTours.views.mobile-tour')
@endif
