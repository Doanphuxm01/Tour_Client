@extends($THEME_EXTEND)

@section('CONTENT_ADMIN_REGION')
    <!--dasboard-wrap-->
    <div class="dasboard-wrap fl-wrap">
        <!-- dashboard-content-->
        <div class="dashboard-content fl-wrap">
            <div class="dashboard-list-box fl-wrap">
                <div class="dashboard-header fl-wrap">
                    <h3>Bookings</h3>
                </div>
                @foreach($lsObj as $obj)
                <!-- dashboard-list end-->
                <div class="dashboard-list">
                    <div class="dashboard-message">
                        <span class="new-dashboard-item"><a target="_blank" href="{{ route('FeBookingSearch', ['id' => @$obj['code']]) }}">Xem chi tiết</a></span>
                        {{--<div class="dashboard-message-avatar">
                            <img src="{{ \App\Http\Models\Media::getImageSrc(@$obj['chi_tiet_tour']['avatar']['full_size_link']) }}" alt="{{ \App\Elibs\Helper::showContent(@$obj['chi_tiet_tour']['name']) }}">
                        </div>--}}
                        <div class="dashboard-message-text">
                            <h4>Mã Booking: {{ value_show($obj['code']) }}</h4>
                            {{--<div class="booking-details fl-wrap">
                                <span class="booking-title">Số lượng :</span>
                                <span class="booking-text">{{ @$obj['nguoi_lon']['total']+@$obj['tre_em']['total']+@$obj['tre_nho']['total'] }} người</span>
                            </div>--}}
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Thời gian đi:</span>
                                <span class="booking-text">{{ (@$obj['chi_tiet_tour']['ngay_khoi_hanh']) ? \App\Elibs\Helper::showMongoDate($obj['chi_tiet_tour']['ngay_khoi_hanh']) : show_tuan(@$obj['chi_tiet_tour']['thoi_gian_khoi_hanh_hang_tuan']) }}</span>
                            </div>
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Ngày tạo :</span>
                                <span class="booking-text">{{ \App\Elibs\Helper::showMongoDate($obj['created_at']) }}</span>
                            </div>
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Email :</span>
                                <span class="booking-text"><a href="mailto:{{ \App\Elibs\Helper::showContent(@$obj['info']['email']) }}" target="_top">{{ \App\Elibs\Helper::showContent(@$obj['info']['email']) }}</a></span>
                            </div>
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Di động :</span>
                                <span class="booking-text"><a href="tel:{{ value_show(@$obj['info']['phone']) }}" target="_top">{{ \App\Elibs\Helper::showContent(@$obj['info']['phone']) }}</a></span>
                            </div>
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Hình thức thanh toán :</span>
                                <span class="booking-text"> <strong class="done-paid">{{ \App\Http\Models\Payment::getListPayment(@$obj['payment'], true)['text'] }}  </strong></span>
                            </div>
                            <div class="booking-details fl-wrap">
                                <span class="booking-title">Trạng thái :</span>
                                <span class="booking-text"> <strong class="done-paid text-{{ @\App\Http\Models\Booking::getListStatus(@$obj['status'], true)['style'] }}">{{ @\App\Http\Models\Booking::getListStatus(@$obj['status'], true)['text'] }}</strong></span>
                            </div>
                            <span class="fw-separator"></span>
                        </div>
                    </div>
                </div>
                <!-- dashboard-list end-->
                @endforeach
            </div>
            <!-- pagination-->
            {{ $lsObj->render() }}
        </div>
        <!-- dashboard-list-box end-->
    </div>
    <!-- dasboard-wrap end-->
@stop
