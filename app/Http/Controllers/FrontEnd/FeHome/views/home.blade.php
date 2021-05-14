@extends($THEME_FE_EXTEND)
@section('CONTENT_REGION')
    {{--	js-bannner--}}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js banner/jssor.slider-28.1.0.min.js') !!}
    {{--	<script src="{{ asset('js/jssor.slider-28.0.0.min.js') }}" type="text/javascript"></script>--}}
    <script type="text/javascript">
        window.jssor_1_slider_init = function() {

            var jssor_1_SlideoTransitions = [
                [{b:-1,d:1,ls:0.5},{b:0,d:1000,y:5,e:{y:6}}],
                [{b:-1,d:1,ls:0.5},{b:200,d:1000,y:25,e:{y:6}}],
                [{b:-1,d:1,ls:0.5},{b:400,d:1000,y:45,e:{y:6}}],
                [{b:-1,d:1,ls:0.5},{b:600,d:1000,y:65,e:{y:6}}],
                [{b:-1,d:1,ls:0.5},{b:800,d:1000,y:85,e:{y:6}}],
                [{b:-1,d:1,ls:0.5},{b:500,d:1000,y:195,e:{y:6}}],
                [{b:0,d:2000,y:30,e:{y:3}}],
                [{b:-1,d:1,rY:-15,tZ:100},{b:0,d:1500,y:30,o:1,e:{y:3}}],
                [{b:-1,d:1,rY:-15,tZ:-100},{b:0,d:1500,y:100,o:0.8,e:{y:3}}],
                [{b:500,d:1500,o:1}],
                [{b:0,d:1000,y:380,e:{y:6}}],
                [{b:300,d:1000,x:80,e:{x:6}}],
                [{b:300,d:1000,x:330,e:{x:6}}],
                [{b:-1,d:1,r:-110,sX:5,sY:5},{b:0,d:2000,o:1,r:-20,sX:1,sY:1,e:{o:6,r:6,sX:6,sY:6}}],
                [{b:0,d:600,x:150,o:0.5,e:{x:6}}],
                [{b:0,d:600,x:1140,o:0.6,e:{x:6}}],
                [{b:-1,d:1,sX:5,sY:5},{b:600,d:600,o:1,sX:1,sY:1,e:{sX:3,sY:3}}]
            ];

            var jssor_1_options = {
                $AutoPlay: 1,
                $LazyLoading: 1,
                $CaptionSliderOptions: {
                    $Class: $JssorCaptionSlideo$,
                    $Transitions: jssor_1_SlideoTransitions
                },
                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$
                },
                $BulletNavigatorOptions: {
                    $Class: $JssorBulletNavigator$,
                    $SpacingX: 20,
                    $SpacingY: 20
                }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 3000;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
    </script>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,regular,italic,700,700italic&subset=latin-ext,greek-ext,cyrillic-ext,greek,vietnamese,latin,cyrillic" rel="stylesheet" type="text/css" />
    <style>
        /* jssor slider loading skin spin css */
        .jssorl-009-spin img {
            animation-name: jssorl-009-spin;
            animation-duration: 1.6s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes jssorl-009-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        /*jssor slider bullet skin 132 css*/
        .jssorb132 {position:absolute;}
        .jssorb132 .i {position:absolute;cursor:pointer;}
        .jssorb132 .i .b {fill:#fff;fill-opacity:0.8;stroke:#000;stroke-width:1600;stroke-miterlimit:10;stroke-opacity:0.7;}
        .jssorb132 .i:hover .b {fill:#000;fill-opacity:.7;stroke:#fff;stroke-width:2000;stroke-opacity:0.8;}
        .jssorb132 .iav .b {fill:#000;stroke:#fff;stroke-width:2400;fill-opacity:0.8;stroke-opacity:1;}
        .jssorb132 .i.idn {opacity:0.3;}

        .jssora051 {display:block;position:absolute;cursor:pointer;}
        .jssora051 .a {fill:none;stroke:#fff;stroke-width:360;stroke-miterlimit:10;}
        .jssora051:hover {opacity:.8;}
        .jssora051.jssora051dn {opacity:.5;}
        .jssora051.jssora051ds {opacity:.3;pointer-events:none;}
    </style>
    <svg viewbox="0 0 0 0" width="0" height="0" style="display:block;position:relative;left:0px;top:0px;">
        <defs>
            <filter id="jssor_1_flt_1" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stddeviation="4"></feGaussianBlur>
            </filter>
            <radialGradient id="jssor_1_grd_2">
                <stop offset="0" stop-color="#fff"></stop>
                <stop offset="1" stop-color="#000"></stop>
            </radialGradient>
            <mask id="jssor_1_msk_3">
                <path fill="url(#jssor_1_grd_2)" d="M600,0L600,400L0,400L0,0Z" x="0" y="0" style="position:absolute;overflow:visible;"></path>
            </mask>
        </defs>
    </svg>
    {{--end-js-banner--}}
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!--section -->
            <section class="hero-section no-padding" data-scrollax-parent="true" id="sec1">
                <div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;visibility:hidden;">
                    <!-- Loading Screen -->
                    <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
                        <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="img/spin.svg" />
                    </div>
                    <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;">
                        <div style="background-color:#d3890e;">
                            <img data-u="image" style="opacity:0.8;" data-src="{{ public_link('vietrantour/images/banner/con-dao3.jpg') }}" />
                            <div data-ts="flat" data-p="275" data-po="40% 50%" style="left:150px;top:40px;width:800px;height:300px;position:absolute;">
                                <div data-to="50% 50%" data-t="0" style="left:50px;top:520px;width:400px;height:100px;position:absolute;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Traveling</div>
                                <div data-to="50% 50%" data-t="1" style="left:50px;top:540px;width:400px;height:100px;position:absolute;opacity:0.5;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Traveling</div>
                                <div data-to="50% 50%" data-t="2" style="left:50px;top:560px;width:400px;height:100px;position:absolute;opacity:0.25;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Traveling</div>
                                <div data-to="50% 50%" data-t="3" style="left:50px;top:580px;width:400px;height:100px;position:absolute;opacity:0.125;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Traveling</div>
                                <div data-to="50% 50%" data-t="4" style="left:50px;top:600px;width:400px;height:100px;position:absolute;opacity:0.06;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Traveling</div>
                                <div data-to="50% 50%" data-t="5" style="left:50px;top:710px;width:700px;height:100px;position:absolute;color:#f0a329;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;letter-spacing:0.5em;">Vietrantour</div>
                            </div>
                        </div>
                        <div>
                            <img data-u="image" data-src="{{ public_link('vietrantour/images/bg/2.jpg') }}" />
                            <div data-ts="flat" data-p="540" data-po="40% 50%" style="left:0px;top:0px;width:1600px;height:560px;position:absolute;">
                                <div data-to="50% 50%" data-ts="preserve-3d" data-t="6" style="left:350px;top:360px;width:900px;height:500px;position:absolute;">
                                    <svg viewbox="0 0 800 60" data-to="50% 50%" width="800" height="60" data-t="7" style="left:0px;top:-70px;display:block;position:absolute;opacity:0;font-family:'Roboto Condensed',sans-serif;font-size:60px;font-weight:700;letter-spacing:0.05em;overflow:visible;">
                                        <text fill="#454447" stroke="#ff9500" stroke-width="2" text-anchor="middle" x="400" y="60">Interesting with
                                        </text>
                                    </svg>
                                    <div data-to="50% 50%" data-t="8" style="filter:url('#jssor_1_flt_1');left:200px;top:0px;width:600px;height:60px;position:absolute;opacity:0;color:#C49D8F;font-family:Roboto Condensed, sans-serif;font-size:48px;line-height:1.2;letter-spacing:0.1em;text-align:center;">FOR STYLISH LIFE</div>
                                    <svg viewbox="0 0 800 100" width="800" height="100" data-t="9" style="left:40px;top:250px;display:block;position:absolute;opacity:0;font-family:'Roboto Condensed',sans-serif;font-size:100px;font-weight:900;letter-spacing:0.5em;overflow:visible;">
                                        <text fill="rgba(255,255,255,0.7)" stroke="#ff9500" text-anchor="middle" x="400" y="100">Tours
                                        </text>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#000000;">
                            <img data-u="image" style="opacity:0.8;" data-src="{{ public_link('vietrantour/images/banner/hinh-anh-hoa-ca-phe-tay-nguyen-11.jpg') }}" />
                            <div data-ts="flat" data-p="1080" style="left:0px;top:0px;width:1600px;height:560px;position:absolute;">
                                <svg viewbox="0 0 600 400" data-ts="preserve-3d" width="600" height="400" data-tchd="jssor_1_msk_3" style="left:255px;top:0px;display:block;position:absolute;overflow:visible;">
                                    <g mask="url(#jssor_1_msk_3)">
                                        <path data-to="300px -180px" fill="none" stroke="rgba(250,251,252,0.5)" stroke-width="20" d="M410-350L410-10L190-10L190-350Z" x="190" y="-350" data-t="10" style="position:absolute;overflow:visible;"></path>
                                    </g>
                                </svg>
                                <svg viewbox="0 0 800 72" data-to="50% 50%" width="800" height="72" data-t="11" style="left:-800px;top:78px;display:block;position:absolute;font-family:'Roboto Condensed',sans-serif;font-size:84px;font-weight:900;overflow:visible;">
                                    <text fill="#fafbfc" text-anchor="middle" x="400" y="72">Du lịch cùng
                                    </text>
                                </svg>
                                <svg viewbox="0 0 800 72" data-to="50% 50%" width="800" height="72" data-t="12" style="left:1600px;top:153px;display:block;position:absolute;font-family:'Roboto Condensed',sans-serif;font-size:60px;font-weight:900;overflow:visible;">
                                    <text fill="#fafbfc" text-anchor="middle" x="400" y="72">Vietrantour
                                    </text>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <img data-u="image" data-src="{{ public_link('vietrantour/images/banner/hoa-dao-tay-bac.jpg') }}" />
                            <div data-ts="flat" data-p="1080" style="left:0px;top:0px;width:1600px;height:560px;position:absolute;">
                                <div data-to="50% 50%" data-t="13" style="left:100px;top:-20px;width:800px;height:200px;position:absolute;opacity:0;">
                                    <div style="left:94px;top:35px;width:480px;height:90px;position:absolute;color:rgb(10, 199, 183);font-family:'Roboto Condensed',sans-serif;font-size:72px;line-height:1.2;">Du lịch cùng</div>
                                    <div style="left:307px;top:115px;width:400px;height:50px;position:absolute;color:rgb(27, 216, 200);font-family:'Roboto Condensed',sans-serif;font-size:42px;line-height:1.1;text-align:center;background-color:rgba(72,77,76,0.5);">Gia đình của bạn</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <img data-u="image" data-src="{{ public_link('vietrantour/images/banner/pexels-bagus-pangestu-1440476.jpg') }}" />
                            <div data-ts="flat" data-p="1080" style="left:0px;top:0px;width:1600px;height:560px;position:absolute;">
                                <div data-to="50% 50%" data-t="14" style="left:690px;top:140px;width:600px;height:150px;position:absolute;color:rgb(199, 130, 2);opacity:0;font-family:Georgia,'Times New Roman',Times,serif;font-size:60px;line-height:1.2;letter-spacing:0.1em;">Du lịch tới<br />Châu Âu</div>
                                {{-- <img data-to="50% 50%" data-t="15" style="left:780px;top:350px;width:344px;height:157px;position:absolute;opacity:0;max-width:344px;" data-src="{{ public_link('vietrantour/images/bg/wine-atlantic-ocean.png') }}" /> --}}
                                <img data-to="50% 50%" data-t="16" style="left:1330px;top:22px;width:172px;height:68px;position:absolute;opacity:0;max-width:172px;" data-src="{{ public_link('vietrantour/images/logoMain.png') }}" />
                            </div>
                        </div>
                    </div><a data-scale="0" href="https://www.jssor.com" style="display:none;position:absolute;">slider html</a>
                    <!-- Bullet Navigator -->
                    <div data-u="navigator" class="jssorb132" style="position:absolute;bottom:24px;right:16px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
                        <div data-u="prototype" class="i" style="width:12px;height:12px;">
                            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                                <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                            </svg>
                        </div>
                    </div>
                    <!-- Arrow Navigator -->
                    <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
                        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                            <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
                        </svg>
                    </div>
                    <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
                        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                            <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
                        </svg>
                    </div>
                </div>
            </section>

            <script type="text/javascript">jssor_1_slider_init();
            </script>
            <!-- section end -->

            <!--section -->
            <section class="pb-0" style="height: 197px;padding:10px 0;">
                <div class="container-fluid ">
                    <!-- -->
                @include('FE::FeHome.views.include.combo')
                <!--process-wrap   end-->
                </div>
            </section>
            <!-- section end -->

            <!--section -->

        @if(!empty($lsToursGioChot))
                <section class="grey-blue-bg">
                    <div class="container">
                        <div class="section-title">
                            <a href="{{ route('FeTour', ['action' => 'tour-gio-chot']) }}"><h2>TOUR GIỜ CHÓT</h2></a>
                            <span
                                    class="section-separator"></span>
                        </div>
                    </div>
                    <div class="list-carousel fl-wrap card-listing ">
                        <div class="listing-carousel fl-wrap"
                             data-slick='{"slidesToShow": 4, "slidesToScroll": 4}'>
                            @foreach($lsToursGioChot as $obj)
                                @include('FE::FeTours.views.grid-item', ['countdown' => true])
                            @endforeach
                        </div>
                        <div class="swiper-button-prev sw-btn">
                            <i class="fa fa-long-arrow-left"></i>
                        </div>
                        <div class="swiper-button-next sw-btn">
                            <i class="fa fa-long-arrow-right"></i>
                        </div>
                    </div>
                    <!--  carousel end-->
                    <a class="xem-all btn-more btn-primary" href="{{ route('FeTour', ['alias' => 'tour-gio-chot']) }}">Xem thêm <i
                                class="fas fa-caret-right"></i></a>
                </section>

            @endif

        @if(!empty($lsToursGioChot))
        <section class="grey-blue-bg">
            <!-- container-->
            <div class="container">
                <div class="section-title">
                    {{-- <div class="section-title-separator"><span></span></div> --}}
                    <a href="{{ route('FeTour', ['action' => 'tour-khuyen-mai']) }}"><h2>TOUR KHUYẾN MẠI</h2></a>
                    <span class="section-separator"></span>
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
            <a style="margin-left: 85%;font-size: 15px;" href="{{ route('FeTour', ['action' => 'tour-khuyen-mai']) }}">Xem thêm <i
                        class="fas fa-caret-right"></i></a>
        </section>
        @endif
            @if(isset($lsTourByDanhMuc))
                @foreach($lsTourByDanhMuc as $sku => $danhmuc)
                    
                        <section class="grey-blue-bg section-home">
                            <!-- container-->
                            <div class="container">
                                <div class="section-title">
                                    {{-- <div class="section-title-separator"><span></span></div> --}}
                                    <a href="{{ route('FeTour', ['alias' => @$IO_TOURCATE[$sku]['alias']]) }}">
                                        <h2>{{ \App\Elibs\Helper::showContent(@$IO_TOURCATE[$sku]['name']) }}</h2></a>
                                    <a class=""
                                       href="{{ route('FeTour', ['alias' => @$IO_TOURCATE[$sku]['alias']]) }}">Xem thêm <i
                                                class="fas fa-caret-right"></i></a>
                                    <span
                                            class="section-separator"></span>
                                    {{--<p>{{ \App\Elibs\Helper::showContent(@$IO_TOURCATE[$sku]['name']) }}</p>--}}
                                </div>
                            </div>
                            <!-- container end-->
                            <!-- carousel -->
                            <div class="list-carousel fl-wrap card-listing ">
                                <!--listing-carousel-->
                                <div class="listing-carousel fl-wrap">
                                    @include('FE::FeTours.views.slick-item', ['lsObj' => $danhmuc])
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
                                
                @endforeach
            @endif
            @if(isset($lsSieuThiTienIch))
                <section class=" middle-padding">
                    <div class="container">
                        <div class="section-title">
                            {{-- <div class="section-title-separator"><span></span></div> --}}
                            {{--<a href="{{ route('FeContent.NewsCate', ['category' => $aliasCateSieuThiTienIch]) }}"><h2>Siêu Thị Tiện Ích</h2></a>--}}
                            <h2 style="text-transform: uppercase">Siêu Thị Tiện Ích</h2>
                           <span class="section-separator"></span>
                           <!-- <p>Browse the latest articles from our blog.</p> -->
                       </div>
                       <div class="home-posts">
                       @php($cate = \App\Http\Models\BaseModel::NOCATE)
                       <!-- carousel -->
                           <div class="list-carousel fl-wrap card-listing ">
                               <!--listing-carousel-->
                               <div class="listing-carousel fl-wrap"
                                    data-slick='{"slidesToShow": 4, "slidesToScroll": 1}'>
                                   @foreach($lsSieuThiTienIch as $obj)

                                       @if(isset($obj['categories']))
                                           @dd($obj['categories'])
                                           @foreach($obj['categories'] as $val)
                                               @if($val['type']==\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                                                   @php($cate = $val['alias']) @break
                                               @endif
                                           @endforeach
                                       @endif
                                       <div class="slick-slide-item">
                                           <!-- listing-item  -->
                                           <div class="listing-item vcl-list-item">
                                               <article class="card-post">
                                                   <div class="card-post-img fl-wrap">
                                                       <a href="{{ route('FeContent.NewsCate', ['category' => $obj['alias']]) }}"><img class="fix-img"
                                                                   src="{{ \App\Http\Models\Media::getImageSrc($obj['avatar']) }}"
                                                                   alt="{{ \App\Elibs\Helper::showContent($obj['name']) }}"></a>
                                                   </div>
                                                   <div class="card-post-content fl-wrap">
                                                       <h3 class="content-dau-buoi">
                                                           <a href="{{ route('FeContent.NewsCate', ['category' => $obj['alias']]) }}">{{ \App\Elibs\Helper::showContent($obj['name']) }}</a>
                                                       </h3>
                                                    </div>
                                                </article>
                                            </div>
                                        </div>
                                    @endforeach
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
                        </div>
                        {{--<a href="blog.html" class="btn-more color-bg">Xem Thêm Nhiều<i
                            class="fas fa-caret-right"></i></a>--}}
                    </div>
                    <div class="section-decor"></div>
                </section>

            @endif

            @if(@$configHome['dia_diem_den'])
                <section class=" middle-padding">
                    <div class="container">
                        <div class="section-title">
                            {{-- <div class="section-title-separator"><span></span></div> --}}
                            <a href="#"><h2>ĐỊA ĐIỂM ĐẾN HOT NHẤT</h2></a> <span
                                    class="section-separator"></span>
                        </div>
                    </div>
                    <!-- portfolio start -->
                    <div class="container">
                        <div class="row fl-wrap mr-bot spad home-grid">
                        @foreach($configHome['dia_diem_den'] as $obj)
                            <!-- gallery-item-->
                                <div class="col-md-3">
                                    <div class="grid-item-holder">
                                        <div class="listing-item-grid">
                                            @if(@$obj['total_tour'])
                                                <div class="listing-counter">
                                                    <span>{{ $obj['total_tour'] }} </span> Điểm du lịch
                                                </div>
                                            @endif
                                            <a href="{{ route('FeTour.Place', ['alias' => $obj['alias']]) }}">
                                                <img height="200px"
                                                     src="{{ \App\Http\Models\Media::getImageSrc($obj['avatar']['relative_link']) }}"
                                                     alt="{{ \App\Elibs\Helper::showContent($obj['name']) }}">
                                            </a>
                                            <div class="listing-item-cat">
                                                <h3>
                                                    <a class="dia-diem-con-cac" href="{{ route('FeTour.Place', ['alias' => $obj['alias']]) }}">{{ \App\Elibs\Helper::showContent($obj['name']) }}</a>
                                                </h3>
                                                <div class="weather-grid"
                                                     data-grcity="{{ \App\Elibs\Helper::showContent($obj['name']) }}"></div>
                                                <div class="clearfix"></div>
                                                {{-- <p>{{ \App\Elibs\Helper::showContent(@$obj['mo_ta_ngan']) }}</p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- gallery-item end-->
                            @endforeach
                        </div>
                    </div>
                    <!-- portfolio end -->
                    {{--<a href="listing.html" class="btn-more color-bg">Khám phá tất cả các
                        thành phố<i class="fas fa-caret-right"></i>
                    </a>--}}
                </section>
			@endif
			
			@include('FE::FeHome.views.include.parallax-video')

    {{-- TIN TỨC NHÁ --}}
                <section class=" middle-padding">
                    <div class="container">
                        <div class="section-title">
                            {{-- <div class="section-title-separator"><span></span></div> --}}
                            <h2>CẨM NANG DU LỊCH | TIN TỨC</h2> 
                            <span class="section-separator"></span>                                    
                            <!-- <p>Browse the latest articles from our blog.</p> -->
                        </div>
                        <div class="row home-posts">
                            @if(isset($tintucvtt))
                            <div class="col-6 col-lg-4">
                                <h5 class="chinh-sua-cai-tin-tuc">Tin Tức Vietrantour</h5>
                                <span class="section-separator"></span>                                    
                                <div class="slider-carousel-wrap fl-wrap" >
                                    <div class="ls-slick-tin-tuc fl-wrap" id="autoplay" data-slick='{"slidesToShow": 1, "slidesToScroll": 1,"autoplay": true,"autoplaySpeed": 2000}'>
                                        @foreach($tintucvtt as $obj)
                                            @php($cate = \App\Http\Models\BaseModel::NOCATE)
                                            @if(isset($obj['categories']))
                                                @foreach($obj['categories'] as $val)
                                                    @if($val['type']==\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                                                        @php($cate = $val['alias']) @break
                                                    @endif
                                                @endforeach
                                            @endif
                                            <div class="px-xxl-3">
                                                <article class="card-post">
                                                    <div class="card-post-img fl-wrap">
                                                        <a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}">
                                                            <img  src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}"   alt="{{ value_show($obj['name']) }}"></a>
                                                    </div>
                                                    <div class="card-post-content fl-wrap">
                                                        <h3><a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }}</a></h3>
                                                        <p>{{ value_show($obj['brief']) }} </p>
                                                    </div>
                                                </article>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- <p>Đáp slide vào đây</p> -->
                            </div>
                            @endif
                            @if(isset($camnangdulich))
                            <div class="col-6 col-lg-4">
                                <h5 class="chinh-sua-cai-tin-tuc">Cẩm Nang Du Lịch</h5>
                                <span class="section-separator"></span>                                     
                                <div class="slider-carousel-wrap fl-wrap" >
                                    <div class="ls-slick-tin-tuc fl-wrap" id="autoplay" data-slick='{"slidesToShow": 1, "slidesToScroll": 1,"autoplay": true,"autoplaySpeed": 2000}'>
                                        @foreach($camnangdulich as $obj)

                                            @php($cate = \App\Http\Models\BaseModel::NOCATE)
                                            @if(isset($obj['categories']))
                                                @foreach($obj['categories'] as $val)
                                                    @if($val['type']==\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                                                        @php($cate = $val['alias']) @break
                                                    @endif
                                                @endforeach
                                            @endif

                                            <div class="px-xxl-3">
                                                <article class="card-post">
                                                    <div class="card-post-img fl-wrap">
                                                        <a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}"><img  src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}"   alt="{{ value_show($obj['name']) }}"></a>
                                                    </div>
                                                    <div class="card-post-content fl-wrap">
                                                        <h3><a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }}</a></h3>
                                                        <p>{{ value_show($obj['brief']) }} </p>
                                                    </div>
                                                </article>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- <p>Đáp slide vào đây</p> -->
                            </div>
                            @endif
                            @if(isset($lsNewsFeed))
                                <div class="col-12 col-lg-4">
                                    <h5 class="chinh-sua-cai-tin-tuc" style="font-style:italic;text-decoration:underline">Tin tức liên quan</h5>
                                    <span class="section-separator fix-tin-tuc-lien-quan"></span>
                                    @php($lsNewsFeedSideBar = array_slice($lsNewsFeed, 0, 3))

                                    <!--box-image-widget-->
                                    @foreach($lsNewsFeedSideBar as $obj)

                                        @if($obj['sort'] != null)
                                    <div class="box-image-widget">
                                        <div class="box-image-widget-media"><img src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}" alt="{{ value_show($obj['name']) }} ">
                                            <a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}" class="color-bg">Xem chi tiết</a>
                                        </div>
                                        <div class="box-image-widget-details">
                                            <h3 class="sp-line-3"><a href="{{ route('FeContent.NewsDetail', ['category' => $cate, 'alias' => $obj['alias']]) }}">{{ value_show($obj['name']) }} </a></h3>
                                            <p>{{ value_show($obj['brief']) }} </p>

                                        </div>
                                    </div>
                                        @endif
                                @endforeach
                                    <!--box-image-widget end -->
                                </div>
                        </div>
                        {{--<a href="blog.html" class="btn-more color-bg">Xem Thêm Nhiều<i
                            class="fas fa-caret-right"></i></a>--}}
                    </div>
                    <div class="section-decor"></div>
                </section>


            @endif

        </div>
        <!-- content end-->
    </div>
    <!-- The Modal -->
    <div id="myModalcontact" class="modal-ct">
        <!-- Nội dung form đăng nhập -->
        <form action="#" id="contact" class="modal-content-sm">
            <a href="javascript:history.back()"> <span class="close">&times;</span></a>
            <h3>Thông tin liên lạc</h3>
            <fieldset>
                <input placeholder="Nhập địa chỉ email" type="email" tabindex="1" required autofocus>
            </fieldset>
            <fieldset>
                <button name="submit" type="submit" id="contact-submit" data-submit="...Sending">Gửi</button>
            </fieldset>
        </form>
    </div>
@stop
@section('JS_REGION')
    <script>
        $(document).ready(function () {
            /*var counter_tour = $('.counter_tour');
            $.each(counter_tour, function (key, value) {
                let time = $(this).data('time');
                let id = $(this).data('id');
                updateTimerSlide(time, id)
            });
            var sbp = $('.swiper-button-prev'),
                sbn = $('.swiper-button-next');
            sbp.on("click", function () {
                $.each(counter_tour, function (key, value) {
                    let time = $(this).data('time');
                    let id = $(this).data('id');
                    console.log(id)
                    updateTimer(time, id)
                });
            });
            sbn.on("click", function () {
                $.each(counter_tour, function (key, value) {
                    let time = $(this).data('time');
                    let id = $(this).data('id');
                    console.log(id)
                    updateTimer(time, id)
                });
            });*/
            $('.ls-combo').slick({
                lazyLoad: 'ondemand',
                speed: 300,
                slidesToShow: 6,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2,
                        }
                    }
                ]
            });
            $('.ls-slick-tin-tuc').slick({
                lazyLoad: 'ondemand',
                speed: 300,
                slidesToShow: 6,
                slidesToScroll: 1,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        });

    </script>
@stop
