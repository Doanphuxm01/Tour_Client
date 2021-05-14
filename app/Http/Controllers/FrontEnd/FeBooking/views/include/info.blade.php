<fieldset class="fl-wrap book_mdf">
    <div class="list-single-main-item-title fl-wrap">
        <h3>Thông tin số lượng khách du lịch</h3>
        <span class="section-separator"></span>
        <p>Quý khách điền thông tin chi tiết để đảm bảo được giữ chỗ, giữ giá tốt nhất.</p>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <label>Họ tên <span class="text-danger">*</span><i class="far fa-user"></i></label>
            <input type="text" placeholder="Nhập thông tin" v-model="member.name"/>
        </div>
        <div class="col-sm-6">
            <label>Di động <span class="text-danger">*</span><i class="far fa-phone"></i>  </label>
            <input type="text" placeholder="Nhập thông tin" v-model="member.phone"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label>Địa chỉ email <i class="far fa-envelope"></i>  </label>
            <input type="text" placeholder="Nhập thông tin" v-model="member.email"/>
        </div>
        
        <div class="col-sm-12">
            <label>Quý khách có mong muốn nhận được email nhắc lịch, đưa giá tour khuyến mại hoặc cập thật booking đặt chỗ của quý khách<small class="text-danger">*</small></label>
            <nice-select :gender="SELECT_EMAIL" :value="member.send_email" v-model="member.send_email"></nice-select>
        </div>

        <div class="col-sm-12">
            <label>Ghi chú</label>
            <textarea class="gic" type="text" v-model="member.note" placeholder="Nhập ghi chú" ></textarea>
        </div>
    </div>
    <div class="filter-tags" style="margin-top: 20px;">
        <input style="margin-top: 2px" id="check-a" v-model="member.check" type="checkbox" name="check">
        @if(isset($obj['categories']))
            @foreach($obj['categories'] as $val)
                @if($val['type']==\App\Http\Models\Cate::$cateTypeRegister['cate']['key'])
                    @php($cate = $val['alias']) @break
                @endif
            @endforeach
        @endif
        <label for="check-a">Để tiếp tục, quý khách đồng ý với &nbsp;<a href="{{ route('FeContent.NewsDetail', ['category' => \App\Http\Models\Post::NOCATE, 'alias' => 'quy-dinh-va-dieu-khoan']) }}" target="_blank">Điều khoản và Điều kiện.</a>.</label>
    </div>
    <br>
    <br>
    <a  href="#"  class="previous-form action-button back-form color-bg"><i class="fal fa-angle-left"></i> Quay lại</a>
    <a href="javascript:void(0);" @click="_check_member('dia_chi_thanh_toan', 'tab_info')" ref="dia_chi_thanh_toan"
       class="next-form action-button btn-more no-shdow-btn color-bg">Thông tin thanh toán <i class="fal fa-angle-right"></i></a>
    <div class="mt-4">
        @if(!isset($member['_id']))
            <br><br>
            <span class="fw-separator"></span>
            <div class="log-massage">Khách hàng hiện tại? <a href="#" class="modal-open">Ấn vào đây để đăng nhập</a></div>
            <div class="log-separator fl-wrap"><span>hoặc</span></div>
            <div class="soc-log fl-wrap">
                <p>Để đăng nhập nhanh hơn hoặc đăng ký, hãy sử dụng tài khoản xã hội của quý khách.</p>
                <a href="{{ route('AuthGate', ['action' => 'redirect', 'href' => 'booking']) }}" class="facebook-log"><i class="fab fa-facebook-f"></i>Kết nối với Facebook</a>
            </div>
            <span class="fw-separator"></span>
        @endif
    </div>
</fieldset>
