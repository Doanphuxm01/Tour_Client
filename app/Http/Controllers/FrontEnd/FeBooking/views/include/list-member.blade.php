<fieldset class="fl-wrap book_mdf">
    <div class="list-single-main-item-title fl-wrap">
        <h2>Thông tin số lượng khách</h3>
        <span class="section-separator"></span>
        <p>Vui lòng nhập số lượng khách du lịch vào phần ô nhập phía dưới.</p>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <label>Người lớn <small class="text-danger">*</small> <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" @change="newMembers(dytNguoiLon)" placeholder="Nhập thông tin" v-model="dytNguoiLon.total"/>
        </div>
        <div class="col-sm-4">
            <label>Trẻ em <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" @change="newMembers(dytTreEm)" placeholder="Nhập thông tin" v-model="dytTreEm.total"/>
        </div>
        <div class="col-sm-4">
            <label>Trẻ nhỏ <i class="fal fa-globe-asia"></i></label>
            <input type="text" class="input-type-number" @change="newMembers(dytTreNho)" placeholder="Nhập thông tin" v-model="dytTreNho.total"/>
        </div>
    </div>
    {{-- <div class="row"> --}}
        <!-- người lớn-->
        {{-- <div class="accordion mar-top" v-for="(obj, index) in dytNguoiLon.list" :key="index">
            <a class="toggle act-accordion" href="#">Người lớn @{{ index+1 }}   <span></span></a>
            <div class="accordion-inner visible">
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Họ tên <small class="text-danger">*</small> <i class="fal fa-globe-asia"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.name"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Giới tính <i class="fal fa-globe-asia"></i></label>
                        <nice-select :gender="GENDER" :value="obj.gender" v-model="obj.gender"></nice-select>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Di động <small class="text-danger">*</small> <i class="far fa-phone"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.phone"/> --}}
                            {{-- <div class="date-parents">
                            <label>Số di động <i class="far fa-phone"></i></label>
                            <date-picker class="edit-input"
                                    v-model="obj.phone"
                                    format="DD/MM/YYYY"
                                    placeholder="Nhập thông tin"
                            ></date-picker>
                        </div> --}}
                    {{-- </div>
                    <div class="col-sm-6">
                        <label>Email <small class="text-danger">*</small> <i class="far fa-envelope"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.email"/>
                    </div>
                </div> --}}
                {{-- phần này để chọn nhận email --}}
                {{-- <div class="col-sm-12">
                    <label>Quý khách có mong muốn nhận được email nhắc lịch, đưa giá tour khuyến mại hoặc cập thật booking đặt chỗ của quý khách<small class="text-danger">*</small></label>
                    <nice-select :gender="SELECT_EMAIL" :value="obj.select_email" v-model="obj.select_email"></nice-select>
                </div>
            </div>
        </div> --}}
        <!-- người lớn end -->
        <!-- trẻ em-->
        {{-- <div class="accordion mar-top" v-if="dytTreEm.total" v-for="(obj, index) in dytTreEm.list" :key="index">
            <a class="toggle act-accordion" href="#">Trẻ em @{{ index+1 }}  <span></span></a>
            <div class="accordion-inner visible">
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Họ tên <small class="text-danger">*</small> <i class="fal fa-globe-asia"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.name"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Giới tính <i class="fal fa-globe-asia"></i></label>
                        <nice-select :gender="GENDER" :value="obj.gender" v-model="obj.gender"></nice-select>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Di động <small class="text-danger">*</small> <i class="far fa-phone"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.phone"/> --}}
                            {{-- <div class="date-parents">
                            <label>Số di động <i class="far fa-phone"></i></label>
                            <date-picker class="edit-input"
                                    v-model="obj.phone"
                                    format="DD/MM/YYYY"
                                    placeholder="Nhập thông tin"
                            ></date-picker>
                        </div> --}}
                    {{-- </div>
                    <div class="col-sm-6">
                        <label>Email <small class="text-danger">*</small> <i class="far fa-envelope"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.email"/>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- trẻ em end -->
        <!-- trẻ nhỏ-->
        {{-- <div class="accordion mar-top" v-if="dytTreNho.total" v-for="(obj, index) in dytTreNho.list" :key="index">
            <a class="toggle act-accordion" href="#">Trẻ nhỏ @{{ index+1 }}   <span></span></a>
            <div class="accordion-inner visible">
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Họ tên <small class="text-danger">*</small> <i class="fal fa-globe-asia"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.name"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Giới tính <i class="fal fa-globe-asia"></i></label>
                        <nice-select :gender="GENDER" :value="obj.gender" v-model="obj.gender"></nice-select>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-sm-6">
                        <label>Di động <small class="text-danger">*</small> <i class="far fa-phone"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.phone"/> --}}
                            {{-- <div class="date-parents">
                            <label>Số di động <i class="far fa-phone"></i></label>
                            <date-picker class="edit-input"
                                    v-model="obj.phone"
                                    format="DD/MM/YYYY"
                                    placeholder="Nhập thông tin"
                            ></date-picker>
                        </div> --}}
                    {{-- </div>
                    <div class="col-sm-6">
                        <label>Email <small class="text-danger">*</small> <i class="far fa-envelope"></i></label>
                        <input class="edit-input" type="text" placeholder="Nhập thông tin" v-model="obj.email"/>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- trẻ nhỏ end -->
    {{-- </div> --}}

    <span class="fw-separator"></span>
    <a  href="javascript:void(0);" @click="_check_member('hinh_thuc_thanh_toan', 'tab_list_member')" ref="hinh_thuc_thanh_toan" class="next-form action-button btn-more no-shdow-btn color-bg">Thông tin khách du lịch <i class="fal fa-angle-right"></i></a>
</fieldset>