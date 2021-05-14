<fieldset class="fl-wrap book_mdf">
    <div class="list-single-main-item-title fl-wrap">
        <h3>Thông tin thành viên đi</h3>
    </div>
    <div class="row" v-for="(obj, _i) in cart.details" :key="_i">
        <div class="listing-item-container init-grid-items fl-wrap">
            <div class="listing-item has_two_column has_one_column">
            <article class="geodir-category-listing fl-wrap">
                <div class="geodir-category-img  ">
                    <a :href="obj['link']"><img :src="BASE_URL_ADMIN+'/data/'+obj['avatar']['relative_link']" :alt="obj['name']"></a>
                    <div class="geodir-category-opt">
                        <div class="listing-rating card-popup-rainingvis"
                             data-starrating2="{{ @$obj['score'] }}"></div>
                        @if(isset($obj['score']))
                            <div class="rate-class-name">
                                @if(isset($obj['ratings']))
                                    <div class="score">
                                        <strong>Very Good</strong>{{ $obj['ratings'] }} Đánh giá
                                    </div>
                                @endif
                                <span>{{ $obj['score'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="geodir-category-content fl-wrap title-sin_item">
                    <div class="geodir-category-content-title fl-wrap">
                        <div class="geodir-category-content-title-item">
                            <h3 class="title-sin_map"><a :href="obj['link']">@{{ obj['name'] }}</a></h3>
                        </div>
                    </div>
                    <ul class="facilities-list fl-wrap">
                        <li class="thong-tin"><i class="fal fa-calendar-alt"></i> &nbsp;@{{ obj['ngay_khoi_hanh'] }}</li>
                    </ul>
                    <div class="geodir-category-footer p-0 fl-wrap">
                        <div class="cart-details_text">
                            <ul class="cart_list w-100">
                                <li>Số lượng chỗ<span>@{{ obj['so_luong_khach_toi_da'] }} </span></li>
                                <li>Người lớn<span><strong>@{{ formatMoney(obj['gia_nguoi_lon']) }}</strong></span></li>
                                <li>Trẻ em<span><strong>@{{ formatMoney(obj['gia_tre_em']) }}</strong></span></li>
                                <li>Trẻ nhỏ<span><strong>@{{ formatMoney(obj['gia_tre_nho']) }}</strong></span></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </article>
        </div>
        </div>
        <div class="col-sm-4">
            <label>Người lớn <small class="text-danger">*</small> <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" placeholder="Nhập thông tin" v-model="obj.info.nguoi_lon.total"/>
        </div>
        <div class="col-sm-4">
            <label>Trẻ em <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" placeholder="Nhập thông tin" v-model="obj.info.tre_em.total"/>
        </div>
        <div class="col-sm-4">
            <label>Trẻ nhỏ <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" placeholder="Nhập thông tin" v-model="obj.info.tre_nho.total"/>
        </div>
    </div>

    <span class="fw-separator"></span>
    <a  href="#"  class="previous-form action-button back-form color-bg"><i class="fal fa-angle-left"></i> Quay lại</a>
    <a  href="javascript:void(0);" @click="_check_member('hinh_thuc_thanh_toan', 'tab_list_member')" ref="hinh_thuc_thanh_toan" class="next-form action-button btn-more no-shdow-btn color-bg">Thông tin khách du lịch <i class="fal fa-angle-right"></i></a>
</fieldset>