@extends($THEME_FE_EXTEND)

@section('CONTENT_REGION')
    <!--  wrapper  -->
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!--  section  -->
            {{-- <section class="parallax-section single-par" data-scrollax-parent="true">
                <div class="bg par-elem " data-bg="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']['full_size_link']) }}" data-scrollax="properties: { translateY: '30%' }"></div>
                <div class="overlay"></div>
                <div class="container">
                    <div class="section-title center-align big-title">
                        <h2><span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></h2>
                        <span class="section-separator"></span>
                    </div>
                </div>
                <div class="header-sec-link">
                    <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                </div>
            </section> --}}
            <!--  section  end-->
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('/') }}">Trang chủ</a>
                        @if(@$obj['parent_id'] != 0)<a href="{{ @$IO_TOURCATE[@$obj['parent_id']]['alias'] ? route('FeTour', ['alias' => $IO_TOURCATE[@$obj['parent_id']]['alias']]) : '#' }}">
                            {{ \App\Elibs\Helper::showContent(@$IO_TOURCATE[@$obj['parent_id']]) }}</a>@endif
                        <span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></div>
                </div>
            </div>
            <div class="container">
                <div class="section-title center-align big-title">
                    {{-- <div class="section-title-separator"><span></span></div> --}}
                    <h2><span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></h2>
                    <span class="section-separator"></span>
                    {{--<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec tincidunt arcu, sit amet fermentum sem.</h4>--}}
                </div>
                </div>
            <!--section -->
            <section class="grey-blue-bg small-padding" id="sec1">
                <div class="container">
                    <div class="row">
                        <!--listing -->
                        <div class="col-md-12">
                            <div class="mobile-list-controls fl-wrap mar-bot-cont">
                                <div class="mlc show-list-wrap-search fl-wrap" style="padding: 9px 0"><i class="fal fa-filter"></i> Lọc tìm kiếm tour</div>
                            </div>
                            <!--list-wrap-search   -->
                            <form action="">
                                <div class="list-wrap-search lisfw fl-wrap lws_mobile">
                                    <div class="container">
                                        <div class="row">
                                            <!-- col-list-search-input-item -->
                                            <div class="col-md-4">
                                                <div class="col-list-search-input-item in-loc-dec fl-wrap not-vis-arrow">
                                                    <label class="met-lam-roi">Tuyến tour</label>
                                                    <div class="listsearch-input-item">
                                                        <select name="tuyenTour" data-placeholder="City" class="chosen-select" >
                                                            <option value="">Tour tự chọn, tour kích cầu...</option>
                                                            @foreach($IO_TOURCATE as $tourCate)
                                                                <option value="{{ $tourCate['alias'] }}" @if(isset($q['tuyenTour']) && $q['tuyenTour'] == $tourCate['alias']) selected @endif>{{ $tourCate['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- col-list-search-input-item end -->
                                            <!-- col-list-search-input-item -->
                                            <div class="col-md-5">
                                                <div class="col-list-search-input-item in-loc-dec fl-wrap not-vis-arrow">
                                                    <label class="met-lam-roi">Địa điểm đến</label>
                                                    <div class="listsearch-input-item">
                                                        <select name="diaDiemDen" data-placeholder="City" class="chosen-select" >
                                                            <option value="">Hà Nội, Phú Quốc, Buôn Ma Thuột,...</option>
                                                            @foreach($IO_LOCATION as $location)
                                                                <option value="{{ $location['alias'] }}" @if(isset($q['diaDiemDen']) && $q['diaDiemDen'] == $location['alias']) selected @endif>{{ $location['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- col-list-search-input-item end -->
                                            <!-- col-list-search-input-item -->
                                            <div class="col-md-3">
                                                <div class="col-list-search-input-item in-loc-dec date-container  fl-wrap">
                                                    <label class="met-lam-roi">Thời gian </label>
                                                    <span class="header-search-input-item-icon"><i class="fal fa-calendar-check"></i></span>
                                                    <input type="text" placeholder="Tất cả" autocomplete="off" class="date-ranger" name="thoiGian" value="{{ @$q['thoiGian'] }}"/>
                                                </div>
                                            </div>
                                            <!-- col-list-search-input-item end -->
                                        </div>
                                        <div class="search-opt-wrap fl-wrap">
                                            <div class="search-opt-wrap-container">
                                                <!-- col-list-search-input-item -->
                                                <div class="search-input-item">
                                                    <div class="range-slider-title met-lam-roi">Giá</div>
                                                    <div class="range-slider-wrap fl-wrap">
                                                        <input class="range-slider" name="gia" data-from="{{ @$qMoney['money_start'] }}" data-to="{{ @$qMoney['money_end'] }}" data-step="100000" data-min="1000000" data-max="200000000" data-postfix=" đ">
                                                    </div>
                                                </div>
                                                <!-- col-list-search-input-item end -->
                                                <!-- col-list-search-input-item -->
                                                <div class="search-input-item small-input" style="float: right;">
                                                    <div class="col-list-search-input-item fl-wrap">
                                                        <button class="header-search-button" onclick="window.location.href='{{ url('') }}'">Tìm kiếm <i class="far fa-search"></i></button>
                                                    </div>
                                                </div>
                                                <!-- col-list-search-input-item end -->
                                            </div>
                                            {{--<div class="show-more-filters act-hiddenpanel color3-bg"><i class="fal fa-plus"></i><span>Lựa chọn khác</span></div>--}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--list-wrap-search end -->
                            <!--col-list-wrap -->
                            <div class="col-list-wrap fw-col-list-wrap">
                                <!-- list-main-wrap-->
                                <div class="list-main-wrap fl-wrap card-listing">
                                    <div class="list-main-wrap-opt fl-wrap">
                                        <!-- price-opt-->
                                        <div class="grid-opt">
                                            <ul>
                                                <li><span class="two-col-grid act-grid-opt"><i class="fas fa-th-large"></i></span></li>
                                                <li><span class="one-col-grid"><i class="fas fa-bars"></i></span></li>
                                            </ul>
                                        </div>
                                        <!-- price-opt end-->
                                    </div>
                                    <!-- listing-item-container -->
                                    <div class="listing-item-container init-grid-items fl-wrap three-columns-grid four-columns-grid">
                                        @foreach($lsObj as $obj)
                                            @php
                                                $obj['name'] = \App\Elibs\Helper::showContent($obj['name']);
                                                if(isset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'])) {
                                                    $obj['gia_nguoi_lon'] = $groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min'];
                                                    unset($groupTourNhieuLichKhoiHanh[(string)$obj['_id']]['gia_nguoi_lon_min']);
                                                }
                                            @endphp
                                            @if(!$agent->isMobile())
                                            <!-- listing-item  -->
                                                <div class="listing-item">
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

                                                        </div>
                                                    </article>
                                                </div>
                                            <!-- listing-item end -->
                                            @else
                                                @include('FE::FeTours.views.mobile-tour')
                                            @endif
                                        @endforeach
                                    </div>
                                    <!-- listing-item-container end-->
                                    <!-- pagination-->
                                    {{-- {!! $lsObj->links('FE::components.pagi') !!}--}}
                                    {{ $lsObj->render() }}
                                    <!-- pagination end-->
                                </div>
                                <!-- list-main-wrap end-->
                            </div>
                            <!--col-list-wrap end -->
                        </div>
                        <!--listing  end-->
                    </div>
                    <!--row end-->
                </div>
            </section>
            <!--section end -->
        </div>
        <!-- content end-->
    </div>
    <!--wrapper end -->
@stop