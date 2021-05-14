<fieldset class="fl-wrap book_mdf">
    <div class="list-single-main-item-title fl-wrap">
        <h3>Thông tin thanh toán</h3>
        <span class="section-separator"></span>
        <p>Đặt giữ chỗ của quý khách sắp hoàn thành. Mời chọn các cách thanh toán sau:</p>
    </div>
    <div class="row">
        <!--col -->
        <div class="col-md-12" v-for="(obj, index) in PAY" :key="index">
            <div class="add-list-media-header" style="margin-bottom:20px">
                <label class="radio inline">
                    <input type="radio" :value="obj['id']" v-model="PAY_CHECKED">
                    <span>@{{ obj['text'] }}</span>
                </label>
                <span v-if="PAY_CHECKED == index">@{{ obj['text-action'] }}
                    <div v-if="PAY_CHECKED == 'PAY_CHUYENKHOAN'">Quý khách có thể chuyển khoản để đảm bảo được giữ chỗ ngay lập tức. <br>Tên tài khoản: Công ty TNHH Vietrantour <br>Số tài khoản: 0691001006868 <br>Ngân hàng: TMCP Ngoại Thương Việt Nam (Vietcombank)</div>
                    <div v-if="PAY_CHECKED == 'PAY_THETINDUNG_NOIDIA'">Quý khách có thể sử dụng thẻ ATM nội địa để thực hiện thanh toán.  <br>Vui lòng kích hoạt chức năng thanh toán trực tuyến trước khi thực hiện giao dịch.</div>
                    <div v-if="PAY_CHECKED == 'PAY_THETINDUNG_GHINOQUOCTE'">Quý khách có thế sử dụng Thẻ tín dụng hoặc Thẻ ghi nợ quốc tế được phát hành bởi các Ngân hàng, Công ty tài chính và các tổ chức thẻ quốc tế Visa, Master Card, JCB để thanh toán. <br>Giao dịch thanh toán của Quý Khách được xử lý an toàn qua cổng thanh toán quốc tế mà Công ty Vietrantour đã ký kết.</div>
                    {{-- <div v-if="PAY_CHECKED == 'PAY_ATM_INTERNET_BANKING'"><span>QR Pay <br> (Quét mã)</span><span class="qr-con-khi-gio">VN Pay <br> (Quét mã)</span><span class="qr-con-khi-gio">Zalo Pay <br> (Quét mã)</span></div> --}}
                </span>
            </div>
        </div>
        <!--col end-->
    </div>
    <span class="fw-separator"></span>
    <a  href="javascript:void(0);"  class="previous-form  back-form action-button color-bg"><i class="fal fa-angle-left"></i> Quay lại</a>
    <a  href="javascript:void(0);" @click="_save('confirm')" ref="confirm" class="next-form  action-button btn-more color2-bg no-shdow-btn">Xác nhận chỗ<i class="fal fa-angle-right"></i></a>
</fieldset>
