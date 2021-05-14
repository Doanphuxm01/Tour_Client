@extends($THEME_FE_EXTEND)
@section('CONTENT_REGION')
    @if(!isset($lsObj))
    @php($currentTour = (isset($TOURLE)) ? $parent : $tour)
    @endif
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('') }}">Trang chủ</a><span>{{$HtmlHelper['Seo']['title']}}</span></div>
                </div>
            </div>
            <section class="middle-padding gre y-blue-bg" id="regionBooking">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="bookiing-form-wrap">
                                <ul id="progressbar">
                                    <li class="active"><span>01.</span>Số lượng khách</li>
                                    <li><span>02.</span>Thông tin khách du lịch</li>
                                    <li><span>03.</span>Thông tin thanh toán</li>
                                    <li><span>04.</span>Xác nhận chỗ</li>
                                </ul>
                                <!--   list-single-main-item -->
                                <div class="list-single-main-item list-single-main-item-mobile fl-wrap hidden-section tr-sec">
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <form id="mainFormInput">
                                                @php($member = \App\Http\Models\Member::getCurent())
                                                @if(isset($currentTour))
                                                @include('FE::FeBooking.views.include.list-member')
                                                @else
                                                @include('FE::FeBooking.views.cart.list-member')
                                                @endif
                                                @include('FE::FeBooking.views.include.info')
                                                @include('FE::FeBooking.views.include.list-payment')
                                                <fieldset class="fl-wrap book_mdf">
                                                    <div class="list-single-main-item-title fl-wrap">
                                                        <h3>Thành công</h3>
                                                    </div>
                                                    <div class="success-table-container">
                                                        <div class="success-table-header fl-wrap">
                                                            <i class="fal fa-check-circle decsth"></i>
                                                            <h4>Quý khách đã đăng ký thành công thông tin trên hệ thống.</h4>
                                                                <br>
                                                                <h5>Chúng tôi mong sớm nhận được thanh toán dịch vụ của Quý khách để đảm bảo giữ chỗ với mức giá tốt nhất.</h5>
                                                                <h5>Tổng đài của chúng tôi sẵn sàng phục vụ Quý khách từ 8h00 sáng tới 20h00 hàng ngày.</h5>
                                                                <h5>Liên hệ hotline: <a class="text-primary booking-ngo-ngan" href="tel:{{@$IO_CONFIG_WEBSITE['hotline']}}">{{@$IO_CONFIG_WEBSITE['hotline']}}</a></h5>
                                                                <h5>Cảm ơn Quý khách đã sử dụng dịch vụ của Vietrantour.</h5>
                                                            <div class="clearfix"></div>
                                                            {{-- <p>Bạn vui lòng đợi quản trị kiểm duyệt booking của bạn.</p> --}}
                                                            <a href="javascript:void(0);" id="co_cai_lz" class="color-bg xac-nhan-booking-ro">Xem chi tiết</a>
                                                        </div>
                                                    </div>
                                                    <span class="fw-separator"></span>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                </div>
        
                                <!--   list-single-main-item end -->
                            </div>
                        </div>
                        @if(isset($currentTour))
                        <div class="col-md-4">
                            <div class="box-widget-item-header">
                                <h3>Thông tin tour</h3>
                            </div>
                            <div class="cart-details fl-wrap">
                                <!--cart-details_header-->
                                <div class="cart-details_header">
                                    <a href="{{ route('FeTour', ['alias' => $currentTour['alias']]) }}"  class="widget-posts-img">
                                        <img src="{{\App\Http\Models\Media::getImageSrc($currentTour['avatar']['full_size_link'])}}" class="respimg" alt=""></a>
                                    <div class="widget-posts-descr">
                                        <a href="{{ route('FeTour', ['alias' => $currentTour['alias']]) }}" title="{{ $currentTour['name'] }}">{{ $currentTour['name'] }}</a>
                                        <div class="listing-rating card-popup-rainingvis" data-starrating2="{{ @$obj['score'] }}"></div>
                                        <div class="geodir-category-location fl-wrap">
                                            @if(!empty($currentTour['dia_diem_den']))
                                                @foreach($currentTour['dia_diem_den'] as $ls => $location)
                                                    <a href="{{ route('FeTour.Place', ['alias' => @$IO_LOCATION[@$location['id']]['alias']]) }}"
                                                       class="me-2">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        {{ value_show(@$IO_LOCATION[@$location['id']]['name']) }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--cart-details_header end-->
                                <!--ccart-details_text-->
                                <div class="cart-details_text">
                                    <ul class="cart_list">
                                        @if(!isset($TOURLE))
                                            <li>Ngày khởi hành <span>{{ (@$currentTour['ngay_khoi_hanh']) ? \App\Elibs\Helper::showMongoDate($currentTour['ngay_khoi_hanh']) : show_tuan(@$currentTour['thoi_gian_khoi_hanh_hang_tuan']) }}</span></li>
                                        @else
                                            <li>Ngày khởi hành <span>{{ date_time_show(@$tour['ngay_khoi_hanh']) }}</span></li>
                                        @endif
                                        <li>Số lượng chỗ còn trống<span>{{ @$currentTour['so_luong_khach_treo_gio'] ? $currentTour['so_luong_khach_treo_gio'].'/' : '' }}{{ $currentTour['so_luong_khach_toi_da'] }} </span></li>
                                        <li>Số lượng chỗ khách đặt<span>@{{ parseInt(dytNguoiLon.total)+parseInt(dytTreEm.total)+parseInt(dytTreNho.total) }}</span></li>
                                        @php($gia_nguoi_lon = isset($TOURLE) ? $tour['gia_nguoi_lon'] : $currentTour['gia_nguoi_lon'])
                                        @php($gia_tre_em = isset($TOURLE) ? $tour['gia_tre_em'] : $currentTour['gia_tre_em'])
                                        @php($gia_tre_nho = isset($TOURLE) ? $tour['gia_tre_nho'] : $currentTour['gia_tre_nho'])
                                        <li>Người lớn<span><strong>@{{ _FORMAT_MONEY(parseInt(dytNguoiLon.total)*tour.gia_nguoi_lon) }}</strong></span></li>
                                        <li>Trẻ em<span><strong>@{{ _FORMAT_MONEY(parseInt(dytTreEm.total)*tour.gia_tre_em) }}</strong></span></li>
                                        <li>Trẻ nhỏ<span><strong>@{{ _FORMAT_MONEY(parseInt(dytTreNho.total)*tour.gia_tre_nho) }}</strong></span></li>
                                        <li>Tổng tiền<span><strong>@{{ _FORMAT_MONEY(parseInt(dytNguoiLon.total)*tour.gia_nguoi_lon+parseInt(dytTreEm.total)*tour.gia_tre_em+parseInt(dytTreNho.total)*tour.gia_tre_nho) }}</strong></span></li>
                                    </ul>
                                </div>
                                <!--cart-details_text end -->
                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- các tour gợi ý liên quan đến địa điểm vùng miền --}}
                    @if(isset($tour_lien_quan) && $tour_lien_quan->total() > 0)
                    <div class="container">
                        <div class="section-title">
                            <h3 class="thong-tin-khach-puoi">Các tour khác có thể quý khách quan tâm ( gợi ý tour )</h3>
                            <span class="section-separator"></span>
                        </div>
                    </div>
                    <!-- container end-->
                    <!-- carousel -->
                    <div class="list-carousel fl-wrap card-listing ">
                        <!--listing-carousel-->
                        <div class="listing-carousel fl-wrap"
                             data-slick='{"slidesToShow": 4, "slidesToScroll": 4}'>
                            @include('FE::FeTours.views.slick-item', ['lsObj' => $tour_lien_quan])
                        </div>
                        <!--listing-carousel end-->
                        <div class="swiper-button-prev sw-btn">
                            <i class="fa fa-long-arrow-left"></i>
                        </div>
                        <div class="swiper-button-next sw-btn">
                            <i class="fa fa-long-arrow-right"></i>
                        </div>
                        <a class="xem-all btn-more btn-primary"
                           href="{{ route('FeTour', ['alias' => @$IO_TOURCATE[$sku]['alias']]) }}">Xem thêm <i
                                    class="fas fa-caret-right"></i></a>
                    </div>
                    <!--  carousel end-->
                    @endif
                </div>
            </section>
            <!-- section end -->
        </div>
        <!-- content end-->
    </div>
    <style type="text/css">
        .accordion-inner {
            padding: 20px 0;
        }
    </style>
