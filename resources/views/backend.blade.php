@extends($THEME_FE_EXTEND)
@section('JS_REGION')

@stop


@section('CONTENT_REGION')
    <div id="wrapper">
        <!-- content-->
        <div class="content">
            <!-- section-->
            <section class="flat-header color-bg adm-header">
                <div class="wave-bg wave-bg2"></div>
                <div class="container">
                    <div class="dasboard-wrap fl-wrap">
                        <div class="dasboard-breadcrumbs breadcrumbs"><a href="{{ public_link('') }}">Trang chủ</a><a href="#">Quản trị</a><span>Thông tin cá nhân</span></div>
                        <!--dasboard-sidebar-->
                    @include('admin.components.box-info-sidebar')
                    <!--dasboard-sidebar end-->
                        <!-- dasboard-menu-->
                    @include('admin.components.box-menu')
                    <!--dasboard-menu end-->
                    </div>
                </div>
            </section>
            <!-- section end-->
            <!-- section-->
            <section class="middle-padding">
                <div class="container">
                    <!--dasboard-wrap-->
                    @yield('CONTENT_ADMIN_REGION')
                    <!-- dasboard-wrap end-->
                </div>
            </section>
            <!-- section end-->
            <div class="limit-box fl-wrap"></div>
        </div>
        <!-- content end-->
    </div>
@stop
@push('JS_BOTTOM_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/plugins/visualization/echarts/echarts.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/demo_charts/echarts/light/bars/columns_timeline.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/demo_charts/echarts/light/pies/pie_rose_labels.js') !!}
@endpush