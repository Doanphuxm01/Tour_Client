<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$HtmlHelper['Seo']['title']}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
        <meta name="_token" content='{{csrf_token()}}'>
        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!}
        <meta property="fb:app_id" content="{{@$IO_CONFIG_WEBSITE['app_id_facebook']}}" />
        <!--=============== css  ===============-->
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/reset.css') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/plugins.css') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/style.css') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/color.css') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/custom.css') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('vietrantour/css/toastr.min.css') !!}

        @yield('CSS_REGION')
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/main/jquery.min.js') !!}
        @yield('JS_REGION')
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/io/io.js') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/io/custom.js') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/app.js') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('texo.js') !!}
        {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/toastr.min.js') !!}
        <?php
            $media_domain_se = 'https://vpdt.vietrantour.com.vn/';
            if(isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'],['vietrantour.local', 'vpvietrantour.local'])){
                $media_domain_se = 'https://vpvietrantour.local/';
            }elseif (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'],['vptour.hiwidgets.com', 'tour.hiwidgets.com','vietrantours.local'])) {
                $media_domain_se = 'https://vptour.hiwidgets.com/';
            }elseif (isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'],['vptour.kayn.pro', 'tour.kayn.pro'])) {
                $media_domain_se = 'https://vptour.kayn.pro/';
            }
        ?>
        <script>
            var BASE_URL = "{{ url('/') }}/";
            var BASE_URL_ADMIN = "{{ $media_domain_se }}";
        </script>
        <!--=============== favicons ===============-->
        <link rel="shortcut icon" type="image/png" sizes="16x16" href="{{asset('/favicon_io/favicon.png')}}" id="favicon"/>
        <link rel="shortcut icon" type="image/png" sizes="32x32" href="{{asset('/favicon_io/favicon.png')}}" id="favicon"/>
        <link rel="shortcut icon" type="image/png" sizes="96x96" href="{{asset('/favicon_io/favicon.png')}}" id="favicon"/>
        </head>
<body>
<!--loader-->
<div class="loader-wrap">
    <div class="pin">
        <div class="pulse"></div>
    </div>
</div>
<!--loader end-->
<!-- Main  -->
<div id="main">
    <!-- header-->
@include('site.header')
<!--  header end -->
    <!--  wrapper  -->
@yield('CONTENT_REGION')
<!--wrapper end -->
    <!--footer -->
@include('site.footer')
<!--footer end -->
    <!--map-modal -->
    <div class="map-modal-wrap">
        <div class="map-modal-wrap-overlay"></div>
        <div class="map-modal-item">
            <div class="map-modal-container fl-wrap">
                <div class="map-modal fl-wrap">
                    <div id="singleMap" data-latitude="40.7" data-longitude="-73.1"></div>
                </div>
                <h3><i class="fal fa-location-arrow"></i><a href="#">Hotel Title</a></h3>
                <input id="pac-input" class="controls fl-wrap controls-mapwn" type="text" placeholder="What Nearby ?   Bar , Gym , Restaurant ">
                <div class="map-modal-close"><i class="fal fa-times"></i></div>
            </div>
        </div>
    </div>
    <!--map-modal end -->
    <!--register form -->
@include('site.login')
<!--register form end -->
  <div class="color-switch">   
        <div class="social">
            <div class="socials facebook">
                <a href="{{ @$IO_CONFIG_WEBSITE['facebook'] }}" target="_blank"><i class="fab fa-facebook-f ic"></i></a>
            </div>
            <div class="socials youtube">
                <a href="{{ @$IO_CONFIG_WEBSITE['youtube'] }}" target="_blank"><i class="fab fa-youtube ic"></i></a>
            </div>
            <div class="socials skype">
                <a href="{{ @$IO_CONFIG_WEBSITE['skype'] }}" target="_blank"><i class="fab fa-skype ic"></i></a>
            </div>
            <div class="socials mail">
                <a href="{{ @$IO_CONFIG_WEBSITE['email'] }}"><i class="fas fa-envelope-open ic"></i></a>
            </div>
            <div class="socials hotline">
                <a href="{{ @$IO_CONFIG_WEBSITE['hotline'] }}"><i class="fas fa-phone ic"></i></a>
            </div>
        
    </div>
        <div class="icon"><i class="fas fa-caret-left"></i></div>
      </div>	
    <a class="to-top"><i class="fas fa-caret-up"></i></a>
</div>
<!-- Main end -->
<!--=============== scripts  ===============-->
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/jquery.min.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/plugins.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/scripts.js') !!}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMTOY-bo60AgX-ZS6pU_LFhf9fInzssaw&amp;libraries=places&amp;callback=initAutocomplete"></script>
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('vietrantour/js/map-single.js') !!}
@yield('JS_BOTTOM_REGION')

@stack('JS_BOTTOM_REGION')
<div id="fb-root"></div>
</body>
<script>
  // color switch
  

   $(".color-switch").on("click", ".icon", function(){

        $(".color-switch").toggleClass("switch-active");

    });


</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&autoLogAppEvents=1&version=v9.0&appId=1742050609307538" nonce="JkKXMiRA"></script>
</html>
<?php
if (isset($_GET['okbug'])) {
    setcookie("okbug", $_GET['okbug'], time() + 840000, '/');  /* expire in 1 hour */
    //setcookie("okbugbar", $_GET['okbug'], time() + 840000, '/');  /* expire in 1 hour */
}
?>
