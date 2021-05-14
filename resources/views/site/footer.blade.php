<footer class="main-footer">
<div class="subscribe-wrap color-bg  fl-wrap met-lam-roi-day">
                    <div class="container">
                        <div class="sp-bg"> </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="subscribe-header">
                                    <h3>Đăng ký</h3>
                                    <p style="font-size: 14.5px"> Đăng ký nhận thông tin giảm giá, khuyến mại để nhận được ưu đãi và điểm đến mới hấp dẫn nhất. Chúng tôi cam kết chỉ gửi khi có thông tin giảm giá đảm bảo tốt nhất. </p>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="footer-widget fl-wrap">
                                    <div class="subscribe-widget fl-wrap">
                                        <div class="subcribe-form css-form">
                                            <form  id="subscribe" novalidate="true" method="get">
                                                <input class="enteremail fl-wrap" name="email" id="subscribe-email" placeholder="Đăng Ký Nhận Thông Tin Khuyến Mại" spellcheck="false" type="email">
                                                <a type="submit" id="subscribe-button" onclick="_submitFormSubscribe()" >
                                                    <i class="fas fa-rss-square"></i>
                                                    Đăng ký
                                                </a>
                                                <label for="subscribe-email" class="subscribe-message"></label>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wave-bg"></div>
                </div>
    <!--footer-inner-->
    <div class="footer-inner">
        <div class="container">

            <div class="row">
                {!! @$VAR['FOOTER'] !!}

            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="footer-widget fl-wrap">
                        <div class="footer-social">
                            <span>Liên Hệ : </span>
                            <ul>
                                <li><a href="{{ @$IO_CONFIG_WEBSITE['facebook'] }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="{{ @$IO_CONFIG_WEBSITE['youtube'] }}" target="_blank"><i class="fab fa-youtube"></i></a></li>
                                <li><a href="{{ @$IO_CONFIG_WEBSITE['skype'] }}" target="_blank"><i class="fab fa-skype"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <!--footer-widget -->
            <div class="footer-widget" id="potato">
                <div class="fb-page fr" style="width: 500px;">
                    <div class="fb-page fb_iframe_widget" data-href="https://www.facebook.com/Vietrantour.com.vn" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" fb-xfbml-state="rendered" fb-iframe-plugin-query="adapt_container_width=true&amp;app_id=&amp;container_width=500&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2FK14vn&amp;locale=vi_VN&amp;sdk=joey&amp;show_facepile=false&amp;small_header=false&amp;width=500">
                <span style="vertical-align: bottom; width: 500px; height: 130px;">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FVietrantour.com.vn&amp;tabs&amp;width=500px&amp;height=5001px&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=true&amp;appId" width="500px" height="5001px" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture">

                    </iframe>
                </span>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        <div class="customer-support-widget fl-wrap">
                            <h4>Liên hệ hỗ trợ : </h4>
                            <a href="tel:{{@$IO_CONFIG_WEBSITE['hotline']}}" class="cs-mumber">{{@$IO_CONFIG_WEBSITE['hotline']}}</a>
                            <a href="tel:{{@$IO_CONFIG_WEBSITE['hotline']}}" class="cs-mumber-button color2-bg">Gọi ngay <i class="far fa-phone-volume"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--footer-widget end -->
        </div>
    </div>
    <!--footer-inner end -->
    <div class="footer-bg">
    </div>
    <!--sub-footer-->
    <div class="sub-footer">
        <div class="container">
            <div class="copyright"> &#169; Vietrantour {{ date('Y') }} .  Đã đăng ký bản quyền.</div>
            <div class="subfooter-nav">
                <ul>
                    <li><a href="javascript:void(0);">Địa chỉ: 33 Tràng Thi, Hoàn Kiếm, Hà Nội</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--sub-footer end -->
</footer>
{{-- footer của mobilele --}}
<footer class="foot">
    <div class="footer_tab">
        <ul class="tab-foo">
            <li class="tab_item is-active">
                <a href="{{ public_link('/') }}" class="footer_home"><i class="fas fa-home"></i><br>Trang chủ
                </a>
            </li>  
            <li class="tab_item">
                <a href="{{ @$IO_CONFIG_WEBSITE['messenger'] }}" class="footer_chat"><i class="far fa-comment-dots"></i><br> Chat
                </a>
            </li>  
            <li class="tab_item">
                <a href="javascript:void(0);" class="footer_live"><i class="fas fa-video"></i><br> Live
                </a>
            </li>  
            <li class="tab_item">
                <a href="javascript:void(0);" class="footer_promotion"><i class="fas fa-bell"></i><br> Thông báo
                </a>
            </li>  
            <li class="tab_item">
                <a href="{{ route('Member') }}" class="footer_my"><i class="fas fa-user-alt"></i><br> Cá nhân
                </a>
            </li>  
        </ul>
    </div>
</footer>
@push('JS_BOTTOM_REGION')
    <script>
        function _submitFormSubscribe() {
            return _GET_FORM('#subscribe', '{{ route('FeSubscribe') }}');
        }
    </script>
@endpush
