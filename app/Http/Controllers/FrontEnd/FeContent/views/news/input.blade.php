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
            <section class="color-bg middle-padding ">
                <div class="wave-bg wave-bg2"></div>
                <div class="container">
                    <div class="flat-title-wrap">
                        <h2><span>{{ $obj['name'] }}</span></h2>
                        <span class="section-separator"></span>
                        <h4>{{ \App\Elibs\Helper::showContent($obj['brief']) }}</h4>
                    </div>
                </div>
            </section>
            <!--  section  end-->
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('') }}">Trang chủ</a>
                        @if(@$currentCate['alias'])
                        <a href="{{ route('FeContent.NewsCate', ['category' => $currentCate['alias']]) }}">{{ \App\Elibs\Helper::showContent($currentCate['name']) }}</a>
                        @endif
                        <span>{{ $obj['name'] }}</span></div>
                </div>
            </div>
            <!-- section-->
            <section  id="sec1" class="middle-padding grey-blue-bg">
                <div class="container">
                    <div class="row">
                        <!--blog content -->
                        <div class="col-md-8">
                            <!--post-container -->
                            <div class="post-container fl-wrap">
                                <!-- article> -->
                                <article class="post-article">
                                    <div class="list-single-main-media fl-wrap">
                                        <div class="single-slider-wrapper fl-wrap">
                                            <div class="single-slider fl-wrap"  >
                                                {{-- <div class="slick-slide-item"><img src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}" alt="{{ $obj['name'] }}"></div> --}}
                                            </div>
                                            {{--<div class="swiper-button-prev sw-btn"><i class="fa fa-long-arrow-left"></i></div>
                                            <div class="swiper-button-next sw-btn"><i class="fa fa-long-arrow-right"></i></div>--}}
                                        </div>
                                    </div>
                                    <div class="list-single-main-item ok-content-unset-color fl-wrap">
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>{{ $obj['name'] }}</h3>
                                        </div>
                                       {!! $obj['content'] !!}
                                        @if(@$obj['categories'])
                                        <span class="fw-separator"></span>

                                        <div class="post-opt kgroup-tag">
                                            <b>Từ khóa: </b>
                                            <ul>
                                                <li>

                                                        @foreach($obj['categories'] as $val)
                                                            @if($val['type']!=\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                                                                <a class="ktags" href="{{ route('FeContent.NewsCate', ['alias' => $val['alias']]) }}">#{{ $val['name'] }}</a>
                                                            @endif
                                                        @endforeach

                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                        {{-- <span class="fw-separator"></span>
                                        <div class="list-single-main-item-title fl-wrap">
                                            <h3>Tags</h3>
                                        </div>
                                        @if(@$obj['categories'])
                                        <div class="list-single-tags tags-stylwrap blog-tags">
                                            @foreach($obj['categories'] as $val)
                                                @if($val['type']!=\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                                                    <a href="{{ route('FeContent.NewsCate', ['alias' => $val['alias']]) }}">{{ $val['name'] }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                        @endif --}}
                                        <span class="fw-separator"></span>
                                        {{--<div class="post-nav fl-wrap">
                                            <a href="#" class="post-link prev-post-link"><i class="fal fa-angle-left"></i>Prev <span class="clearfix">The Sign of Experience</span></a>
                                            <a href="#" class="post-link next-post-link"><i class="fal fa-angle-right"></i>Next<span class="clearfix">Dedicated to Results</span></a>
                                        </div>--}}
                                    </div>
                                </article>
                                <!-- article end -->
                            </div>
                            <!--post-container end -->
                        </div>
                        <!-- blog content end -->

                        <!--   sidebar  -->
                        <div class="col-md-4">
                            <!--box-widget-wrap -->
                            <div class="box-widget-wrap fl-wrap">
                                <!--box-widget-item -->
                                <div class="box-widget-item fl-wrap">
                                    <div class="box-widget">
                                        <div class="box-widget-content">
                                            <div class="box-widget-item-header">
                                                <h3>Tìm kiếm tin tức, sự kiện </h3>
                                            </div>
                                            <div class="search-widget">
                                                <form class="fl-wrap" @if(@$currentCate['alias'] || @$nocate)
                                                action="{{ route('FeContent.NewsCate', ['category' => $currentCate['alias']??$nocate]) }}"
                                                @endif >
                                                    <input name="q" id="se" type="text" class="search" value="{{ request('q') }}"
                                                           placeholder="Nhập thông tin tìm kiếm..."/>
                                                    <button class="search-submit color2-bg" type="submit" id="submit_btn"><i
                                                                class="fal fa-search transition"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--box-widget-item end -->
                                <!--box-widget-item -->
                                <div class="box-widget-item fl-wrap">
                                    <div class="box-widget widget-posts">
                                        <div class="box-widget-content">
                                            <div class="box-widget-item-header">
                                                <h3>Bài viết mới nhất</h3>
                                            </div>
                                            <!--box-image-widget-->
                                            @if(!empty($related))
                                                @foreach($related as $lienquan)
                                                    <div class="box-image-widget">
                                                        <div class="box-image-widget-media"><img src="{{ \App\Http\Models\Media::getImageSrc(@$lienquan['avatar']) }}" alt="">
                                                        </div>
                                                        <div class="box-image-widget-details">

                                                            <a href="{{ route('FeContent.NewsDetail',['category' => @$lienquan['categories'][0]['alias']?:\App\Http\Models\Cate::NOCATE, 'alias'=> @$lienquan['alias']]) }}"><b><h6>{{ @$lienquan['name'] }}</h6></b></a>
                                                            <span class="widget-posts-date"><i class="fal fa-calendar"></i> {{ \App\Elibs\Helper::showMongoDate(@$lienquan['created_at']) }}</span>
                                                        </div>
                                                    </div>
                                            @endforeach
                                        @endif
                                        <!--box-image-widget end -->
                                        </div>
                                    </div>
                                </div>
                                <!--box-widget-item end -->
                            </div>
                            <!--box-widget-wrap end -->
                        </div>
                    </div>
                </div>
                <div class="limit-box fl-wrap"></div>
            </section>
            <!-- section end -->
        </div>
        <!-- content end-->
    </div>
@stop