@extends($THEME_FE_EXTEND)

@section('CONTENT_REGION')
    <!--  wrapper  -->
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!--  section  -->
            <section class="parallax-section single-par" data-scrollax-parent="true">
                <div class="bg par-elem " data-bg="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link']) }}" data-scrollax="properties: { translateY: '30%' }"></div>
                <div class="overlay"></div>
                <div class="container">
                    <div class="section-title center-align big-title">
                        {{-- <div class="section-title-separator"><span></span></div> --}}
                        <h2><span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></h2>
                        <span class="section-separator"></span>
                        {{--<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec tincidunt arcu, sit amet fermentum sem.</h4>--}}
                    </div>
                </div>
                <div class="header-sec-link">
                    <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                </div>
            </section>
            <!--  section  end-->
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('/') }}">Trang chủ</a>
                        @if(@$obj['parent_id'] != 0)<a href="{{ @$IO_LOCATION[@$obj['parent_id']]['alias'] ? route('FeTour.Place', ['alias' => $IO_LOCATION[@$obj['parent_id']]['alias']]) : '#' }}">
                            {{ \App\Elibs\Helper::showContent(@$IO_LOCATION[@$obj['parent_id']]) }}</a>@endif
                        <span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></div>
                </div>
            </div>
            <!--  section-->
            <section class="grey-blue-bg small-padding" id="sec1">
                <div class="container">
                    <div class="row">
                        <!--filter sidebar -->
                        <div class="col-md-4">
                            <div class="mobile-list-controls fl-wrap">
                                <div class="mlc show-list-wrap-search fl-wrap"><i class="fal fa-filter"></i> Lọc tìm kiếm tour</div>
                            </div>
                            <div class="fl-wrap filter-sidebar_item fixed-bar">
                                <form>
                                    <div class="filter-sidebar fl-wrap lws_mobile">
                                        <!--col-list-search-input-item -->
                                        <div class="col-list-search-input-item in-loc-dec fl-wrap not-vis-arrow">
                                            <label class="met-lam-roi">Tuyến tour</label>
                                            <div class="listsearch-input-item">
                                                <select name="tuyenTour" data-placeholder="City" class="chosen-select" >
                                                    <option value="">Tất cả</option>
                                                    @foreach($IO_TOURCATE as $tourCate)
                                                        <option value="{{ $tourCate['alias'] }}" @if(isset($q['tuyenTour']) && $q['tuyenTour'] == $tourCate['alias']) selected @endif>{{ $tourCate['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--col-list-search-input-item end-->
                                        <!--col-list-search-input-item -->
                                        <div class="col-list-search-input-item fl-wrap location autocomplete-container">
                                            <label class="met-lam-roi">Địa điểm đến</label>
                                            <div class="listsearch-input-item">
                                                <select name="diaDiemDen" data-placeholder="City" class="chosen-select" >
                                                    <option value="">Tất cả</option>
                                                    @foreach($IO_LOCATION as $location)
                                                        <option value="{{ $location['alias'] }}" @if(isset($q['diaDiemDen']) && $q['diaDiemDen'] == $location['alias']) selected @endif>{{ $location['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--col-list-search-input-item end-->
                                        <!--col-list-search-input-item -->
                                        <div class="col-list-search-input-item in-loc-dec date-container  fl-wrap">
                                            <label class="met-lam-roi">Thời gian </label>
                                            <span class="header-search-input-item-icon"><i class="fal fa-calendar-check"></i></span>
                                            <input type="text" placeholder="Tất cả" autocomplete="off" class="date-ranger" name="thoiGian" value="{{ @$q['thoiGian'] }}"/>
                                        </div>
                                        <!--col-list-search-input-item end-->
                                        <!--col-list-search-input-item -->
                                        <div class="col-list-search-input-item fl-wrap">
                                            <div class="range-slider-title met-lam-roi">Giá</div>
                                            <div class="range-slider-wrap fl-wrap">
                                                <input class="range-slider" name="gia" data-from="{{ @$qMoney['money_start'] }}" data-to="{{ @$qMoney['money_end'] }}" data-step="100000" data-min="1000000" data-max="200000000" data-postfix=" đ">
                                            </div>
                                        </div>
                                        <!--col-list-search-input-item end-->
                                    {{--<!--col-list-search-input-item -->
                                    <div class="col-list-search-input-item fl-wrap">
                                        <label>Star Rating</label>
                                        <div class="search-opt-container fl-wrap">
                                            <!-- Checkboxes -->
                                            <ul class="fl-wrap filter-tags">
                                                <li class="five-star-rating">
                                                    <input id="check-aa2" type="checkbox" name="check" checked>
                                                    <label for="check-aa2"><span class="listing-rating card-popup-rainingvis" data-starrating2="5"><span>5 Stars</span></span></label>
                                                </li>
                                                <li class="four-star-rating">
                                                    <input id="check-aa3" type="checkbox" name="check">
                                                    <label for="check-aa3"><span class="listing-rating card-popup-rainingvis" data-starrating2="5"><span>4 Star</span></span></label>
                                                </li>
                                                <li class="three-star-rating">
                                                    <input id="check-aa4" type="checkbox" name="check">
                                                    <label for="check-aa4"><span class="listing-rating card-popup-rainingvis" data-starrating2="5"><span>3 Star</span></span></label>
                                                </li>
                                            </ul>
                                            <!-- Checkboxes end -->
                                        </div>
                                    </div>
                                    <!--col-list-search-input-item end-->
                                    <!--col-list-search-input-item -->
                                    <div class="col-list-search-input-item fl-wrap">
                                        <label>Facility</label>
                                        <div class="search-opt-container fl-wrap">
                                            <!-- Checkboxes -->
                                            <ul class="fl-wrap filter-tags half-tags">
                                                <li>
                                                    <input id="check-aaa5" type="checkbox" name="check" checked>
                                                    <label for="check-aaa5">Free WiFi</label>
                                                </li>
                                                <li>
                                                    <input id="check-bb5" type="checkbox" name="check">
                                                    <label for="check-bb5">Parking</label>
                                                </li>
                                                <li>
                                                    <input id="check-dd5" type="checkbox" name="check">
                                                    <label for="check-dd5">Fitness Center</label>
                                                </li>
                                            </ul>
                                            <!-- Checkboxes end -->
                                            <!-- Checkboxes -->
                                            <ul class="fl-wrap filter-tags half-tags">
                                                <li>
                                                    <input id="check-ff5" type="checkbox" name="check">
                                                    <label for="check-ff5">Airport Shuttle</label>
                                                </li>
                                                <li>
                                                    <input id="check-cc5" type="checkbox" name="check" checked>
                                                    <label for="check-cc5">Non-smoking Rooms</label>
                                                </li>
                                                <li>
                                                    <input id="check-c4" type="checkbox" name="check" checked>
                                                    <label for="check-c4">Air Conditioning</label>
                                                </li>
                                            </ul>
                                            <!-- Checkboxes end -->
                                        </div>
                                    </div>
                                    <!--col-list-search-input-item end-->--}}
                                    <!--col-list-search-input-item  -->
                                        <div class="col-list-search-input-item fl-wrap">
                                            <button class="header-search-button" type="submit">Search <i class="far fa-search"></i></button>
                                        </div>
                                        <!--col-list-search-input-item end-->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--filter sidebar end-->
                        <!--listing -->
                        <div class="col-md-8">
                            <!--col-list-wrap -->
                            <div class="col-list-wrap fw-col-list-wrap post-container">
                                <!-- list-main-wrap-->
                                <div class="list-main-wrap fl-wrap card-listing">
                                    <!-- list-main-wrap-opt-->
                                    <div class="list-main-wrap-opt fl-wrap">

                                        <!-- price-opt-->
                                        <div class="grid-opt">
                                            <ul>
                                                <li><span class="two-col-grid"><i class="fas fa-th-large"></i></span></li>
                                                <li><span class="one-col-grid act-grid-opt"><i class="fas fa-bars"></i></span></li>
                                            </ul>
                                        </div>
                                        <!-- price-opt end-->
                                    </div>
                                    <!-- list-main-wrap-opt end-->
                                    <!-- listing-item-container -->
                                    <div class="listing-item-container init-grid-items fl-wrap">
                                        @foreach($lsObj as $obj)
                                        @php
                                            $obj['name'] = \App\Elibs\Helper::showContent($obj['name']);
                                            if(isset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'])) {
                                                    $obj['gia_nguoi_lon'] = $groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'];
                                                }
                                        @endphp
                                        <!-- listing-item  -->
                                            <div class="listing-item has_two_column">
                                                <article class="geodir-category-listing fl-wrap">
                                                    <div class="geodir-category-img  ">
                                                        <a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"><img src="{{\App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link'])}}" alt=""></a>
                                                        @php($discount = \App\Elibs\Helper::calcDiscount(@$obj['gia_nguoi_lon'], @$obj['gia_niem_yet']))
                                                        <div class="sale-window {{ $discount >= 50 ? 'big-sale' : ''}}"> Sale {{ $discount }}%</div>
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
                                                                <h3 class="title-sin_map"><a href="{{ route('FeTour', ['alias' => $obj['alias']]) }}">{{ $obj['name'] }}</a></h3>
                                                                {{-- <div class="geodir-category-location fl-wrap sp-line-1" style="text-align: left;">

                                                                    @if(!empty($obj['dia_diem_den']))
                                                                        @foreach($obj['dia_diem_den'] as $ls => $location)
                                                                            <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                                                               class="me-2">
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
                                                            <li class="thong-tin"><i class="fal fa-calendar-alt"></i>&nbsp;{{ \App\Elibs\Helper::showContent(@$obj['so_ngay_di_tour']) }}</li>
                                                        </ul>
                                                        <div class="geodir-category-footer fl-wrap">
                                                            <div class="geodir-category-price" style="color:#ff5f01;">Giá từ: <span style="color: #ff5f01;font-size: 14px;">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon']) }}</span></div>
                                                            <div class="geodir-opt-list">
                                                                <a href="{{ route('FeBooking', $obj['sku']) }}" class="geodir-js-favorite">ĐẶT NGAY
                                                                    {{-- <i class="fal fa-paper-plane"></i> --}}
                                                                    {{-- <span class="geodir-opt-tooltip">Đặt ngay</span> --}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                            <!-- listing-item end -->
                                        @endforeach
                                    </div>
                                    <!-- listing-item-container end-->
                                    {{ $lsObj->render() }}
                                    {{--<a class="load-more-button" href="#">Load more <i class="fal fa-spinner"></i> </a>--}}
                                </div>
                                <!-- list-main-wrap end-->
                            </div>
                            <!--col-list-wrap end -->
                        </div>
                        <!--listing  end-->
                    </div>
                    <!--row end-->
                </div>
                <div class="limit-box fl-wrap"></div>
            </section>
        </div>
        <!-- content end-->
    </div>
    <!--wrapper end -->
@stop