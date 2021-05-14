@if(isset($lsObj) && !empty($lsObj))

    @foreach($lsObj as $obj)
        @php($obj['_id'] = (string)$obj['_id'])
        @php
            if(isset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'])) {
                $obj['gia_nguoi_lon'] = $groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'];
            }
        @endphp
    <!--slick-slide-item-->
    <div class="slick-slide-item" @if($agent->isDesktop()) style="min-height: 356px" @else style="min-height: 290px" @endif>
        <!-- listing-item  -->
        <div class="listing-item">
            <article class="geodir-category-listing fl-wrap" @if($agent->isDesktop()) style="min-height: 356px" @else style="min-height: 290px" @endif>
                <div class="geodir-category-img ">
                    @if(!isset($sale))
                    {{-- <div class="sale-window big-sale counter_tour" id="counter-{{ $obj['_id'] }}"></div> --}}
                    {{--@if(isset($obj['ngay_khoi_hanh']))
                    <script>
                        updateTimer("{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}", 'counter-{{ $obj["_id"] }}')
                    </script>
                    @endif--}}
                    @endif
                    <a href="{{ route('FeTour', ['alias' => @$obj['alias']]) }}" title="{{ value_show($obj['name']) }}"><img src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link']) }}" alt="{{ value_show($obj['name']) }}"></a>
                    {{--<div class="listing-avatar"><a href="author-single.html"><img src="images/avatar/1.jpg" alt=""></a>
                        <span class="avatar-tooltip">Added By  <strong>Alisa Noory</strong></span>
                    </div>--}}
                    @if(isset($sale))
                        @php($discount = \App\Elibs\Helper::calcDiscount(@$obj['gia_nguoi_lon'], @$obj['gia_niem_yet']))
                        @if($discount > 0)
                        <div class="sale-window {{ $discount >= 50 ? 'big-sale' : ''}}"> Sale {{ $discount }}%</div>
                        @endif
                    @endif
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
                <div class="geodir-category-content fl-wrap title-sin_item">
                    <div class="geodir-category-content-title fl-wrap">
                        <div class="geodir-category-content-title-item">
                            <h3 class="title-sin_map"><a title="{{ value_show($obj['name']) }}" href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }}</a></h3>
                            {{-- <div class="geodir-category-location fl-wrap sp-line-1" style="text-align: left;">
                                @if(!empty($obj['dia_diem_den']))
                                    @foreach($obj['dia_diem_den'] as $ls => $location)
                                        <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                           class="me-2" title="{{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}"  style="font-size: 12.5px">
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
                            @if(@$groupTourNhieuLichKhoiHanh[$obj['_id']])
                                @foreach($groupTourNhieuLichKhoiHanh[$obj['_id']] as $item)
                                    {{ date_time_show($item['ngay_khoi_hanh'], 'd/m') }}@if(!$loop->last),@endif
                                @endforeach
                            @else
                                &nbsp;{{ (@$obj['ngay_khoi_hanh'] && @$obj['tour_hang_tuan'] == \App\Http\Models\Tour::TOURLE) ? date_time_show($obj['ngay_khoi_hanh']) : show_tuan(@$obj['thoi_gian_khoi_hanh_hang_tuan']) }}
                            @endif
                        </li><br>
                        <li class="thong-tin"><i class="fal fa-calendar-alt"></i>
                            {{-- {{ value_show(@$IO_LOCATION[@$obj['so_ngay_di_tour']) }} --}}
                            {{ \App\Elibs\Helper::showContent(@$obj['so_ngay_di_tour']) }}
                        </li>

                    </ul>
                    <div class="fl-wrap border-bottom-dotted" style="margin-bottom: 8px">
                        <ul class="facilities-list fl-wrap" style="height: 38px">
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
                    </div>
                    <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">ĐẶT NGAY
                        {{-- <i class="fal fa-paper-plane"></i><span class="geodir-opt-tooltip">Đặt ngay</span> --}}
                    </a>
                    {{--@if($agent->isMobile())
                        <div class="fl-wrap border-bottom-dotted" style="margin-bottom: 8px">
                            <ul class="facilities-list fl-wrap" style="height: 38px">
                                <li class="thong-tin"><i class="fas fa-money-bill-alt"></i>
                                    Giá từ: <b style="color: red">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon'], '.', $obj['don_vi_tien_te']??' ₫') }}</b>
                                    --}}{{-- @if(@$obj['gia_niem_yet'] > $obj['gia_nguoi_lon']) &nbsp;&nbsp;  --}}{{--
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
                        </div>
                        <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">ĐẶT NGAY
                            --}}{{-- <i class="fal fa-paper-plane"></i><span class="geodir-opt-tooltip">Đặt ngay</span> --}}{{--
                        </a>

                    @else
                    <div class="geodir-category-footer fl-wrap">
                        <div class="geodir-category-price" style="color:#ff5f01;">
                            Giá từ:
                            <span style="color: #ff5f01;font-size: 14px;">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon'], '.', $obj['don_vi_tien_te']??' ₫') }}</span>
                            --}}{{-- @if(@$obj['gia_niem_yet'] > $obj['gia_nguoi_lon']) &nbsp;&nbsp;  --}}{{--
                            @if ($obj['gia_niem_yet'] > $obj['gia_nguoi_lon'])
                            <br>
                            <del style="color: #807a7a; text-decoration: line-through;margin-right: 18px;">

                                {{\App\Elibs\Helper::formatMoney(@$obj['gia_niem_yet'])}}


                                --}}{{-- {{ ($obj['gia_niem_yet'] <= $obj['gia_nguoi_lon']) ? 0 : \App\Elibs\Helper::formatMoney(@$obj['gia_niem_yet']) }} --}}{{--
                            </del>
                            @endif
                        </div>
                        <div class="geodir-opt-list">
                            --}}{{--<a href="#" class="single-map-item" data-newlatitude="40.72956781" data-newlongitude="-73.99726866" ><i class="fal fa-map-marker-alt"></i><span class="geodir-opt-tooltip">Xem bản đồ</span></a>--}}{{--
                            <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">ĐẶT NGAY
                                --}}{{-- <i class="fal fa-paper-plane"></i><span class="geodir-opt-tooltip">Đặt ngay</span> --}}{{--
                            </a>
                            --}}{{--<a href="#" class="geodir-js-booking"><i class="fal fa-exchange"></i><span class="geodir-opt-tooltip">Tìm chỉ đường</span></a>--}}{{--
                        </div>
                    </div>
                    @endif--}}

                </div>
            </article>
        </div>
        <!-- listing-item end -->
    </div>
    <!--slick-slide-item end-->
@endforeach
@endif