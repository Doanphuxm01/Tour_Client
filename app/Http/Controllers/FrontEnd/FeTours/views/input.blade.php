@extends($THEME_FE_EXTEND)

@section('CONTENT_REGION')
    <style>
        @media only screen and (max-width: 1064px) {
            .list-single-main-item img {
                width: 100%;
            }

        }
        thead, tbody, tfoot, tr, td, th{
            border-width: 1px;
            vertical-align: middle;
        }
        p, .list-single-main-item p, a {
            color: unset;
            text-align: unset;
            padding: unset;
        }
    </style>
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!--  section  -->
            <section class="list-single-hero" data-scrollax-parent="true" id="sec1">
                <div class="bg par-elem "
                     data-bg="{{\App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link'])}}"
                     data-scrollax="properties: { translateY: '30%' }"></div>
                <div class="list-single-hero-title fl-wrap" style="padding: 0px 0 30px;">
                    <div class="container">
                        <div class="row oi-gioi-oi">
                            <div class="col-md-9">
                                <div class="listing-rating-wrap">
                                    <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                </div>
                                <h2><span>{{ \App\Elibs\Helper::showContent($obj['name']) }}</span></h2>
                                <div class="list-single-header-contacts fl-wrap">
                                    <ul>
                                        <li><i class="far fa-phone"></i><a
                                                    href="tel:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}</a>
                                        </li>
                                        @if(!empty($obj['dia_diem_den']))
                                            @foreach($obj['dia_diem_den'] as $ls => $location)
                                                <li><i class="far fa-map-marker-alt"></i>
                                                    <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}">
                                                        {{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                        <li><i class="far fa-envelope"></i><a target="_blank"

                                                                              href="mailto:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['email']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['email']) }}</a>
                                        </li>
                                        <li style="color: aliceblue;font-size: 15px;"><i class="fal fa-calendar-alt"></i>Ngày khởi hành:
                                            @if(@$groupTourLe)
                                                @foreach($groupTourLe as $item)
                                                    {{ date_time_show($item['ngay_khoi_hanh'], 'd/m') }}@if(!$loop->last),@endif
                                                @endforeach
                                            @else
                                                &nbsp;{{ (@$obj['ngay_khoi_hanh'] && @$obj['tour_hang_tuan'] == \App\Http\Models\Tour::TOURLE) ? date_time_show($obj['ngay_khoi_hanh']) : show_tuan(@$obj['thoi_gian_khoi_hanh_hang_tuan']) }}
                                            @endif
                                        </li>

                                        <li style="color: aliceblue;font-size: 15px;"><i
                                            class="fal fa-calendar-alt"></i>Số ngày đi tour: {{ \App\Elibs\Helper::showContent(@$obj['so_ngay_di_tour']) }}                                </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!--  list-single-hero-details-->
                                <div class="list-single-hero-details fl-wrap">
                                    <!--  list-single-hero-rating-->
                                @include('FE::FeTours.views.components.rate')
                                <!--  list-single-hero-rating  end-->
                                    <div class="clearfix"></div>
                                    <!-- list-single-hero-links-->
                                    <div class="list-single-hero-links">
                                        <a class="lisd-link" href="{{ route('FeBooking', $obj['sku']) }}"><i
                                                    class="fal fa-paper-plane"></i> Đặt ngay</a>
                                        <a class="custom-scroll-link lisd-link" href="#sec6"><i
                                                    class="fal fa-comment-alt-check"></i> Viết nhận xét</a>
                                    </div>
                                    <div class="list-single-hero-price" style="margin-top: 39px;font-size: 14px;">
                                        Giá<span style="color: #ff5f01">{{ \App\Elibs\Helper::formatMoney($obj['gia_nguoi_lon']) }}</span></div>
        
                                    <!--  list-single-hero-links end-->
                                </div>
                                <!--  list-single-hero-details  end-->
                            </div>
                        </div>
                        <div class="breadcrumbs-hero-buttom fl-wrap">
                            <div class="breadcrumbs"><a href="{{ public_link() }}">Trang chủ</a>
                                @if(@$IO_TOURCATE[@$obj['tuyen_tour'][0]['parent_id']??$obj['tuyen_tour']['parent_id']]['alias'])
                                    <a href="{{ @$IO_TOURCATE[@$obj['tuyen_tour'][0]['parent_id']??$obj['tuyen_tour']['parent_id']]['alias'] ? route('FeTour', ['alias' => $IO_TOURCATE[@$obj['tuyen_tour'][0]['parent_id']??$obj['tuyen_tour']['parent_id']]['alias']]) : '#' }}">
                                        {{ \App\Elibs\Helper::showContent(@$IO_TOURCATE[$obj['tuyen_tour'][0]['parent_id']??$obj['tuyen_tour']['parent_id']]) }}</a>
                                @endif
                                <span>{{ \App\Elibs\Helper::showContent(@$obj['tuyen_tour'][0]??$obj['tuyen_tour']) }}</span></div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  section  end-->
            <!--  section  -->
            <section class="grey-blue-bg small-padding scroll-nav-container" id="sec2">
                <!--  scroll-nav-wrapper  -->
                <div class="scroll-nav-wrapper fl-wrap">
                    <div class="hidden-map-container fl-wrap">
                        <input id="pac-input" class="controls fl-wrap controls-mapwn" type="text"
                               placeholder="What Nearby ?   Bar , Gym , Restaurant ">
                        <div class="map-container">
                            <div id="singleMap" data-latitude="21.026326735566048"
                                 data-longitude="105.85746835932898"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="container">
                        <nav class="scroll-nav scroll-init">
                            <ul>
                                <li><a class="act-scrlink" href="#sec1">Đầu trang</a></li>
                                <li><a href="#sec-kh">Lịch khởi hành</a></li>
                                <li><a href="#sec3">Lịch trình</a></li>
                                <li><a href="#sec-noidung">Nội dung, lưu ý đặc biệt</a></li>
                                {{--<li><a href="#sec4">Tour liên quan</a></li>--}}
                                <li><a href="#sec6">Nhận xét</a></li>
                            </ul>
                        </nav>
                        {{--<a href="#" class="show-hidden-map">  <span>On The Map</span> <i class="fal fa-map-marked-alt"></i></a>--}}
                    </div>
                </div>
                <!--  scroll-nav-wrapper end  -->
                <!--   container  -->
                <div class="container">
                    <!--   row  -->
                    <div class="row">
                        <!--   datails -->
                        <div class="col-md-8">
                            <div class="list-single-main-container ">
                                <!-- fixed-scroll-column  -->
                                <div class="fixed-scroll-column">
                                    <div class="fixed-scroll-column-item fl-wrap">
                                        <div class="showshare sfcs fc-button"><i class="far fa-share-alt"></i><span>Chia sẻ </span>
                                        </div>
                                        <div class="share-holder fixed-scroll-column-share-container">
                                            <div class="share-container  isShare"></div>
                                        </div>
                                        <a class="fc-button custom-scroll-link" href="#sec6"><i
                                                    class="far fa-comment-alt-check"></i> <span>  Viết nhận xét </span></a>
                                        <a class="fc-button custom-scroll-link them-vao-gio"
                                           data-sku="{{ value_show($obj['sku']) }}"
                                           data-id="{{ value_show($obj['_id']) }}" href="javascript:void(0);"><i
                                                    class="far fa-heart"></i> <span>  Thêm vào giỏ hàng </span></a>
                                        <a class="fc-button" href="{{ route('FeBooking', $obj['sku']) }}"><i
                                                    class="fal fa-paper-plane"></i> <span>Đặt ngay</span></a>
                                        <form id="inputCart">
                                            <input type="hidden" name="sku" value="{{ value_show($obj['sku']) }}">
                                            <input type="hidden" name="id" value="{{ value_show($obj['_id']) }}">
                                        </form>
                                    </div>
                                </div>
                            @if(isset($obj['files']) && !empty($obj['files']))
                                <!-- fixed-scroll-column end   -->
                                    <div class="list-single-main-media fl-wrap">
                                        <!-- gallery-items   -->
                                        <div class="gallery-items grid-small-pad  list-single-gallery three-coulms lightgallery">
                                            @php $countOrther = 0; @endphp
                                            @foreach($obj['files'] as $k => $file)

                                                <div class="gallery-item @if($k == 2) gallery-item-second @endif">
                                                    <div class="grid-item-holder">
                                                        <div class="box-item">
                                                            @if($k < 5)
                                                                <img src="{{\App\Http\Models\Media::getImageSrc($file['src'])}}"
                                                                     alt=" {{ \App\Elibs\Helper::showContent($file) }}">
                                                                <a href="{{\App\Http\Models\Media::getImageSrc($file['src'])}}"
                                                                   class="gal-link popup-image"><i
                                                                            class="fa fa-search"></i></a>
                                                            @else
                                                                @php
                                                                    $countOrther++;
                                                                    array_push($dynamicPath, (object)['src' => \App\Http\Models\Media::getImageSrc($file['src'])])
                                                                @endphp
                                                                @if($loop->last)
                                                                    <img src="{{\App\Http\Models\Media::getImageSrc($file['src'])}}"
                                                                         alt=" {{ \App\Elibs\Helper::showContent($file) }}">
                                                                    <div class="more-photos-button dynamic-gal"
                                                                         data-dynamicPath="{{ json_encode($dynamicPath) }}">
                                                                        <span>{{ $countOrther }} ảnh khác</span><i
                                                                                class="far fa-long-arrow-right"></i>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                        <!-- end gallery items -->
                                    </div>
                                    <!-- list-single-header end -->
                            @else
                                <div class="list-single-main-media fl-wrap lightgallery" id="sec1">
                                    <div class="single-slider-wrapper fl-wrap">
                                        <div class="slider-for fl-wrap"  >
                                            <div class="slick-slide-item box-item">
                                                <img src="{{\App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link'])}}" alt="{{ value_show($obj['name']) }}">
                                                <a href="{{\App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link'])}}"
                                                   class="gal-link popup-image"><i
                                                            class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($groupTourLe))
                                <!--   list-single-main-item -->
                                <div class="list-single-main-item list-single-main-item-mobile fl-wrap" id="sec-kh">
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Lịch khởi hành</h3>
                                    </div>
                                    <table class="table datatable-responsive">
                                        <thead>
                                        <tr>
                                            <th style="font-weight: bold">STT</th>
                                            <th style="font-weight: bold">Ngày khởi hành</th>
                                            <th style="font-weight: bold">Giá người lớn</th>
                                            <th style="font-weight: bold">Giá trẻ em</th>
                                            <th style="font-weight: bold">Giá trẻ nhỏ</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($groupTourLe as $k => $tour)
                                        <tr>
                                            <td scope="row"><i class="fal fa-plus"></i></td>
                                            <td>{{ date_time_show($tour['ngay_khoi_hanh']) }}</td>
                                            <td>{{ \App\Elibs\Helper::formatMoney($tour['gia_nguoi_lon']) }}</td>
                                            <td>{{ \App\Elibs\Helper::formatMoney($tour['gia_tre_em']) }}</td>
                                            <td>{{ \App\Elibs\Helper::formatMoney($tour['gia_tre_nho']) }}</td>
                                            <td><a href="{{ route('FeBookingTourLe', ['alias' => $tour['sku']]) }}" class="color-bg text-white" style="padding: 6px 8px">Đặt tour</a></td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <!--   list-single-main-item end -->
                            @endif

                            <!--   list-single-main-item -->
                                <div class="list-single-main-item list-single-main-item-mobile ok-content-unset-color fl-wrap" id="sec3">
                                    {{--@if(isset($obj['dich_vu_tien_ich']))
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Dịch vụ</h3>
                                    </div>
                                    <div class="listing-features fl-wrap">
                                        <ul>
                                            @php($dich_vu_tien_ich = explode(',', $obj['dich_vu_tien_ich']))
                                            @if($dich_vu_tien_ich)
                                            @foreach($dich_vu_tien_ich as $item)
                                            <li><i class="fal fa-rocket"></i> {{ $item }}</li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    @endif--}}
                                    <div class="list-single-main-item-title no-dec-title fl-wrap">
                                        <h3>Lịch trình</h3>
                                    </div>
                                    <!-- accordion-->
                                    <div class="accordion mar-top">
                                        @foreach($obj['lich_trinh'] as $option)
                                            <a class="toggle act-accordion-mobile {{ $loop->first ? 'act-accordion' : '' }}"
                                               href="javascript:void(0);">{{ \App\Elibs\Helper::showContent($option['name']) }}
                                                <span></span></a>
                                            <div class="accordion-inner accordion-inner-mobile {{ $loop->first ? 'visible' : '' }}">
                                                {!! $option['content'] !!}
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- accordion end -->
                                </div>
                                <!--   list-single-main-item end -->
                                <!--   list-single-main-item -->
                                <div class="list-single-main-item fl-wrap" id="sec-noidung">
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Thông tin chi tiết</h3>
                                    </div>
                                    <div id="noi_dung">{!! $obj['luu_y'] !!}</div>
                                    @if(@$obj['link_video'])
                                        <a href="{{ $obj['link_video'] }}"
                                           class="btn-more mt-3 flat-btn color-bg big-btn float-btn image-popup">Trình
                                            chiếu video <i class="fal fa-play"></i></a>
                                    @endif
                                    <a href="{{ route('FeBooking', $obj['sku']) }}"
                                       class="btn-more mt-3 flat-btn color-bg big-btn float-btn">Đặt ngay <i class="fal fa-play"></i></a>
                                </div>
                                <!--   list-single-main-item end -->
                                <!-- tour liên quan -->
                            {{--@include('FE::FeTours.views.components.related')--}}
                            <!-- tour liên quan -->

                                <!-- list-single-main-item -->
                                <div class="list-single-main-item fl-wrap" id="sec6">
                                    <div class="list-single-main-item-title fl-wrap">
                                        <h3>Viết nhận xét</h3>
                                    </div>
                                    <!-- Viết nhận xét Box -->
                                    <div id="add-review" class="add-review-box">
                                        <!-- Review Comment -->
                                        <div class="fb-comments"
                                             data-href="{{ route('FeTour', ['alias' => $obj['alias']]) }}"
                                             data-width="100%" data-numposts="5"></div>
                                    </div>
                                    <!-- Viết nhận xét Box / End -->
                                </div>
                                <!-- list-single-main-item end -->
                            </div>
                        </div>
                        <!--   datails end  -->
                        <!--   sidebar  -->
                        <div class="col-md-4">
                            <!--box-widget-wrap -->
                            <div class="box-widget-wrap">
                            @if(@$obj['ngay_khoi_hanh'])
                                <!--box-widget-item -->
                                    <div class="box-widget-item fl-wrap">
                                        <div class="box-widget counter-widget"
                                             data-countDate="{{ \App\Elibs\Helper::showMongoDate($obj['ngay_khoi_hanh'], 'm/d/Y H:i:s') }}">
                                            <div class="banner-wdget fl-wrap">
                                                <div class="overlay"></div>
                                                <div class="bg"
                                                     {{-- data-bg="{{\App\Http\Models\Media::getImageSrc(@$obj['avatar']['relative_link'])}}" --}}
                                                     >
                                                </div>
                                                <div class="banner-wdget-content fl-wrap">
                                                    {{--<h4>Get a discount <span>20%</span> when ordering a room from three days.</h4>--}}
                                                    <div class="countdown fl-wrap">
                                                        <div class="countdown-item">
                                                            <span class="days rot">00</span>
                                                            <p>days</p>
                                                        </div>
                                                        <div class="countdown-item">
                                                            <span class="hours rot">00</span>
                                                            <p>hours </p>
                                                        </div>
                                                        <div class="countdown-item">
                                                            <span class="minutes rot">00</span>
                                                            <p>minutes </p>
                                                        </div>
                                                        <div class="countdown-item">
                                                            <span class="seconds rot">00</span>
                                                            <p>seconds</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('FeBooking', $obj['sku']) }}">Đặt ngay <i
                                                                class="fal fa-paper-plane"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--box-widget-item end -->
                            @endif
                            <!--box-widget-item -->
                            {{-- tại sao tao phải chọn --}}
                            <div class="box-widget-item fl-wrap">
                                <div class="box-widget">
                                    <div class="box-widget-content">
                                        <div class="box-widget-item-header">
                                            <h3 class="chu-voi-nghia">TẠI SAO ĐẶT DỊCH VỤ VỚI VIETRANTOUR</h3>
                                        </div>
                                        <div class="box-widget-list">
                                            <ul>
                                                <li><span><i class="fas fa-check"></i> Giá tốt nhất.</span> 
                                                    {{-- <a href="javascript:void(0);">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['name']) }}</a> --}}
                                                </li>
                                                <li><span><i class="fas fa-check"></i> Phản hồi nhanh nhất.</span> 
                                                    {{-- <a href="tel:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}</a> --}}
                                                </li>
                                                <li><span><i class="fas fa-check"></i> Hỗ trợ khách hàng 24/7.</span> 
                                                    {{-- <a target="_blank"
                                                            href="mailto:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['email']) }}</a> --}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <div class="box-widget-item fl-wrap">
                                    <div class="box-widget">
                                        <div class="box-widget-content">
                                            <div class="box-widget-item-header">
                                                <h3>Phụ trách tư vấn</h3>
                                            </div>
                                            <div class="box-widget-list">
                                                <ul>
                                                    <li><span><i class="fal fa-user"></i>Tên nhân viên :</span> <a
                                                                href="javascript:void(0);">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['name']) }}</a>
                                                    </li>
                                                    <li><span><i class="fal fa-phone"></i> Số điện thoại :</span> <a
                                                                href="tel:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}</a>
                                                    </li>
                                                    <li><span><i class="fal fa-envelope"></i> Email :</span> <a
                                                                target="_blank"
                                                                href="mailto:{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['phone']) }}">{{ \App\Elibs\Helper::showContent(@$obj['thong_tin_huong_dan_vien']['email']) }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-widget-item fl-wrap">
                                    <div class="box-widget">
                                        <div class="box-widget-content">
                                            <div class="box-widget-item-header">
                                                <h3>Giá</h3>
                                            </div>
                                            <div class="box-widget-list">
                                                <ul>
                                                    <li><span><i class="fal fa-user"></i>Giá người lớn :</span> <a
                                                                href="javascript:void(0);"
                                                                class="pricerange text-danger">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_nguoi_lon']) }}</a>
                                                    </li>
                                                    <li><span><i class="fal fa-phone"></i>Giá trẻ em :</span> <a
                                                                href="javascript:void(0);"
                                                                class="pricerange text-danger">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_tre_em']) }}</a>
                                                    </li>
                                                    <li><span><i class="fal fa-envelope"></i>Giá trẻ nhỏ :</span> <a
                                                                href="javascript:void(0);"
                                                                class="pricerange text-danger">{{ \App\Elibs\Helper::formatMoney(@$obj['gia_tre_nho']) }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--box-widget-item end -->
                                <!--box-widget-item -->
                                <div class="box-widget-item fl-wrap">
                                    <div id="weather-widget" class="gradient-bg ideaboxWeather"
                                         data-city="{{ \App\Elibs\Helper::showContent(@$obj['thanh_pho_den']) }}"></div>
                                </div>
                                <!--box-widget-item end -->
                            </div>
                            <!--box-widget-wrap end -->
                        </div>
                        <!--   sidebar end  -->
                    </div>
                    <!--   row end  -->
                </div>
                <!--   container  end  -->
            </section>
            <!--  section  end-->
        </div>
        <!-- content end-->
        <div class="limit-box fl-wrap"></div>
    </div>
@stop

@push('JS_BOTTOM_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/plugins/tables/datatables/datatables.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/plugins/tables/datatables/extensions/responsive.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/demo_pages/datatables_responsive.js') !!}

@endpush