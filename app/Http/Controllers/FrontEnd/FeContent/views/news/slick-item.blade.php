@if(isset($lsObj) && !empty($lsObj))
    @foreach($lsObj as $obj)
    <!--slick-slide-item-->
    <div class="slick-slide-item">
        <!-- listing-item  -->
        <div class="listing-item">
            <article class="geodir-category-listing fl-wrap">
                <div class="geodir-category-img">
                    @if(!isset($sale))
                    <div class="sale-window big-sale counter_tour" id="counter-{{ $obj['_id'] }}"></div>
                    <script>
                        updateTimer("{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}", 'counter-{{ $obj["_id"] }}')
                    </script>
                    @endif
                    <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"><img src="{{ public_link('vietrantour/images/gal/'.rand(1, 8).'.jpg') }}" alt=""></a>
                    {{--<div class="listing-avatar"><a href="author-single.html"><img src="images/avatar/1.jpg" alt=""></a>
                        <span class="avatar-tooltip">Added By  <strong>Alisa Noory</strong></span>
                    </div>--}}
                    @if(isset($sale))
                        @php($discount = \App\Elibs\Helper::calcDiscount(@$obj['gia_nguoi_lon'], @$obj['gia_niem_yet']))
                        <div class="sale-window {{ $discount >= 50 ? 'big-sale' : ''}}"> Sale {{ $discount }}%</div>
                    @endif
                    <div class="geodir-category-opt">
                        <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                        <div class="rate-class-name">
                            <div class="score"><strong>Very Good</strong>27 Reviews </div>
                            <span>5.0</span>
                        </div>
                    </div>
                </div>
                <div class="geodir-category-content fl-wrap title-sin_item">
                    <div class="geodir-category-content-title fl-wrap">
                        <div class="geodir-category-content-title-item">
                            <h3 class="title-sin_map"><a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ \App\Elibs\Helper::showContent($obj['name']) }}</a></h3>
                            <div class="geodir-category-location fl-wrap"><a href="#" class="map-item"><i class="fas fa-map-marker-alt"></i> {{ \App\Elibs\Helper::showContent(@$data_dia_diem[@$obj['dia_diem_den']['id']]) }}</a></div>
                        </div>
                    </div>
                    <p>{!! @$obj['mo_ta_ngan'] !!}</p>
                    <ul class="facilities-list fl-wrap">
                        <li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>
                        <li><i class="fal fa-parking"></i><span>Parking</span></li>
                        <li><i class="fal fa-smoking-ban"></i><span>Non-smoking Rooms</span></li>
                        <li><i class="fal fa-utensils"></i><span> Restaurant</span></li>
                    </ul>
                    <div class="geodir-category-footer fl-wrap">
                        <div class="geodir-category-price" style="color:#ff5f01;">
                            Giá từ: 
                            <span style="color: #ff5f01;font-size: 14px;">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon'], ',', $obj['don_vi_tien_te']??' ₫') }}</span></div>
                        <div class="geodir-opt-list">
                            <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">
                                ĐẶT NGAY
                                    {{-- <span class="geodir-opt-tooltip">Đặt ngay</span> --}}
                                        </a>
            
                            {{-- <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite"><i class="fal fa-paper-plane"></i><span class="geodir-opt-tooltip">Đặt ngay</span></a> --}}
{{--                            <a href="javascript:void(0)" onclick="initMap()" class="single-map-item" data-newlatitude="40.72956781" data-newlongitude="-73.99726866" ><i class="fal fa-map-marker-alt"></i><span class="geodir-opt-tooltip">Xem bản đồ</span></a>--}}
{{--                            <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite"><i class="fal fa-heart"></i><span class="geodir-opt-tooltip">Đặt tour</span></a>--}}
{{--                            <a href="#" class="geodir-js-booking"><i class="fal fa-exchange"></i><span class="geodir-opt-tooltip">Tìm chỉ đường</span></a>--}}
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <!-- listing-item end -->
    </div>
    <!--slick-slide-item end-->
@endforeach
@endif