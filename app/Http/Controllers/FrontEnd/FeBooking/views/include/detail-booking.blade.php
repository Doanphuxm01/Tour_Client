@extends($THEME_FE_EXTEND)
@section('CSS_REGION')
    <style>
        @media screen {
            #printSection {
                display: none;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printSection, #printSection * {
                visibility: visible;
            }

            #printSection {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
        #form-booku .bot, #form-booku .font500 {
            text-align: left;
            font-family: Helvetica, Arial, sans-serif;
        }
        .thanks {
            padding-top: 20px;
            display: block;
            height: 60px;
            margin-bottom: 40px;
            background-color: #003b93;
            font-family: Arial, Helvetica, Tahoma, sans-serif;
        }
        .confirm {
            width: 49%;
            float: left;
            height: 340px;
        }

        .info-lz {
            width: 49%;
            float: right;
            height: 340px;
        }

        .chi-tiet-booking {
            margin-top: 100px;
        }

        p {
            text-align: center;
            font-size: 25px;
           
        }

        .title {
            font-size: 16px;
            line-height: 25px;
            padding-bottom: 15px;
            text-transform: uppercase;
            font-weight: bold;
            color: #4b4f56;
            text-align: left;
            font-family: Arial, Helvetica, Tahoma, sans-serif;  

        }

        .xnbooking {
            width: 100%;
            padding: 15px;
            height:370px;
            padding-top: 10px;
        }

        .bor-bot {
            display: flex;
            border-bottom: 1px dashed #e1e1e1;
            padding: 10px;  
            font-weight: 600;
        }

        .font500 {
            width: 70%;
            font-weight: 500;
        }

        .bot {
            width: 25%;
            
        }

        @media only screen and (max-width: 600px) and (min-width: 320px) {
            .container {
                width: auto;
                margin: auto;
            }

            .confirm {
                width: 100%;

            }

            .info-lz {
                width: 100%;
                height: 230px;
            }

            .chi-tiet-booking {
                margin-top: 100px;
            }
            .thanks{
                height:90px;
            }
        }

        @media only screen and (max-width: 768px) and (min-width: 320px) {
            .container {
                width: auto;
                margin: auto;
            }

            .confirm {
                width: 100%;

            }

            .info-lz {
                width: 100%;
            }

            .chi-tiet-booking {
                margin-top: 100px;
            }
            .thanks{
                height:90px;
            }
        }

        /* Large devices (laptops/desktops, 992px and up) */

    </style>
