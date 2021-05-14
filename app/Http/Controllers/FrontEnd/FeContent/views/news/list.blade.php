@extends($THEME_FE_EXTEND)

@section('CONTENT_REGION')
    <!--  wrapper  -->
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!--  section  -->
            @if(!$q)
            <section class="parallax-section single-par" data-scrollax-parent="true">
                <div class="bg par-elem "
                     data-bg="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}"
                     data-scrollax="properties: { translateY: '30%' }"></div>
                <div class="overlay"></div>
                <div class="container">
                    <div class="section-title center-align big-title">
                        {{-- <div class="section-title-separator"><span></span></div> --}}
                        <h2><span>{{ \App\Elibs\Helper::showContent(@$obj['name']) }}</span></h2>
                        <span class="section-separator"></span>
                    </div>
                </div>
                <div class="header-sec-link">
                    <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i
                                    class="fal fa-angle-double-down"></i></a></div>
                </div>
            </section>
            @else
                <section class="color-bg middle-padding ">
                    <div class="wave-bg wave-bg2"></div>
                    <div class="container">
                        <div class="flat-title-wrap">
                            <h2><span>{{ $obj['name'] }}</span></h2>
                            <span class="section-separator"></span>
                        </div>
                    </div>
                </section>
            @endif
            <!--  section  end-->
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                    <div class="breadcrumbs fl-wrap"><a href="{{ public_link('/') }}">Trang chủ</a>
                        @if(@@$obj['parent_id'] != 0)<a
                                href="{{ @$IO_TOURCATE[@$obj['parent_id']]['alias'] ? route('FeTour', ['alias' => $IO_TOURCATE[@$obj['parent_id']]['alias']]) : '#' }}">
                            {{ \App\Elibs\Helper::showContent(@$IO_TOURCATE[@$obj['parent_id']]) }}</a>@endif
                        <span>{{ \App\Elibs\Helper::showContent(@$obj['name']) }}</span></div>
                </div>
            </div>
            <!-- section-->
            <section id="sec1" class="middle-padding grey-blue-bg">
                <div class="container">
                    <div class="row">
                        <!--blog content -->
                        <div class="col-md-8">
                            <!--post-container -->
                            <div class="post-container fl-wrap">
                            @php(@$currentObj = @$obj)
                            @foreach($lsObj as $obj)
                                <!--article-masonry -->
                                    <div class="article-masonry">
                                        <article class="card-post">
                                            <div class="card-post-img fl-wrap">
                                                <a href="{{ route('FeContent.NewsDetail', ['category' => ($q) ? @$obj['categories'][0]['alias'] : @$currentObj['alias'], 'alias' => @$obj['alias']]) }}">
                                                    <img src="{{ \App\Http\Models\Media::getImageSrc(@$obj['avatar']) }}" alt="{{ \App\Elibs\Helper::showContent(@$obj['name']) }}">
                                                </a>
                                            </div>
                                            <div class="card-post-content fl-wrap">
                                                <h3>
                                                    <a href="{{ route('FeContent.NewsDetail', ['category' => ($q) ? @$obj['categories'][0]['alias'] : @$currentObj['alias'], 'alias' => @$obj['alias']]) }}">{{ \App\Elibs\Helper::showContent(@$obj['name']) }}</a>
                                                </h3>
                                                <p>{{ \App\Elibs\Helper::showContent(@$obj['brief']) }}</p>
                                            </div>
                                        </article>
                                    </div>
                                    <!--article-masonry end -->
                            @endforeach
                            {!! @$lsObj->render() !!}
                            <!-- pagination-->

                                <!-- pagination end-->
                            </div>
                            <!--post-container end -->
                        </div>
                        <!-- blog content end -->
                        <!--   sidebar  -->
                        <div class="col-md-4">
                            <!--box-widget-wrap -->
                            <div class="box-widget-wrap fl-wrap">
                                <!--box-widget-item -->
                                {{-- <div class="box-widget-item fl-wrap">
                                    <div class="box-widget">
                                        <div class="box-widget-content">
                                            <div class="box-widget-item-header">
                                                <h3>Tìm kiếm tin tức, sự kiện </h3>
                                            </div>
                                            <div class="search-widget">
                                                <form class="fl-wrap" method="get">
                                                    <input name="q" id="se" type="text" class="search" value="{{ request('q') }}"
                                                           placeholder="Nhập thông tin tìm kiếm..."/>
                                                    <button class="search-submit color2-bg" type="submit" id="submit_btn"><i
                                                                class="fal fa-search transition"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
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

                                                            <a href="{{ route('FeContent.NewsDetail',['category' => @$lienquan['categories'][0]['alias'], 'alias'=> @$lienquan['alias']]) }}"><b><h6>{{ @$lienquan['name'] }}</h6></b></a>
                                                            <p>{{ \App\Elibs\Helper::showContent(@$obj['brief']) }}</p>
                                                            {{-- <span class="widget-posts-date"><i class="fal fa-calendar"></i> {{ \App\Elibs\Helper::showMongoDate(@$lienquan['created_at']) }}</span> --}}
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
                        <!--   sidebar end  -->
                    </div>
                </div>
                <div class="limit-box fl-wrap"></div>
            </section>
            <!-- section end -->
        </div>
        <!-- content end-->
    </div>
    <!--wrapper end -->
@stop
