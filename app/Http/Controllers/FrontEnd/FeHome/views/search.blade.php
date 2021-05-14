@extends('frontend')

@section('CSS_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css') !!}
    <style>
        .section-product-sugget {
            background-color: #fff;
        }
        .product-sugget:hover .name {
            color: #007c39;
        }
        .product-sugget .name {
            color: #000;
        }
    </style>
@endsection

@section('JS_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('mpg-tmp/giaodienmuahang/js/magiczoom.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js') !!}
@endsection

@section('CONTENT_REGION')
    <div id="wrapper">
        <div class="content">
            <div class="col-list-wrap left-list col-12">
                <section class="grey-blue-bg">
                    <!-- container-->
                    <div class="container">
                        <div class="section-title">
                            {{-- <div class="section-title-separator"><span></span></div> --}}
                            <a href="#"><h2>TOUR ĐƯỢC TÌM KIẾM </h2></a> <span
                                    class="section-separator"></span>
                            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar.</p> -->
                        </div>
                    </div>
                    <!-- container end-->
                    <div class="container-fluid">
                        <div class="row init-grid-items position-relative">
                            @if(count($lsObj) > 0)
                                @foreach($lsObj as $obj)
                                    <!-- listing-item  -->
                                    @include('FE::FeTours.views.grid-item', ['countdown' => false])
                                    <!-- listing-item end -->
                                    @endforeach
                            @else
                                <div class="">
                                    <!--  section  -->
                                    <section class="color-bg parallax-section">
                                        <div class="city-bg"></div>
                                        <div class="cloud-anim cloud-anim-bottom x1"><i class="fal fa-cloud"></i></div>
                                        <div class="cloud-anim cloud-anim-top x2"><i class="fal fa-cloud"></i></div>
                                        <div class="overlay op1 color3-bg"></div>
                                        <div class="container">
                                            <div class="error-wrap">
                                                <h6 style="color: white">Hiện Tại Chúng tôi Chưa Có Dữ liệu Cho Thông Tin Của Bạn !</h6>
{{--                                                    <p>We're sorry, but the Page you were looking for, couldn't be found.</p>--}}
                                                <div class="clearfix"></div>
{{--                                                    <form action="#">--}}
{{--                                                        <input name="se" id="se" type="text" class="search" placeholder="Search.." value="">--}}
{{--                                                        <button class="search-submit color-bg" id="submit_btn"><i class="fal fa-search"></i> </button>--}}
{{--                                                    </form>--}}
{{--                                                    <div class="clearfix"></div>--}}
{{--                                                    <p>Or</p>--}}
{{--                                                    <a href="index.html" class="btn     color2-bg flat-btn">Back to Home Page<i class="fal fa-home"></i></a>--}}
                                            </div>
                                        </div>
                                    </section>
                                    <section class="grey-blue-bg">
                                        <!-- container-->
                                        <div class="container">
                                            <div class="section-title">
                                                {{-- <div class="section-title-separator"><span></span></div> --}}
                                                <a href="{{ route('FeTour', ['action' => 'tour-khuyen-mai']) }}"><h2>TOUR KHUYẾN MẠI</h2></a> <span
                                                        class="section-separator"></span>
                                                <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar.</p> -->
                                            </div>
                                        </div>
                                        <!-- container end-->
                                        <!-- carousel -->
                                        <div class="list-carousel fl-wrap card-listing ">
                                            <!--listing-carousel-->
                                            <div class="listing-carousel fl-wrap"
                                                 data-slick='{"slidesToShow": 4, "slidesToScroll": 4}'>
                                                @include('FE::FeTours.views.slick-item', ['lsObj' => $lsToursKhuyenMai, 'sale' => true])
                                            </div>
                                            <!--listing-carousel end-->
                                            <div class="swiper-button-prev sw-btn">
                                                <i class="fa fa-long-arrow-left"></i>
                                            </div>
                                            <div class="swiper-button-next sw-btn">
                                                <i class="fa fa-long-arrow-right"></i>
                                            </div>
                                        </div>
                                        <!--  carousel end-->
                                    </section>
                                    <!--  section  end-->
                                </div>

                            @endif

                        </div>
                    </div>
                </section>
            <!-- list-main-wrap end-->
                <!-- phan trang-->
                @include('site/pagination',['paginator' => @$lsObj])
            </div>
        </div>
    </div>

@stop
@section('JS_BOTTOM_REGION')
    <script type="text/javascript">
        $('[data-fancybox="gallery"]').fancybox({});
        if (window.matchMedia && window.matchMedia('(max-width: 979px)').matches && MagicZoom !== undefined) {
            MagicZoom.options['zoom-position'] = 'inner';
            MagicZoom.refresh();
        } else if (window.matchMedia && window.matchMedia('(min-width: 980px)').matches && MagicZoom !== undefined) {
            MagicZoom.options['zoom-position'] = 'right';
            MagicZoom.options = {
                'zoom-width': 380,
                'zoom-height': 380
            }
            MagicZoom.refresh();
        }
        $(window).bind('load', function(){
            $('#menu').mmenu();
        });

    </script>
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('mpg-tmp/giaodienmuahang/js/cart/pdetail.js') !!}
@endsection