@stop

@section('JS_BOTTOM_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/axios.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/vue.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('backend-ui/assets/js/plugins/pickers/vue2-datepicker.min.css') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/plugins/pickers/vue2-datepicker.min.js') !!}
    <script>
        var typeTour = '{{ isset($TOURLE) ? $TOURLE : '' }}'
        var tourSKU = '{{ @$currentTour['sku'] }}';
        var tourKhoiHanhSKU = '{{ isset($TOURLE) ? $tour['sku'] : @$currentTour['sku'] }}';

        var cart = 0;
        var lsPayment = {!! json_encode($lsPay) !!};
        var member = {!! json_encode($member) !!};
        if(!member) {
            member = {
                name: '', email: '', dien_thoai: '', phone: '', note: ''
            }
        }
    </script>
    @if(isset($lsObj))
        <script>
            cart = '{!! json_encode($lsObj) !!}';
            cart = JSON.parse(cart)
        </script>
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/booking/cart.js') !!}
    @else
        <script>
            var tour = {
                'gia_nguoi_lon':'{{ $gia_nguoi_lon }}',
                'gia_tre_em': '{{ $gia_tre_em }}',
                'gia_tre_nho': '{{ $gia_tre_nho }}',
            };
        </script>
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/booking/booking.js') !!}
    @endif
@stop