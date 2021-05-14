<div class="listing-item-container init-grid-items fl-wrap three-columns-grid">
    @foreach($lsObj as $obj)
        @php
            $obj['name'] = \App\Elibs\Helper::showContent($obj['name']);
            if(isset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'])) {
                $obj['gia_nguoi_lon'] = $groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'];
                unset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min']);
            }
        @endphp
        <!-- listing-item  -->
            <div class="listing-item has_two_column has_one_column">
                <article class="geodir-category-listing fl-wrap">
                    <div class="geodir-category-img">
                        @if(isset($countdown))
                            <div class="sale-window big-sale counter_tour" id="counter-{{ $obj['_id'] }}"></div>
                            <script>
                                updateTimer("{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}", 'counter-{{ $obj["_id"] }}')
                            </script>
                        @endif
                        <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"><img src="{{ \App\Http\Models\Media::getImageSrc($obj['avatar']['relative_link']) }}" alt="{{ value_show($obj['name']) }}"></a>
                        <div class="geodir-category-opt">
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="{{ @$obj['score'] }}"></div>
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
                    <div class="geodir-category-content fl-wrap title-sin_item">
                        <div class="geodir-category-content-title fl-wrap">
                            <div class="geodir-category-content-title-item">
                                <h3 class="title-sin_map"><a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }}</a></h3>
                                {{-- <div class="geodir-category-location fl-wrap sp-line-1" style="text-align: left">
                                    @if(!empty($obj['dia_diem_den']))
                                        @foreach($obj['dia_diem_den'] as $ls => $location)
                                            <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                               class="me-2" >
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div> --}}
                            </div>
                        </div>
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
                    </div>
                    <div class="geodir-category-footer fl-wrap fix-tiep-nha" style="color:#ff5f01;">
                        <ul class="facilities-list fl-wrap" style="width: auto;margin-top: 4px;">
                            <li class="thong-tin"><i class="fas fa-money-bill-alt"></i>
                                Giá từ: <b style="color: red">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon'], '.', $obj['don_vi_tien_te']??' ₫') }}</b>
                                {{-- @if(@$obj['gia_niem_yet'] > $obj['gia_nguoi_lon']) &nbsp;&nbsp;  --}}
                            </li>
                            @if ($obj['gia_niem_yet'] > $obj['gia_nguoi_lon'])
                                <li class="thong-tin"><i class="fas fa-money-bill-alt"></i>
                                    Giá gốc:
                                    <del style="color: #807a7a; text-decoration: line-through;margin-right: 18px;">
                                        {{\App\Elibs\Helper::formatMoney(@$obj['gia_niem_yet'])}}
                                    </del>
                                </li>
                            @endif
                        </ul>
                        <a href="{{ (isset($obj['sku']) && !empty($obj['sku'])) ? route('FeBooking', ['id' => $obj['sku']]) : '#' }}" class="dat-ngay-mobile">ĐẶT NGAY
                        </a>
                    </div>
                </article>
            </div>
        <!-- listing-item end -->
        @endforeach
</div>

{{-- 
<div class="col-12 col-sm-6 col-md-4">
    <div class="hotel-card fl-wrap title-sin_item">
        <div class="geodir-category-img card-post">
            <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"><img src="{{ \App\Http\Models\Media::getImageSrc($obj['avatar']['relative_link']) }}"
                                                                             alt="{{ value_show($obj['name']) }}">></a>
            <div class="geodir-category-opt">
                <div class="listing-rating card-popup-rainingvis"
                     data-starrating2="{{ @$obj['score'] }}"></div>
                <h4 class="title-sin_map @if(!isset($obj['score'])) mb-3 @endif"><a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }}</a></h4>
                <div class="geodir-category-footer fl-wrap border-top-0">
                    <div class="geodir-category-price" style="color:#ff5f01;">
                        Giá từ: 
                        <span style="color: #ff5f01;font-size: 14px;">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_nguoi_lon']) }}</span>@if(@$obj['gia_niem_yet'] > $obj['gia_nguoi_lon']) &nbsp;&nbsp; <del style="color: #807a7a; text-decoration: line-through;margin-right: 18px;">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_niem_yet']) }}</del> @endif
                        <a href="{{ (isset($obj['sku']) && !empty($obj['sku'])) ? route('FeBooking', ['id' => $obj['sku']]) : '#' }}" class="geodir-js-favorite" style="float: right">
                            <i class="fal fa-paper-plane"></i><span
                                    class="geodir-opt-tooltip">Đặt ngay</span></a>
                        <ul class="facilities-list ngay-khoi-hanh fl-wrap">
                            <li class="thong-tin"><i class="fal fa-calendar-alt"></i>
                                &nbsp;@if(isset($groupTourNhieuLichKhoiHanh[$obj['_id']]))
                                    @foreach($groupTourNhieuLichKhoiHanh[$obj['_id']] as $item)
                                        {{ date_time_show($item['ngay_khoi_hanh'], 'd/m') }}@if(!$loop->last),@endif
                                    @endforeach
                                @else
                                    &nbsp;{{ (@$obj['ngay_khoi_hanh'] && @$obj['tour_hang_tuan'] == \App\Http\Models\Tour::TOURLE) ? date_time_show($obj['ngay_khoi_hanh']) : show_tuan(@$obj['thoi_gian_khoi_hanh_hang_tuan']) }}
                                @endif
                            </li>
                        </ul>
                    </div>

                </div>
                @if(isset($obj['score']))
                    <div class="geodir-category-location fl-wrap">
                        @if(!empty($obj['dia_diem_den']))
                            @foreach($obj['dia_diem_den'] as $ls => $location)
                                <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                   class="me-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}
                                </a>
                            @endforeach
                        @endif
                    </div>
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
    </div>
</div> --}}