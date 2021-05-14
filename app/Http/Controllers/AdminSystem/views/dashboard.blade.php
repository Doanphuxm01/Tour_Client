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
                        <label>Họ và tên <i class="far fa-user"></i></label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[name]" value="{{ @$MEMBER['name'] }}"/>
                        <label>Số điện thoại<i class="far fa-phone"></i>  </label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[phone]" value="{{ @$MEMBER['phone'] }}"/>
                        <label>Email<i class="far fa-envelope"></i>  </label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[email]" value="{{ @$MEMBER['email'] }}"/>
                        <label>Địa chỉ <i class="fas fa-map-marker"></i>  </label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[addr]" value="{{ @$MEMBER['addr'] }}"/>
                        <label>Ngày sinh<i class="far fa-phone"></i>  </label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[birthday]" value="{{ @$MEMBER['birthday'] }}"/>
                        <label>CMND/CCCD<i class="far fa-phone"></i>  </label>
                        <input type="text" placeholder="Nhập thông tin" name="obj[can_cuoc_cong_dan]" value="{{ @$MEMBER['can_cuoc_cong_dan'] }}"/>
                        <label>Giới tính<i class="fas fa-venus-mars"></i>  </label>
                        <select class="form-control" name="obj[gender]" value="{{ @$MEMBER['gender'] }}" style="padding-left: 43px;background-color:#F7F9FB;">
                            @foreach($GENDER as $gender)
                                <option value="{{ $gender['id'] }}" @if ($gender['id']== @$MEMBER['gender'])
                                    selected
                                @endif>{{ $gender['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <a type="button" href="javascript:void(0)" onclick="return _submitForm()"
                    class="btn-more color2-bg float-btn"><i class="fal fa-save"></i> Cập nhật thông tin</a>
            </form>
            <!-- profile-edit-container end-->
        </div>
        <!-- dashboard-list-box end-->
    </div>
    <script>
    function _submitForm() {
        return _POST_FORM('#mainFormInput', '{!! admin_link('/info/_save') !!}')
        if (haveChangeData) {
            haveChangeData = false;
            return _POST_FORM('#mainFormInput', '{!! admin_link('/info/_save') !!}')
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
@stop