@stop
@section('CONTENT_REGION')
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('') }}">Trang
                            chủ</a><span>{{$HtmlHelper['Seo']['title']}}</span></div>
                </div>
            </div>
            <section class="middle-padding gre y-blue-bg">
                <div class="container" id="form-booku">
                    <!--THANKYOU-->
                    <div class="thanks">
                        <p style="color:#fff;">Cảm ơn quý khác đã sử dụng dịch vụ của chúng tôi</p>
                    </div>

                    <div class="confirm">
                        <div class="title">
                            PHIẾU XÁC NHẬN BOOKING
                        </div>
                        @if(@$obj['chi_tiet_gio_hang'])
                            <style>
                                .confirm, .info-lz {
                                    width: unset;
                                    float: unset;
                                    height: unset;
                                }
                                .xnbooking {
                                    height: unset;
                                }
                                .chi-tiet-booking {
                                    margin-top: 10px;
                                }
                            </style>
                            @foreach($obj['chi_tiet_gio_hang'] as $o)
                                <div class="xnbooking mb-2">
                                    <!-- <div class="bor-bot">
                                        <div class="bot">Mã tour:</div>
                                        <div class="font500">{{ value_show(@$o['sku']) }}</div>
                                    </div> -->
                                    <div class="bor-bot">
                                        <div class="bot">Tên tour:</div>
                                        <div class="font500">
                                            {{ value_show(@$o['name']) }}
                                        </div>
                                    </div>
                                    <div class="bor-bot">
                                        <div class="bot">Tuyến tour:</div>
                                        <div class="font500">
                                            {{ value_show(@$o['tuyen_tour'][0]??$o['tuyen_tour']) }}
                                        </div>
                                    </div>
                                    <div class="bor-bot">
                                        <div class="bot">Ngày khởi hành:</div>
                                        <div class="font500">
                                            {{ (@$o['ngay_khoi_hanh']) ? date_time_show(@$o['ngay_khoi_hanh']) : show_tuan(@$o['thoi_gian_khoi_hanh_hang_tuan']) }}
                                        </div>
                                    </div>
                                    @if(@$o['ngay_ket_thuc'])
                                    <div class="bor-bot">
                                        <div class="bot">Ngày về:</div>
                                        <div class="font500">
                                            {{ date_time_show($o['ngay_ket_thuc']) }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="bor-bot">
                                        <div class="bot">Nơi khởi hành:</div>
                                        <div class="font500">
                                            {{ value_show(@$o['dia_diem_khoi_hanh']) }}
                                        </div>
                                    </div>
                                    <div class="bor-bot">
                                        <div class="bot">Số khách:</div>
                                        <div class="font500">
                                            {{ value_show(@$o['info']['nguoi_lon']['total']+@$o['info']['tre_em']['total']+@$o['info']['tre_nho']['total']) }}
                                            (Người lớn: {{ @$o['info']['nguoi_lon']['total'] }} Trẻ
                                            em: {{ @$o['info']['tre_em']['total']?:0 }} Trẻ nhỏ: {{ @$o['info']['tre_nho']['total']?:0 }})
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="xnbooking">
                            <!-- <div class="bor-bot">
                                <div class="bot">Mã tour:</div>
                                <div class="font500">{{ value_show(@$obj['chi_tiet_tour']['sku']) }}</div>
                            </div> -->
                            <div class="bor-bot">
                                <div class="bot">Tên tour:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['chi_tiet_tour']['name']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Tuyến tour:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['chi_tiet_tour']['tuyen_tour'][0]??$obj['chi_tiet_tour']['tuyen_tour']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Ngày khởi hành:</div>
                                <div class="font500">
                                    {{ (@$obj['chi_tiet_tour']['ngay_khoi_hanh']) ? date_time_show($obj['chi_tiet_tour']['ngay_khoi_hanh']) : show_tuan(@$obj['chi_tiet_tour']['thoi_gian_khoi_hanh_hang_tuan']) }}
                                </div>
                            </div>
                            @if(@$obj['chi_tiet_tour']['ngay_ket_thuc'])
                            <div class="bor-bot">
                                <div class="bot">Ngày về:</div>
                                <div class="font500">
                                    {{ date_time_show(@$obj['chi_tiet_tour']['ngay_ket_thuc']) }}
                                </div>
                            </div>
                            @endif
                            <div class="bor-bot">
                                <div class="bot">Nơi khởi hành:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['chi_tiet_tour']['dia_diem_khoi_hanh']) }}
                                </div>
                            </div>
                            {{--<div class="bor-bot">
                                <div class="bot">Phương tiện đi:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['chi_tiet_tour']['phuong_tien_di']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Phương tiện về:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['chi_tiet_tour']['phuong_tien_ve']) }}
                                </div>
                            </div>--}}
                        </div>
                        @endif
                    </div>
                    <div class="info-lz">
                        <div class="title">
                            THÔNG TIN LIÊN LẠC
                        </div>
                        <div class="xnbooking">
                            <div class="bor-bot">
                                <div class="bot">Họ Tên:</div>
                                <div class="font500">{{ value_show(@$obj['info']) }}</div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Email:</div>
                                <div class="font500">
                                    {{ value_show(@$obj['info']['email']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Di động</div>
                                <div class="font500" style="display: flex;">
                                    {{ value_show(@$obj['info']['phone']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Số khách:</div>
                                <div class="font500">
                                    @if(@$obj['chi_tiet_gio_hang'])

                                    @else
                                    {{ value_show(@$obj['nguoi_lon']['total']+@$obj['tre_em']['total']+@$obj['tre_nho']['total']) }}
                                    (Người lớn: {{ @$obj['nguoi_lon']['total'] }} Trẻ
                                    em: {{ @$obj['tre_em']['total']?:0 }} Trẻ nhỏ: {{ @$obj['tre_nho']['total']?:0 }})
                                    @endif
                                </div>
                            </div>
                            {{--<div class="bor-bot">
                                <div class="bot">Ghi chú:</div>
                                <div class="font500">
                                    Booking từ travel.com.vn. Thời hạn thanh toán 02/07/2020 15:50:35.
                                    02/07 CHỦ SĐT BÁO KHÔNG ĐĂNG KÝ TOUR - KHANH ĐÃ MAIL BÁO THÔNG TIN - KHANH ONL
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="chi-tiet-booking">
                        <div class="title" @if(!@$obj['chi_tiet_gio_hang']) style="text-align: center !important;font-size:25px" @endif>
                            CHI TIẾT BOOKING
                        </div>
                        <div class="xnbooking">
                            <div class="bor-bot">
                                <div class="bot">Số booking:</div>
                                <div class="font500"><span class="text-danger">{{ value_show(@$obj['code']) }}</span>
                                    <br>
                                    (Quý khách vui lòng nhớ số booking (Booking No) để thuận tiện cho các giao dịch sau này)</div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Trị giá booking:</div>
                                <div class="font500">
                                    {{ \App\Elibs\Helper::formatMoney(@$obj['total_money']) }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Ngày đăng ký:</div>
                                <div class="font500">
                                    {{ date_time_show(@$obj['created_at'], 'd/m/Y H:i:s') }} (Theo giờ Việt Nam)
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Hình thức thanh toán:</div>
                                <div class="font500">
                                    {{ @\App\Http\Models\Payment::getListPayment($obj['payment'], true)['text'] }}
                                </div>
                            </div>
                            <div class="bor-bot">
                                <div class="bot">Tình trạng:</div>
                                <div class="font500">
                                    <span class="text-{{ @\App\Http\Models\Booking::getListStatus($obj['status'], true)['style'] }}">{{ @\App\Http\Models\Booking::getListStatus($obj['status'], true)['text'] }}</span>
                                </div>
                            </div>
                            {{--<div class="bor-bot">
                                <div class="bot">Thời hạn thanh toán:</div>
                                <div class="font500">
                                    Hồ Chí Minh
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@stop