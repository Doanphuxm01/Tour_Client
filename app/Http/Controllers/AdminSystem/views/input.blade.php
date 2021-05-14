@extends($THEME_EXTEND)

@section('CONTENT_ADMIN_REGION')
<section class="middle-padding">
    <form method="post" id="mainFormInput">
                        <div class="container">
                            <!--dasboard-wrap-->
                            <div class="dasboard-wrap fl-wrap">
                                <!-- dashboard-content--> 
                                <div class="dashboard-content fl-wrap">
                                    <div class="box-widget-item-header">
                                        <h3> Đổi mật khẩu</h3>
                                    </div>
                                    <div class="custom-form no-icons">
                                        <div class="pass-input-wrap fl-wrap">
                                            <label>Mật khẩu cũ</label>
                                            <input type="password" class="pass-input" placeholder="Nhập thông tin" name="password" value=""/>
                                            <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                        </div>
                                        <div class="pass-input-wrap fl-wrap">
                                            <label>Mật khẩu mới</label>
                                            <input type="password" class="pass-input" placeholder="Nhập thông tin" name="new-password" value=""/>
                                            <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                        </div>
                                        <div class="pass-input-wrap fl-wrap">
                                            <label>Nhập lại mật khẩu mới</label>
                                            <input type="password" class="pass-input" placeholder="Nhập thông tin" name="re-new-password" value=""/>
                                            <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- dashboard-list-box end--> 
                            </div>
                            <!-- dasboard-wrap end-->
                        </div>
                        <a type="button" href="javascript:void(0)" onclick="return _submitFormPass()"
                    class="btn-more color2-bg float-btn"><i class="fal fa-save"></i> Cập nhật mật khẩu</a>
            </form>

<script>
    function _submitFormPass() {
        return _POST_FORM('#mainFormInput', '{!! admin_link('/info/_change') !!}')
        if (haveChangeData) {
            haveChangeData = false;
            return _POST_FORM('#mainFormInput', '{!! admin_link('/info/_change') !!}')
        } else {
            new Noty({
                theme: ' alert alert-info alert-styled-left p-0 mb-2',
                progressBar: false,
                closeWith: ['button'],
                layout: 'topRight',
                text: 'Không có dữ liệu nào được thay đổi. Bạn cần thay đổi thông tin trước khi cập nhật',
                type: 'info/information',
            }).show();
        }
    }
</script>
                    </section>
@stop