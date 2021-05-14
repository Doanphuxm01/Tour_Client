
<div class="main-register-wrap modal" style="overflow-y: scroll">
    <div class="reg-overlay"></div>
    <div class="main-register-holder">
        <div class="main-register fl-wrap">
            <div class="close-reg color-bg"><i class="fal fa-times"></i></div>
            <ul class="tabs-menu">
                <li class="current"><a href="#tab-1"><i class="fal fa-sign-in-alt"></i> Đăng nhập</a></li>
                <li><a href="#tab-2"><i class="fal fa-user-plus"></i> Đăng ký</a></li>
            </ul>
            <!--tabs -->
            <div id="tabs-container">
                <div class="tab">
                    <!--tab -->
                    <div id="tab-1" class="tab-content">
                        <h3>Đăng nhập <span>Vietran<strong>Tour</strong></span></h3>
                        <div class="custom-form">
                            <form method="post" name="registerform" id="main-login-form">
                                @csrf
                                <label>Tài khoản <span>*</span> </label>
                                <input name="obj[account]" type="text"   onClick="this.select()" value="">
                                <label >Mật khẩu <span>*</span> </label>
                                <input name="obj[password]" type="password"   onClick="this.select()" value="" >
                                <button type="button" class="log-submit-btn color-bg"><span>Đăng nhập</span></button>
                                <div class="clearfix"></div>
                                {{--<div class="filter-tags">
                                    <input id="check-a" type="checkbox" name="check">
                                    <label for="check-a">Ghi nhớ</label>
                                </div>--}}
                            </form>
                            {{--<div class="lost_password">
                                <a href="#">Quên mật khẩu?</a>
                            </div>--}}
                        </div>
                    </div>
                    <!--tab end -->
                    <!--tab -->
                    <div class="tab">
                        <div id="tab-2" class="tab-content">
                            <h3>Đăng ký <span>Vietran<strong>Tour</strong></span></h3>
                            <div class="custom-form">
                                <form method="post" name="registerform" class="main-register-form" id="main-register-form">
                                    @csrf
                                    <label>Họ Tên <span>*</span> </label>
                                    <input name="obj[name]" type="text" onClick="this.select()" value="">
                                    <label>Địa chỉ email <span>*</span></label>
                                    <input name="obj[email]" type="email" onClick="this.select()" value="">
                                    <label>Tài khoản đăng nhập <span>*</span></label>
                                    <input name="obj[account]" type="email" onClick="this.select()" value="">
                                    <label>Mật khẩu đăng nhập <span>*</span></label>
                                    <input name="obj[password]" type="password" onClick="this.select()" value="" >
                                    <button type="button" class="log-submit-btn color-bg"><span>Đăng ký</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--tab end -->
                </div>
                <!--tabs end -->
                <div class="log-separator fl-wrap"><span>hoặc</span></div>
                <div class="soc-log fl-wrap">
                    <p>Để đăng nhập nhanh hơn hoặc đăng ký, hãy sử dụng tài khoản xã hội của bạn.</p>
                    <a href="{{ route('AuthGate', ['action' => 'redirect']) }}" class="facebook-log"><i class="fab fa-facebook-f"></i>Connect with Facebook</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('JS_BOTTOM_REGION')
    <script type="text/javascript">
        $('.log-submit-btn').click(function () {
            $form = $(this).parent('form');
            if('main-login-form' == $form.attr('id')) {
                _POST_FORM('#'+$form.attr('id'), '{{ route('AuthGate',  ['action' => 'login']) }}');
            }else {
                _POST_FORM('#'+$form.attr('id'), '{{ route('AuthGate', ['action' => 'register']) }}');
            }

        })

        @if(count($errors) > 0)
            toastr.options.progressBar = true;
            var err = '{!! json_encode($errors->all()) !!}';
            toastr.error(JSON.parse(err));
        @endif

    </script>
@endpush
