@if(isset($lsVideos))
<section class="parallax-section" data-scrollax-parent="true">
    <div class="bg" data-bg="{{ public_link('vietrantour/images/bg/15.jpg') }}" data-scrollax="properties: { translateY: '100px' }"></div>
    <div class="overlay op7"></div>
    <!--container-->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="colomn-text fl-wrap pad-top-column-text_small">
                    <div class="colomn-text-title">
                        <h3>Xem là thích</h3>
                        <p>Đến với Chúng Tôi, Bạn sẽ được tham khảo các Video mà do chính tay Chúng Tôi xây dựng lên để chào đón khách hàng.</p>
                        <a href="javascript:void(0)" class="btn-more color2-bg float-btn">Xem thêm video<i class="fas fa-caret-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!--light-carousel-wrap-->
                <div class="light-carousel-wrap fl-wrap">
                    <!--light-carousel-->
                    <div class="light-carousel">
                        @foreach($lsVideos as $obj)
                        <!--slick-slide-item-->
                        <div class="slick-slide-item">
                            <div class="hotel-card fl-wrap title-sin_item">
                                <div class="geodir-category-img card-post">
                                    <a href="{{ @$obj['link'] }}" class="image-popup"><img src="{{ $obj['image'] }}" alt="{{ $obj['name'] }}"></a>
                                    <div class="geodir-category-opt">
                                        <h4 class="title-sin_map mb-3"><a href="#">{{ $obj['name'] }}</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--slick-slide-item end-->
                        @endforeach
                    </div>
                    <!--light-carousel end-->
                    <div class="fc-cont  lc-prev"><i class="fal fa-angle-left"></i></div>
                    <div class="fc-cont  lc-next"><i class="fal fa-angle-right"></i></div>
                </div>
                <!--light-carousel-wrap end-->
            </div>
        </div>
    </div>
</section>
@endif