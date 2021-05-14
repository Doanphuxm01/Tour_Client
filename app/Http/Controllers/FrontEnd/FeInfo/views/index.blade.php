@extends($THEME_EXTEND)

@section('CONTENT_ADMIN_REGION')
    <div class="dasboard-wrap fl-wrap">
        <!-- dashboard-content-->
        <div class="dashboard-content fl-wrap">
            <div class="box-widget-item-header">
                <h3> Thông tin cá nhân</h3>
            </div>
            <!-- profile-edit-container-->
            <form method="post" id="mainFormInput">
                @csrf
            <div class="profile-edit-container">
                <div class="custom-form">
                    <label>Họ tên <i class="far fa-user"></i></label>
                    <input type="text" placeholder="Nhập thông tin" value="{{ @$MEMBER['name'] }}"/>
                    <label>Email<i class="far fa-envelope"></i>  </label>
                    <input type="text" placeholder="Nhập thông tin" value="{{ @$MEMBER['email'] }}"/>
                    <label>Di động<i class="far fa-phone"></i>  </label>
                    <input type="text" placeholder="Nhập thông tin" value="{{ @$MEMBER['phone'] }}"/>
                    <label>Địa chỉ <i class="fas fa-map-marker"></i>  </label>
                    <input type="text" placeholder="Nhập thông tin" value="{{ @$MEMBER['addr'] }}"/>
                    <a href="javascript:void(0)" onclick="return _submitForm()"
                    class="btn-more color2-bg float-btn"><i class="fal fa-save"></i> Cập nhật thông tin</a>
                </div>
            </div>
            </form>
            <!-- profile-edit-container end-->
        </div>
        <!-- dashboard-list-box end-->
    </div>
    <script>
    function _submitForm() {
        return _POST_FORM('#mainFormInput', '{!! admin_link('/admin/_save') !!}')
    }
</script>
@stop
