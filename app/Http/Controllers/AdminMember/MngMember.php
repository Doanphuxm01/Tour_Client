<?php

/**
 * Created by PhpStorm.
 * User: Sakura
 * Date: 5/16/2016
 * Time: 12:24 PM
 */

namespace App\Http\Controllers\AdminMember;

use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\FileHelper;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Models\BaseModel;
use App\Http\Models\Location;
use App\Http\Models\Logs;
use App\Http\Models\Media;
use App\Http\Models\Member;
use App\Http\Models\Menu;
use App\Http\Models\MetaData;
use App\Http\Models\Position;
use App\Http\Models\Department;
use App\Http\Models\Project;
use App\Http\Models\ProjectPermission;
use App\Http\Models\Role;
use App\Http\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MongoRegex;

class   MngMember extends Controller
{

    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->_list();
        }
    }

    /***
     * Danh sách thành vien
     *
     * @url: admin/member/list
     */
    public function _list()
    {
        HtmlHelper::getInstance()->setTitle('Quản lý thành viên - Member Manager');
        $tpl = [];

        #region check role
        $isAllow = Role::isAllowTo(Role::$ACTION_LIST. Role::$_MP_MEMBER);
        if (!$isAllow) {
            return eView::getInstance()->getJsonError('Bạn không có quyền xem danh sách nhân sụ');
        }
        #endregion

        $itemPerPage = (int)Request::capture()->input('row', 35);

        //select Cái này dùng cho export
        $q = Request::capture()->input('q', []);

        $lsTh = [
            ['key' => 'account', 'name' => 'TÀI KHOẢN', 'td' => ['class' => 'font-weight-bold text-teal-400', 'link' => admin_link('staff/input?account=')]],
            ['key' => 'name', 'name' => 'HỌ TÊN'],
            ['key' => 'phone', 'name' => 'SỐ ĐIỆN THOẠI'],
            ['key' => 'email', 'name' => 'EMAIL'],
            ['key' => 'department', 'ksub' => 'name', 'name' => 'PHÒNG BAN'],
            ['key' => 'position', 'ksub' => 'name', 'name' => 'CHỨC VỤ'],
            ['key' => 'work_status', 'ksub' => 'name', 'name' => 'TRẠNG THÁI CÔNG VIỆC'],
            ['key' => 'noi_sinh', 'name' => 'NƠI SINH'],
            ['key' => 'nguyen_quan', 'name' => 'NGUYÊN QUÁN'],
            ['key' => 'noi_dang_ky_ho_khau', 'name' => 'NƠI ĐĂNG KÝ HỘ KHẨU'],
            ['key' => 'cho_o_hien_nay', 'name' => 'CHỖ Ở HIỆN NAY'],
            ['key' => 'chung_minh_thu_number', 'name' => 'CHỨNG MINH THƯ'],
            ['key' => 'created_at', 'name' => 'THỜI GIAN ĐĂNG KÝ TÀI KHOẢN'],
            ['key' => 'status', 'name' => 'Trạng thái'],
        ];
        $sText = [
            ['placeholder'=>'Nhập tên tài khoản...','field'=>['key'=>'account', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-user-tie']],
            ['placeholder'=>'Nhập họ và tên...','field'=>['key'=>'name', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-profile']],
            ['placeholder'=>'Nhập địa chỉ, nơi ở hiện nay...','field'=>['key'=>'cho_o_hien_nay', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-address-book']],
            ['placeholder'=>'Nhập số điện thoại...','field'=>['key'=>'phone', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-iphone']],
            ['placeholder'=>'Nhập địa chỉ email...','field'=>['key'=>'email', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-mail5']],
            ['placeholder'=>'Nhập địa chỉ email...','field'=>['key'=>'work_status.name', 'autocomplte' => 'off'], 'icons' => ['class' => 'icon-mail5']],
        ];
        $sDate = [
            ['placeholder'=>'Thời gian tạo','field'=>['key'=>'created_at', 'class' => 'daterange-basic'], 'icons' => ['class' => 'icon-calendar3']],
            ['placeholder'=>'Thời gian kích hoạt','field'=>['key'=>'actived_at', 'class' => 'daterange-basic'], 'icons' => ['class' => 'icon-calendar3']],
            ['placeholder'=>'Thời gian cập nhật','field'=>['key'=>'updated_at', 'class' => 'daterange-basic'], 'icons' => ['class' => 'icon-calendar3']],
        ];
        $tpl['lsTh'] = $lsTh;
        $tpl['sText'] = $sText;
        $tpl['sDate'] = $sDate;
        $tpl['q'] = $q;
        $where = [];

        if (@$q['status']) {
            $where['status'] = $q['status'];
        }
        $listObj = Member::where($where);
        // Nếu search theo từ khóa
        if(@$q) {
            foreach ($q as $k => $i) {
                $i = trim($i);
                if($k != 'created_at' && $i != '') {
                    $listObj = $listObj->where(function ($q) use ($k, $i) {
                        $q->where($k, 'LIKE', '%' . $i . '%');
                    });
                }
            }
        }

        $listObj = $listObj->where('account', '!=', 'Khoa')->orderBy('_id', 'desc');
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        $tpl['lsObj'] = $listObj;
        return eView::getInstance()->setViewBackEnd(__DIR__, 'list-style', $tpl);
    }

    public function my_info()
    {
        return redirect(admin_link('/staff/input?id=' . Member::getCurentId()));
    }

    public function input()
    {
        if (!empty($_POST)) {
            return $this->_save_tab_info();
        }
        $tpl = [];

        $currentMember = Member::getCurent();
        $currentMemberId = $currentMember['_id'];
        $currentMemberAcc = $currentMember['account'];
        HtmlHelper::getInstance()->setTitle('Cập nhật thành viên - Member Manager');
        $id = Request::capture()->input('id', 0);
        $account = Request::capture()->input('account', 0);
        $preview = request('preview');
        $tpl['preview'] = $preview;
        $type_view = request('view', 'full');
        $tpl['view'] = $type_view;

        $obj = false;
        if($account) {
            $obj = Member::getMemberByAccount($account);
            if ($obj) {
                return redirect()->route('AdminMember', ['action_name' => 'input', 'id' => (string)$obj['_id']]);
            }
        }
        if ($id) {
            $obj = Member::find($id);
            if (!$obj || BaseModel::isDeleted($obj)) {
                return redirect(admin_link('/staff'));
            }
            #region check role
            if ($id === $currentMemberId) {
                $isAllow = Role::isAllowTo(Role::$ACTION_LIST_OF_ME. Role::$_MP_MEMBER);
                if (!$isAllow) {
                    return eView::getInstance()->cannnotAccess(['msg' => 'Bạn không có quyền xem thông tin nhân sự này.']);
                }
            } else {
                $isAllow = Role::isAllowTo(Role::$ACTION_LIST_OF_NOT_ME. Role::$_MP_MEMBER);
                if (!$isAllow) {
                    return eView::getInstance()->cannnotAccess(['msg' => 'Bạn không có quyền xem thông tin nhân sự này.']);
                }
            }
            #endregion
            //todo@tinhthanh _call lúc show form edit
            Location::getProvinceInput($obj, $tpl);
        }
        $tpl['isNew'] = !$obj;
        $tpl['obj'] = $obj;
        $tpl['allDepartment'] = Department::getDepartment();
        $tpl['allCity'] = Location::getAllCity();

        return eView::getInstance()->setViewBackEnd(__DIR__, 'input', $tpl);
    }

    public function _tab_files(&$tpl = [])
    {
        $id = Request::capture()->input('id', 0);
        $q = trim(Request::capture()->input('q'));

        $where ['created_by'] = $id;

        $listObj = Media::where($where);
        if ($q) {
            $listObj = $listObj->where(
                function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . trim($q) . '%')
                        ->OrWhere('src', 'LIKE', '%' . trim($q) . '%');
                }
            );
        }
        $itemPerPage = Request::capture()->input('row', 50);
        $listObj = $listObj->orderBy('_id', 'desc');
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        $tpl['listObj'] = $listObj;

        return eView::getInstance()->setViewBackEnd(__DIR__, 'input-all', $tpl);

    }

    /***
     * Danh sách thành vien
     *
     * @url: admin/member/_save
     */



    public function _save_tab_info()
    {

        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        $isNew = false;
        #region Kiểm tra tài khoản tồn tại chưa
        if (!$id) {
            $isNew = true;
        } else {
            $currentObj = Member::where('_id', $id)->first();
            if (!$currentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        }
        $allowToEditImportant = Role::isAllowTo(Role::$ACTION_EDIT_IMPORTANT . Role::$_MP_MEMBER);
        $isAllow = Role::isAllowTo(Role::$ACTION_EDIT . Role::$_MP_MEMBER);
        if (!$isAllow) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thực hiện hành động này');
        }
        $currentMember = Member::getCurent();
        #endregion


        $objToSave = [
            'status' => BaseModel::STATUS_ACTIVE,
            'removed' => BaseModel::REMOVED_NO,
            'updated_at' => Helper::getMongoDateTime(),
        ];

        #region validate

        //case code  : mã nhân viên và phải là duy nhất
        if (isset($obj['code']) && !empty($obj['code'])) {
            $existsCode = Member::where('code', $obj['code'])->first();
            if ($existsCode && $existsCode['_id'] != $id) {
                return eView::getInstance()->getJsonError('Mã nhân viên đã tồn tại');
            } else {
                $objToSave ['code'] = $obj['code'];
            }
        } else {
            return eView::getInstance()->getJsonError('Thiếu thông tin mã nhân viên');
        }

        //case account  : tài khoản nhân viên và phải là duy nhất
        if (isset($obj['account']) && !empty($obj['account'])) {
            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]{5,31}$/', $obj['account'])) {
                return eView::getInstance()->getJsonError('Tài khoản chỉ có thể là chuỗi các chữ, số và bắt đầu bằng chữ cái');
            }

            $existsAccount = Member::where('account', $obj['account'])->where(['_id' => ['$ne' => $id]])->first();
            if ($existsAccount) {
                return eView::getInstance()->getJsonError('Tài khoản này đã tồn tại ');
            } else {
                $objToSave ['account'] = $obj['account'];
            }
        } else {
            if (!$isNew) {
                unset($objToSave['account']);
            } else {
                return eView::getInstance()->getJsonError('Bạn vui lòng nhập tài khoản');
            }
        }

        //case name
        if (!isset($obj['name']) && empty($obj['name'])) {
            return eView::getInstance()->getJsonError('Thiếu thông tên nhân viên');
        }
        $objToSave['name'] = $obj['name'];

        //case email
        if (!empty(@$obj['email'])) {
            if (!Helper::isEmail($obj['email'])) {
                return eView::getInstance()->getJsonError('Email không đúng định dạng');
            }
        }
        $objToSave['email'] = $obj['email'];

        if (!empty(@$obj['phone'])) {
            if (!Helper::isPhoneNumber($obj['phone'])) {
                return eView::getInstance()->getJsonError('Số điện thoại không đúng định dạng');
            }
        }
        $objToSave['phone'] = $obj['phone'];
        $objToSave['chung_chi_atld'] = $obj['chung_chi_atld'];

        //case files
        $objToSave['files'] = Helper::processFilesToSave();

        //case gender
        if (isset($obj['gender']) && $obj['gender']) {
            $objToSave['gender'] = $obj['gender'];
        }
        //case avatar_url
        if (isset($obj['avatar_url']) && $obj['avatar_url']) {
            $objToSave['avatar_url'] = $obj['avatar_url'];
        }

        //case date_of_birth
        if (isset($obj['date_of_birth']) && !empty($obj['date_of_birth'])) {
            if (Helper::isDatetime($obj['date_of_birth'])) {
                $objToSave['date_of_birth'] = Helper::getMongoDate($obj['date_of_birth']);
            } else {
                return eView::getInstance()->getJsonError('Ngày sinh nhật format không đúng kiểu');
            }
        }
        if (empty(@$obj['date_of_birth'])) {
            $objToSave ['date_of_birth'] = @$obj['date_of_birth'];
        }
        //case nam_tot_nghiep
        if (isset($obj['nam_tot_nghiep'])) {
            $objToSave['nam_tot_nghiep'] = $obj['nam_tot_nghiep'];
        }

        //bổ sung năm tốt nghiệp thành ngày tốt nghiệp (sử dụng cả ngày để tính thâm niên
        if (isset($obj['ngay_tot_nghiep']) && $obj['ngay_tot_nghiep']) {
            $objToSave['ngay_tot_nghiep'] = Helper::getMongoDate($obj['ngay_tot_nghiep']);
        }

        if (isset($obj['avatar']) && is_array($obj['avatar'])) {
            $objToSave['avatar'] = $obj['avatar'];
        }
        //case gender
        if (isset($obj['trinh_do_chuyen_mon']) && $obj['trinh_do_chuyen_mon']) {
            $objToSave['trinh_do_chuyen_mon'] = $obj['trinh_do_chuyen_mon'];
        }
        //case gender
        if (isset($obj['nganh_hoc']) && $obj['nganh_hoc']) {
            $objToSave['nganh_hoc'] = $obj['nganh_hoc'];
        }
        //case gender
        if (isset($obj['noi_dao_tao']) && $obj['noi_dao_tao']) {
            $objToSave['noi_dao_tao'] = $obj['noi_dao_tao'];
        }

        //case noi_sinh
        if (isset($obj['noi_sinh'])) {
            $objToSave['noi_sinh'] = $obj['noi_sinh'];
        }

        //case nguyen_quan
        if (isset($obj['nguyen_quan'])) {
            $objToSave ['nguyen_quan'] = $obj['nguyen_quan'];
        }
        if (isset($obj['nguyen_quan'])) {
            $objToSave ['nguyen_quan'] = $obj['nguyen_quan'];
        }

        if (isset($obj['noi_dang_ky_ho_khau'])) {
            $objToSave ['noi_dang_ky_ho_khau'] = $obj['noi_dang_ky_ho_khau'];
        }

        if (isset($obj['cho_o_hien_nay'])) {
            $objToSave ['cho_o_hien_nay'] = $obj['cho_o_hien_nay'];
        }

        if (isset($obj['dan_toc'])) {
            $objToSave ['dan_toc'] = $obj['dan_toc'];
        }


        //case ton_giao
        if (isset($obj['ton_giao'])) {
            $objToSave ['ton_giao'] = $obj['ton_giao'];
        }

        //case chung_minh_thu_number
        if (isset($obj['chung_minh_thu_number'])) {
            $objToSave ['chung_minh_thu_number'] = $obj['chung_minh_thu_number'];
        }

        //case chung_minh_thu_ngay_cap
        if (isset($obj['chung_minh_thu_ngay_cap']) && !empty($obj['chung_minh_thu_ngay_cap'])) {
            if (Helper::isDatetime($obj['chung_minh_thu_ngay_cap'])) {
                $objToSave['chung_minh_thu_ngay_cap'] = Helper::getMongoDate($obj['chung_minh_thu_ngay_cap']);
            } else {
                return eView::getInstance()->getJsonError('Ngày cấp cmt format không đúng kiểu');
            }
        }
        if (empty(@$obj['chung_minh_thu_ngay_cap'])) {
            $objToSave ['chung_minh_thu_ngay_cap'] = @$obj['chung_minh_thu_ngay_cap'];
        }
        //case chung_minh_thu_noi_cap
        if (isset($obj['chung_minh_thu_noi_cap'])) {
            $objToSave ['chung_minh_thu_noi_cap'] = $obj['chung_minh_thu_noi_cap'];
        }


        //case tai_khoan_ngan_hang_number
        if (isset($obj['tai_khoan_ngan_hang_number'])) {
            $objToSave ['tai_khoan_ngan_hang_number'] = $obj['tai_khoan_ngan_hang_number'];
        }
        //case tai_khoan_ngan_hang_ngan_hang
        if (isset($obj['tai_khoan_ngan_hang_ngan_hang']['id']) && $obj['tai_khoan_ngan_hang_ngan_hang']['id']) {
            $temp = MetaData::where('_id', $obj['tai_khoan_ngan_hang_ngan_hang']['id'])->first();
            if ($temp) {
                $objToSave['tai_khoan_ngan_hang_ngan_hang'] = [
                    'id' => $temp['_id'],
                    'key' => $temp['key'],
                    'name' => $temp['name']
                ];
            } else {
                return eView::getInstance()->getJsonError("Không tìm thấy ngân hàng đã lựa chọn");
            }

        }
        $isAllow = Role::isAllowTo(Role::$ACTION_EDIT_IMPORTANT. Role::$ACTION_ROLE . Role::$_MP_MEMBER);
        if($isAllow) {
            if (isset($obj['department']['id']) && Helper::isMongoId($obj['department']['id'])) {
                $temp = Department::where('_id', $obj['department']['id'])->first();
                if ($temp) {
                    $temp['id'] = $temp['_id'];
                    $temp = $temp->toArray();
                    unset($temp['_id']);
                    $objToSave['department'] = $temp;
                }else {
                    return eView::getInstance()->getJsonError("Không tìm thấy phòng ban đã lựa chọn");
                }
                // có phòng ban thì ms đc chọn chức vụ
                if (isset($obj['position']['id']) && Helper::isMongoId($obj['position']['id'])) {
                    $temp = Position::where('_id', $obj['position']['id'])->first();
                    if ($temp) {
                        $temp['id'] = $temp['_id'];
                        $temp = $temp->toArray();
                        unset($temp['_id']);
                        if(isset($temp['roles'])) {
                            $objToSave['roles'] = $temp['roles'];
                            unset($temp['roles']);
                        }
                        unset($temp['department']);
                        $objToSave['position'] = $temp;

                    }else {
                        return eView::getInstance()->getJsonError("Không tìm thấy chức danh đã lựa chọn");
                    }
                }
            }else{
                if(isset($obj['department']['id'] )&& empty($obj['department']['id'])){
                    $objToSave['department']  = [];
                    $objToSave['position']  = [];
                }
            }
        }

        //case tai_khoan_ngan_hang_chi_nhanh
        if (isset($obj['tai_khoan_ngan_hang_chi_nhanh'])) {
            $objToSave ['tai_khoan_ngan_hang_chi_nhanh'] = $obj['tai_khoan_ngan_hang_chi_nhanh'];
        }
        //case cchn_tvgs
        if (isset($obj['cchn_tvgs'])) {
            $objToSave ['cchn_tvgs'] = $obj['cchn_tvgs'];
        }
        //case cchn_tvgs_ngay_hieu_luc
        if (isset($obj['cchn_tvgs_ngay_hieu_luc'])) {
            $objToSave ['cchn_tvgs_ngay_hieu_luc'] = Helper::getMongoDate($obj['cchn_tvgs_ngay_hieu_luc']);
        }
        //case cchn_tvgs_ngay_hieu_luc
        if (isset($obj['cchn_tvgs_ngay_hieu_luc']) && !empty($obj['cchn_tvgs_ngay_hieu_luc'])) {
            if (Helper::isDatetime($obj['cchn_tvgs_ngay_hieu_luc'])) {
                $objToSave['cchn_tvgs_ngay_hieu_luc'] = Helper::getMongoDate($obj['cchn_tvgs_ngay_hieu_luc']);
            } else {
                return eView::getInstance()->getJsonError('Ngày hiệu lực CCHN TVGS format không đúng kiểu');
            }
        }
        if (empty(@$obj['cchn_tvgs_ngay_hieu_luc'])) {
            $objToSave ['cchn_tvgs_ngay_hieu_luc'] = @$obj['cchn_tvgs_ngay_hieu_luc'];
        }

        //
        if (isset($obj['cchn_tvtk'])) {
            $objToSave ['cchn_tvtk'] = $obj['cchn_tvtk'];
        }

        if (isset($obj['chung_chi_khac'])) {
            $objToSave ['chung_chi_khac'] = $obj['chung_chi_khac'];
        }

        //case ngay_nhan_cong_tac
        if (isset($obj['ngay_nhan_cong_tac']) && $allowToEditImportant) {
            $objToSave ['ngay_nhan_cong_tac'] = Helper::getMongoDate($obj['ngay_nhan_cong_tac']);
        }

        //case ngay_dong_bao_hiem_xa_hoi
        if (isset($obj['ngay_dong_bao_hiem_xa_hoi']) && $allowToEditImportant) {
            $objToSave ['ngay_dong_bao_hiem_xa_hoi'] = Helper::getMongoDate($obj['ngay_dong_bao_hiem_xa_hoi']);
        }


        if (isset($obj['contract_type']['name']) && $allowToEditImportant) {
            $objToSave ['contract_type']['name'] = $obj['contract_type']['name'];
        }
        if (isset($obj['work_status']['name']) && $allowToEditImportant) {
            $objToSave ['work_status']['name'] = $obj['work_status']['name'];
        }

        if (isset($obj['so_so_bao_hiem_xa_hoi']) && $allowToEditImportant) {
            $objToSave ['so_so_bao_hiem_xa_hoi'] = $obj['so_so_bao_hiem_xa_hoi'];
        }
        if (isset($obj['muc_dong_bao_hiem_xa_hoi']) && $allowToEditImportant) {
            $objToSave ['muc_dong_bao_hiem_xa_hoi'] = $obj['muc_dong_bao_hiem_xa_hoi'];
        }
        if (isset($obj['ngay_nghi_viec']) && $obj['ngay_nghi_viec'] && $allowToEditImportant) {
            $objToSave ['ngay_nghi_viec'] = Helper::getMongoDate($obj['ngay_nghi_viec']);
        } else {
            $objToSave ['ngay_nghi_viec'] = '';
        }

        #region validate code
        if ($id) {
            if (!$obj['password']) {
                unset($objToSave['password']);
            } else {
                if (!Role::isAllowTo(Role::$ACTION_EDIT_IMPORTANT.Role::$ACTION_PASSWORD. Role::$_MP_MEMBER) && $id != Member::getCurentId()) {
                    unset($objToSave['password']);
                    return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi mật khẩu của tài khoản này!');
                }
            }
        }

        if(!empty($obj['password'])) {
            if(!empty($obj['cfpassword']) && $obj['cfpassword'] != $obj['password']) {
                return eView::getInstance()->getJsonError('Mật khẩu xác nhận không khớp');
            }elseif(empty($obj['cfpassword'])) {
                return eView::getInstance()->getJsonError('Vui lòng nhập mật khẩu xác nhận.');
            }
        }

        if (isset ($obj['mail_notice'])) {
            $objToSave['mail_notice'] = $obj['mail_notice'];
        } else {
            $objToSave['mail_notice'] = 0;
        }



        if (isset($obj['password']) && $obj['password']) {
            $objToSave['password'] = Member::genPassSave($obj['password']);
        }

        if (isset($obj['description'])) {
            $objToSave ['description'] = $obj['description'];
        }


        if ($id) {
            Member::where('_id', $id)->update($objToSave);
            Logs::createLog(
                [
                    'type' => Logs::TYPE_UPDATED,
                    'object_id' => (string)$id,
                    'note' => 'Chỉnh sửa thông tin nhân sự"' . $objToSave['name'] . '" từ ip: ' . $_SERVER['REMOTE_ADDR']
                ], Member::table_name, $currentObj->toArray(), Member::where('_id', $id)->first()->toArray()
            );
        } else {
            $id = Member::insertGetId($objToSave);
            Logs::createLog(
                [
                    'type' => Logs::TYPE_CREATE,
                    'data_object' => $objToSave,
                    'note' => "Nhân viên " . $objToSave['name'] . ' được tạo thêm ',
                ], Member::table_name, [], Member::where('_id', $id)->first()->toArray()
            );

        }
        $ret = ['redirect' => admin_link('staff/input?id=' . $id)];


        #region ghi log

        #endregion
        return eView::getInstance()->getJsonSuccess('Cập nhật thành công', $ret);
    }

    public function _save_tab_work()
    {
        #region check role
        $mng_obj = Role::mng_staff;
        $mng_action = Role::mng_action_edit;
        $requireRole [] = Role::getRoleKey($mng_obj, $mng_action);
        if (!Role::haveRole2($requireRole)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền chỉnh sửa thông tin nhân viên');
        }
        #endregion
        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        #region Kiểm tra tài khoản tồn tại chưa
        if ($id) {
            $curentObj = Member::find($id);
            if (!$curentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        } else {
            return eView::getInstance()->getJsonError('Yêu cầu không đúng');
        }
        #endregion

        #region Kiểm tra quyền
        if (!Member::haveRole(Member::mng_staff_account)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi thông tin tài khoản');
        }
        #endregion

        $objToSave = [];

        #region validate
        //case thong_tin_hop_dong_lao_dong todo validate giấy tờ
        if (isset($obj['thong_tin_hop_dong_lao_dong'])) {
            $objToSave['thong_tin_hop_dong_lao_dong'] = array_values($obj['thong_tin_hop_dong_lao_dong']);
        } else {
            $objToSave['thong_tin_hop_dong_lao_dong'] = [];
        }


        if (isset($obj['thong_tin_hop_dong_lao_dong'])) {
            $objToSave['thong_tin_hop_dong_lao_dong'] = array_values($obj['thong_tin_hop_dong_lao_dong']);
            foreach ($objToSave['thong_tin_hop_dong_lao_dong'] as $key => $value) {
                if (isset($value['ngay_bat_dau']) && !empty($value['ngay_bat_dau'])) {
                    if (Helper::isDatetime($value['ngay_bat_dau'])) {
                        $objToSave['thong_tin_hop_dong_lao_dong'][$key]['ngay_bat_dau'] = Helper::getMongoDate($value['ngay_bat_dau']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày bắt đầu không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
                if (isset($value['ngay_ket_thuc']) && !empty($value['ngay_ket_thuc'])) {
                    if (Helper::isDatetime($value['ngay_ket_thuc'])) {
                        $objToSave['thong_tin_hop_dong_lao_dong'][$key]['ngay_ket_thuc'] = Helper::getMongoDate($value['ngay_ket_thuc']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày kết thúc không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
            }

        }
        //case qua_trinh_cong_tac todo validate giấy tờ
        if (isset($obj['qua_trinh_cong_tac'])) {
            $objToSave['qua_trinh_cong_tac'] = array_values($obj['qua_trinh_cong_tac']);
        } else {
            $objToSave['qua_trinh_cong_tac'] = [];
        }
        if (isset($obj['qua_trinh_cong_tac'])) {
            $objToSave['qua_trinh_cong_tac'] = array_values($obj['qua_trinh_cong_tac']);
            foreach ($objToSave['qua_trinh_cong_tac'] as $key => $value) {
                if (isset($value['position']['id']) && Helper::isMongoId($value['position']['id'])) {
                    $temp = MetaData::where('_id', $value['position']['id'])->first();
                    if ($temp) {
                        $temp['id'] = $temp['_id'];
                        $temp = $temp->toArray();
                        unset($temp['_id']);
                        $objToSave['qua_trinh_cong_tac'][$key]['position'] = $temp;

                    }
                }
                if (isset($value['department']['id']) && Helper::isMongoId($value['department']['id'])) {
                    $temp = MetaData::where('_id', $value['department']['id'])->first();
                    if ($temp) {
                        $temp['id'] = $temp['_id'];
                        $temp = $temp->toArray();
                        unset($temp['_id']);
                        $objToSave['qua_trinh_cong_tac'][$key]['department'] = $temp;
                    }
                }
                if (isset($value['project']['id']) && Helper::isMongoId($value['project']['id'])) {
                    $temp = Project::where('_id', $value['project']['id'])->first();
                    if ($temp) {
                        $temp['id'] = $temp['_id'];
                        $temp = $temp->toArray();
                        unset($temp['_id']);
                        $objToSave['qua_trinh_cong_tac'][$key]['project'] = $temp;
                    }
                }

                if (isset($value['ngay_bat_dau']) && !empty($value['ngay_bat_dau'])) {
                    if (Helper::isDatetime($value['ngay_bat_dau'])) {
                        $objToSave['qua_trinh_cong_tac'][$key]['ngay_bat_dau'] = Helper::getMongoDate($value['ngay_bat_dau']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày bắt đầu không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
                if (isset($value['ngay_ket_thuc']) && !empty($value['ngay_ket_thuc'])) {
                    if (Helper::isDatetime($value['ngay_ket_thuc'])) {
                        $objToSave['qua_trinh_cong_tac'][$key]['ngay_ket_thuc'] = Helper::getMongoDate($value['ngay_ket_thuc']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày kết thúc không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
            }

        }


        //case file_thong_tin_cong_viec
        if (isset($obj['files_thong_tin_cong_viec']) ) {
            if(is_array($obj['files_thong_tin_cong_viec'])){
                if (isset($obj['files_thong_tin_cong_viec']['name'])) {
                    foreach ($obj['files_thong_tin_cong_viec']['name'] as $k => $v) {
                        $objToSave['files_thong_tin_cong_viec'][$k]['name'] = $v;
                    }
                }
                if (isset($obj['files_thong_tin_cong_viec']['path'])) {
                    foreach ($obj['files_thong_tin_cong_viec']['path'] as $k => $v) {
                        $objToSave['files_thong_tin_cong_viec'][$k]['path'] = $v;
                    }
                }
            }else{
                $objToSave['files_thong_tin_cong_viec'] = [];
            }

        }

        //
        if (empty($objToSave)) {
            return eView::getInstance()->getJsonError('Cập nhật không thành công,  thiếu dữ liệu');
        }
        #endregion

        Member::where('_id', $id)->update($objToSave);

        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_CREATE,
                'data_object' => $objToSave,
                'note' => "Nhân viên " . $curentObj['name'] . ' được sửa thông tin công việc',
            ], Logs::OBJECT_STAFF_WORK
        );
        #endregion

        return eView::getInstance()->getJsonSuccess('Câp nhật thành công', ['reload' => true]);
    }

    public function _save_tab_family()
    {

        #region check role
        $mng_obj = Role::mng_staff;
        $mng_action = Role::mng_action_edit;
        $requireRole [] = Role::getRoleKey($mng_obj, $mng_action);
        if (!Role::haveRole2($requireRole)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền chỉnh sửa thông tin nhân viên');
        }
        #endregion
        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        #region Kiểm tra tài khoản tồn tại chưa
        if ($id) {
            $curentObj = Member::find($id);
            if (!$curentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        } else {
            return eView::getInstance()->getJsonError('Yêu cầu không đúng');
        }
        #endregion

        #region Kiểm tra quyền
        if (!Member::haveRole(Member::mng_staff_account)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi thông tin tài khoản');
        }
        #endregion

        $objToSave = [];

        #region validate code
        //case thong_tin_hop_dong_lao_dong todo validate giấy tờ
        if (isset($obj['thong_tin_gia_dinh'])) {
            $objToSave['thong_tin_gia_dinh'] = array_values($obj['thong_tin_gia_dinh']);
        }

        //case to_chuc_doan_the
        if (isset($obj['thong_tin_gia_dinh'])) {
            $objToSave['thong_tin_gia_dinh'] = array_values($obj['thong_tin_gia_dinh']);
            foreach ($objToSave['thong_tin_gia_dinh'] as $key => $value) {
                if (isset($value['nghe_nghiep']['id']) && Helper::isMongoId($value['nghe_nghiep']['id'])) {
                    $temp = MetaData::where('_id', $value['nghe_nghiep']['id'])->first();
                    if ($temp) {
                        $objToSave['thong_tin_gia_dinh'][$key]['nghe_nghiep']['name'] = $temp['name'];
                    }
                }

                if (isset($value['ngay_sinh']) && !empty($value['ngay_sinh'])) {
                    if (Helper::isDatetime($value['ngay_sinh'])) {
                        $objToSave['thong_tin_gia_dinh'][$key]['ngay_sinh'] = Helper::getMongoDate($value['ngay_sinh']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày sinh không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
            }

        }


        //case file_thong_tin_co_ban
        if (isset($obj['files_thong_tin_gia_dinh'])) {
            if(is_array($obj['files_thong_tin_gia_dinh'])){
                if (isset($obj['files_thong_tin_gia_dinh']['name'])) {
                    foreach ($obj['files_thong_tin_gia_dinh']['name'] as $k => $v) {
                        $objToSave['files_thong_tin_gia_dinh'][$k]['name'] = $v;
                    }
                }
                if (isset($obj['files_thong_tin_gia_dinh']['path'])) {
                    foreach ($obj['files_thong_tin_gia_dinh']['path'] as $k => $v) {
                        $objToSave['files_thong_tin_gia_dinh'][$k]['path'] = $v;
                    }
                }
            }else{
                $objToSave['files_thong_tin_gia_dinh'] = [];
            }

        }

        //
        if (empty($objToSave)) {
            return eView::getInstance()->getJsonError('Cập nhật không thành công,  thiếu dữ liệu');
        }
        #endregion

        Member::where('_id', $id)->update($objToSave);

        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_CREATE,
                'data_object' => $objToSave,
                'note' => "Nhân viên " . $curentObj['name'] . ' được sửa thông tin gia đình',
            ], Logs::OBJECT_STAFF_FAMILY
        );
        #endregion

        return eView::getInstance()->getJsonSuccess('Câp nhật thành công', ['reload' => true]);
    }

    public function _save_tab_edu()
    {        #region check role
        $mng_obj = Role::mng_staff;
        $mng_action = Role::mng_action_edit;
        $requireRole [] = Role::getRoleKey($mng_obj, $mng_action);
        if (!Role::haveRole2($requireRole)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền chỉnh sửa thông tin nhân viên');
        }
        #endregion
        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        #region Kiểm tra tài khoản tồn tại chưa
        if ($id) {
            $curentObj = Member::find($id);
            if (!$curentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        } else {
            return eView::getInstance()->getJsonError('Yêu cầu không đúng');
        }
        #endregion

        #region Kiểm tra quyền
        if (!Member::haveRole(Member::mng_staff_account)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi thông tin tài khoản');
        }
        #endregion
        $objToSave = [];

        #region validate code
        if (isset($obj['bang_cap'])) {
            $objToSave['bang_cap'] = array_values($obj['bang_cap']);
            foreach ($objToSave['bang_cap'] as $key => $value) {
                if (isset($value['loai_bang_cap']['id']) && Helper::isMongoId($value['loai_bang_cap']['id'])) {
                    $temp = MetaData::where('_id', $value['loai_bang_cap']['id'])->first();
                    if ($temp) {
                        $objToSave['bang_cap'][$key]['loai_bang_cap']['name'] = $temp['name'];
                    }
                }
                if (isset($value['chuyen_mon']['id']) && Helper::isMongoId($value['chuyen_mon']['id'])) {
                    $temp = MetaData::where('_id', $value['chuyen_mon']['id'])->first();
                    if ($temp) {
                        $objToSave['bang_cap'][$key]['chuyen_mon']['name'] = $temp['name'];
                    }
                }
                if (isset($value['chuyen_nganh']['id']) && Helper::isMongoId($value['chuyen_nganh']['id'])) {
                    $temp = MetaData::where('_id', $value['chuyen_nganh']['id'])->first();
                    if ($temp) {
                        $objToSave['bang_cap'][$key]['chuyen_nganh']['name'] = $temp['name'];
                    }
                }
                if (isset($value['noi_cap']['id']) && Helper::isMongoId($value['noi_cap']['id'])) {
                    $temp = MetaData::where('_id', $value['noi_cap']['id'])->first();
                    if ($temp) {
                        $objToSave['bang_cap'][$key]['noi_cap']['name'] = $temp['name'];
                    }
                }

                if (isset($value['ngay_cap']) && !empty($value['ngay_cap'])) {
                    if (Helper::isDatetime($value['ngay_cap'])) {
                        $objToSave['bang_cap'][$key]['ngay_cap'] = Helper::getMongoDate($value['ngay_cap']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày cấp không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
            }
        }


        //case chung_chi_dao_tao todo validate giấy tờ

        if (isset($obj['chung_chi_dao_tao'])) {
            $objToSave['chung_chi_dao_tao'] = array_values($obj['chung_chi_dao_tao']);
            foreach ($objToSave['chung_chi_dao_tao'] as $key => $value) {
                if (isset($value['loai_chung_chi']['id']) && Helper::isMongoId($value['loai_chung_chi']['id'])) {
                    $temp = MetaData::where('_id', $value['loai_chung_chi']['id'])->first();
                    if ($temp) {
                        $objToSave['chung_chi_dao_tao'][$key]['loai_chung_chi']['name'] = $temp['name'];
                    }
                }

                if (isset($value['hang_chung_chi'])) {
                    $objToSave['chung_chi_dao_tao'][$key]['hang_chung_chi'] = $value['hang_chung_chi'];
                }
                if (isset($value['noi_cap']['id']) && Helper::isMongoId($value['noi_cap']['id'])) {
                    $temp = MetaData::where('_id', $value['noi_cap']['id'])->first();
                    if ($temp) {
                        $objToSave['chung_chi_dao_tao'][$key]['noi_cap']['name'] = $temp['name'];
                    }
                }

                if (isset($value['ngay_cap']) && !empty($value['ngay_cap'])) {
                    if (Helper::isDatetime($value['ngay_cap'])) {
                        $objToSave['chung_chi_dao_tao'][$key]['ngay_cap'] = Helper::getMongoDate($value['ngay_cap']);
                    } else {
                        return eView::getInstance()->getJsonError('Thông tin ngày cấp không đúng định dạng, phải là kiểu  d/m/Y');
                    }
                }
            }

        }

        //case file_thong_tin_co_ban
        if (isset($obj['files_thong_tin_dao_tao']) ) {
            if(is_array($obj['files_thong_tin_dao_tao'])){
                if (isset($obj['files_thong_tin_dao_tao']['name'])) {
                    foreach ($obj['files_thong_tin_dao_tao']['name'] as $k => $v) {
                        $objToSave['files_thong_tin_dao_tao'][$k]['name'] = $v;
                    }
                }
                if (isset($obj['files_thong_tin_dao_tao']['path'])) {
                    foreach ($obj['files_thong_tin_dao_tao']['path'] as $k => $v) {
                        $objToSave['files_thong_tin_dao_tao'][$k]['path'] = $v;
                    }
                }
            }else{
                $objToSave['files_thong_tin_dao_tao'] = [];
            }

        }



        if (empty($objToSave)) {
            return eView::getInstance()->getJsonError('Cập nhật không thành công,  thiếu dữ liệu');
        }
        #endregion

        Member::where('_id', $id)->update($objToSave);

        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_CREATE,
                'data_object' => $objToSave,
                'note' => "Nhân viên " . $curentObj['name'] . ' được sửa thông tin đào tạo',
            ], Logs::OBJECT_STAFF_FAMILY
        );
        #endregion

        return eView::getInstance()->getJsonSuccess('Câp nhật thành công', ['reload' => true]);
    }

    public function _save_tab_role()
    {
        #region check role
        $isAllow = Role::isAllowTo(Role::$ACTION_ROLE. Role::$_MP_MEMBER);
        if (!$isAllow) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thực hiện chức năng này.');
        }
        // dd(request()->all());
        #endregion
        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        #region Kiểm tra tài khoản tồn tại chưa
        if ($id) {
            $curentObj = Member::find($id);
            if (!$curentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        } else {
            return eView::getInstance()->getJsonError('Yêu cầu không đúng');
        }
        #endregion

        #region Kiểm tra quyền
        $isAllow = Role::isAllowTo(Role::$ACTION_EDIT. Role::$_MP_MEMBER);
        if (!$isAllow) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thực hiện chức năng này.');
        }
        #endregion

        #region validate code
        if(!empty($obj['password'])) {
            if(!empty($obj['cfpassword']) && $obj['cfpassword'] != $obj['password']) {
                return eView::getInstance()->getJsonError('Mật khẩu xác nhận không khớp');
            }elseif(empty($obj['cfpassword'])) {
                return eView::getInstance()->getJsonError('Vui lòng nhập mật khẩu xác nhận.');
            }
        }

        //case account
        $objToSave = [
            'password' => (isset($obj['password']) && $obj['password']) ? $obj['password'] : '',
            'account' => (isset($obj['account']) && $obj['account']) ? $obj['account'] : '',
        ];

        if (isset ($obj['mail_notice'])) {
            $objToSave['mail_notice'] = $obj['mail_notice'];
        } else {
            $objToSave['mail_notice'] = 0;
        }

        if ($id) {
            if (!$objToSave['password']) {
                unset($objToSave['password']);
            } else {
                if (!Role::isAllowTo(Role::$ACTION_PASSWORD. Role::$_MP_MEMBER) && $id != Member::getCurentId()) {
                    unset($objToSave['password']);

                    return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi mật khẩu của tài khoản này!');
                }
            }
        }

        if (isset($objToSave['password']) && $objToSave['password']) {
            $objToSave['password'] = Member::genPassSave($objToSave['password']);
        }


        if ($objToSave['account'] && !Helper::isAccount($objToSave['account'])) {
            return eView::getInstance()->getJsonError('Tài khoản không hợp lệ (Tài khoản không được chứa ký tự đặc biệt)');
        } else if ($objToSave['account']) {
            $_mem = Member::getMemberByAccount($objToSave['account']);
            if (isset($_mem['_id']) && $_mem['_id'] != $id) {
                return eView::getInstance()->getJsonError('Tài khoản "' . $_mem['account'] . '" đã được sử dụng');
            }
        } else {
            unset($objToSave['account']);
        }

        if (isset($obj['department']['id']) && Helper::isMongoId($obj['department']['id'])) {
            $temp = Department::where('_id', $obj['department']['id'])->first();
            if ($temp) {
                $temp['id'] = $temp['_id'];
                $temp = $temp->toArray();
                unset($temp['_id']);
                $objToSave['department'] = $temp;
            }
            // có phòng ban thì ms đc chọn chức vụ
            if (isset($obj['position']['id']) && Helper::isMongoId($obj['position']['id'])) {
                $temp = Position::where('_id', $obj['position']['id'])->first();
                if ($temp) {
                    $temp['id'] = $temp['_id'];
                    $temp = $temp->toArray();
                    unset($temp['_id']);
                    if(isset($temp['roles'])) {
                        $objToSave['roles'] = $temp['roles'];
                        unset($temp['roles']);
                    }
                    unset($temp['department']);
                    $objToSave['position'] = $temp;

                }
            }
        }else{
            if(isset($obj['department']['id'] )&& empty($obj['department']['id'])){
                $objToSave['department']  = [];
                $objToSave['position']  = [];
            }
        }


        #endregion

        Member::where('_id', $id)->update($objToSave);

        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_CREATE,
                'data_object' => $objToSave,
                'note' => "Nhân viên " . $curentObj['name'] . ' phân quyền vào nhóm, phòng ban',
            ], Logs::OBJECT_ROLE
        );
        #endregion

        return eView::getInstance()->getJsonSuccess('Câp nhật thành công', ['reload' => true]);
    }
    public function _delete_account()
    {
        #region check role
        $mng_obj = Role::mng_staff;
        $requireRole [] = Role::getRoleKey($mng_obj, Role::mng_action_edit);
        $requireRole [] = Role::getRoleKey($mng_obj, Role::mng_action_role);
        if (!Role::haveRole2($requireRole)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền chỉnh sửa quyền hạn của nhân viên');
        }

        #endregion
        $obj = Request::capture()->input('obj', []);
        $id = Request::capture()->input('id', 0);
        #region Kiểm tra tài khoản tồn tại chưa
        if ($id) {
            $curentObj = Member::find($id);
            if (!$curentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
        } else {
            return eView::getInstance()->getJsonError('Yêu cầu không đúng');
        }
        #endregion

        #region Kiểm tra quyền
        if (!Member::haveRole(Member::mng_staff_account)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền thay đổi thông tin tài khoản');
        }
        #endregion
        $objToSave = [];

        #region validate code

        //case account
        $objToSave = [
            'password' => '',
            'account' => '',
        ];

        if (isset ($obj['mail_notice'])) {
            $objToSave['mail_notice'] = $obj['mail_notice'];
        } else {
            $objToSave['mail_notice'] = 0;
        }


        #endregion

        Member::where('_id', $id)->update($objToSave);

        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_DELETE,
                'data_object' => $objToSave,
                'note' => "Xoá tài khoản " . $curentObj['account'] . ' của nhân viên '. $curentObj['code'].' '. $curentObj['name'],
            ], Logs::OBJECT_ROLE
        );
        #endregion

        return eView::getInstance()->getJsonSuccess('Đã xoá tài khoản của nhân viên '.$curentObj['code'], ['reload' => true]);
    }

    public function _delete()
    {
        #region check role
        // $mng_obj = Role::mng_staff;
        // $requireRole [] = Role::getRoleKey($mng_obj, Role::mng_action_delete);
        // if (!Role::haveRole2($requireRole)) {
        //     return eView::getInstance()->getJsonError('Bạn không có quyền xoá thông tin nhân viên');
        // }
        #endregion
        $id = Request::capture()->input('id', 0);
        $token = Request::capture()->input('token', 0);

        if (!Helper::validateToken($token, $id)) {
            return eView::getInstance()->getJsonError('Bạn không thể xóa đối tượng này');
        }

        // if (!Member::haveRole(Member::mng_staff_account)) {
        //     return eView::getInstance()->getJsonError('Bạn không có quyền xóa tài khoản');
        // }

        $member = Member::find($id);
        if (!$member) {
            return eView::getInstance()->getJsonError('Dữ liệu không tồn tại hoặc đã bị xóa');
        }

        if (in_array($member['account'], Member::ROOT_ACCOUNT)) {
            return eView::getInstance()->getJsonError('Bạn không thể xóa thành viên này');
        }
        Logs::createLog(
            [
                'type' => Logs::TYPE_DELETE,
                'data_object' => $member->toArray(),
                'note' => "Nhân viên " . $member['name'] . ' bị xóa bởi ' . Member::getCurentAccount(),
            ], Logs::OBJECT_STAFF
        );

        Member::where('_id', $id)->delete();

        return eView::getInstance()->getJsonSuccess('Xóa đối tượng thành công. Bạn không thể khôi phục lại', []);
    }



    /**
     * Quản lý phòng ban
     * danh sách, thêm ,sửa, xóa all inone
     */
    function department()
    {
        #region check role
        $mng_obj = Role::mng_staff;
        $requireRole [] = Role::getRoleKey($mng_obj, Role::mng_action_dep);
        if (!Role::haveRole2($requireRole)) {
            return eView::getInstance()->getJsonError('Bạn không có quyền quản lý phòng ban');
        }
        #endregion
        $action = Request::capture()->input('action');
        $id = Request::capture()->input('id');

        $tpl = [];

        switch ($action) {
            case 'show-form':
                {
                    //show form popup
                    if ($id) {
                        $tpl['obj'] = MetaData::find($id);
                        /*$lsPos = MetaData::where('department.id',$id)->get()->toArray();
                        $tpl['lsPos']  =$lsPos;*/
                    }

                    return eView::getInstance()->setViewBackEnd(__DIR__, 'staff-meta/department_input', $tpl);
                }
            case 'update':
                {
                    if (!Member::haveRole(Member::mng_staff_department)) {
                        return eView::getInstance()->getJsonError('Bạn không có quyền cập nhật thông tin phòng ban');
                    }
                    $obj = Request::capture()->input('obj');
                    if (!isset($obj['name']) || !$obj['name']) {
                        return eView::getInstance()->getJsonError('Bạn cần nhập tên');
                    }

                    if (!$id) {
                        //thêm mới
                        if (isset($obj['department_type'])) {
                            if ($obj['department_type'] === MetaData::DEPARTMENT_LEVEL['level_1']['id']) {
                                $obj['parent_dep'] = [];
                            } else if ($obj['department_type'] === MetaData::DEPARTMENT_LEVEL['level_2']['id']) {
                                if (!isset($obj['parent_dep']['id']) || empty($obj['parent_dep']['id'])) {
                                    return eView::getInstance()->getJsonError('Bạn cần phải chọn phòng ban cha');
                                }
                                $parent_dep = MetaData::where('_id', $obj['parent_dep']['id'])->first();
                                if (!$parent_dep) {
                                    return eView::getInstance()->getJsonError('Không tìm thấy phòng ban cha yêu cầu');
                                }
                                $tempId = $obj['parent_dep']['id'];
                                $parent_dep = $parent_dep->toArray();
                                $obj['parent_dep'] = $parent_dep;
                                $obj['parent_dep']['id'] = $tempId;
                                unset($obj['parent_dep'] ['_id']);
                            }
                        }
                        $id = MetaData::insertGetId($obj);
                        Logs::createLog(
                            [
                                'type' => Logs::TYPE_CREATE,
                                'data_object' => $obj,
                                'note' => "Phòng ban " . $obj['name'] . ' được thêm',
                            ], Logs::OBJECT_DEPARTMENT
                        );

                        if (isset($obj['position']) && $obj['position']) {
                            $pos = explode(',', $obj['position']);
                            foreach ($pos as $item) {
                                $item = trim($item);
                                if ($item) {
                                    $_savePos = [
                                        'name' => $item,
                                        'department' => [
                                            'id' => (string)$id,
                                            'name' => $obj['name'],
                                        ],
                                        'type' => MetaData::$typeRegister['position']['key'],
                                        'object' => 'staff',
                                    ];
                                    MetaData::insert($_savePos);
                                }
                            }
                        }

                        return eView::getInstance()->getJsonSuccess('Phòng ban "' . $obj['name'] . '" được thêm thành công!', ['reload' => true]);
                    } else {
                        //update
                        $objInDb = MetaData::find($id);
                        if (!$objInDb) {
                            return eView::getInstance()->getJsonError('Bản ghi này không tồn tại hoặc đã bị xóa');
                        }
                        if (isset($obj['department_type'])) {
                            if ($obj['department_type'] === MetaData::DEPARTMENT_LEVEL['level_1']['id']) {
                                $obj['parent_dep'] = [];
                            } else if ($obj['department_type'] === MetaData::DEPARTMENT_LEVEL['level_2']['id']) {
                                if (!isset($obj['parent_dep']['id']) || empty($obj['parent_dep']['id'])) {
                                    return eView::getInstance()->getJsonError('Bạn cần phải chọn phòng ban cha');
                                }
                                $parent_dep = MetaData::where('_id', $obj['parent_dep']['id'])->first();
                                if (!$parent_dep) {
                                    return eView::getInstance()->getJsonError('Không tìm thấy phòng ban cha yêu cầu');
                                }
                                $tempId = $obj['parent_dep']['id'];
                                $parent_dep = $parent_dep->toArray();
                                $obj['parent_dep'] = $parent_dep;
                                $obj['parent_dep']['id'] = $tempId;
                                unset($obj['parent_dep'] ['_id']);

                            }
                        }

                        $objInDb->update($obj);
                        Logs::createLog(
                            [
                                'type' => Logs::TYPE_UPDATED,
                                'data_object' => $obj,
                                'note' => "Phòng ban " . $obj['name'] . ' được sửa',
                            ], Logs::OBJECT_DEPARTMENT
                        );


                        if (isset($obj['position']) && $obj['position']) {
                            $pos = explode(',', $obj['position']);
                            foreach ($pos as $item) {
                                $item = trim($item);
                                if ($item) {
                                    $_savePos = [
                                        'name' => $item,
                                        'department' => [
                                            'id' => (string)$id,
                                            'name' => $obj['name'],
                                        ],
                                        'type' => MetaData::$typeRegister['position']['key'],
                                        'object' => 'staff',
                                    ];
                                    MetaData::insert($_savePos);
                                }
                            }
                        }

                        return eView::getInstance()->getJsonSuccess('Phòng ban "' . $obj['name'] . '" được cập nhật thành công!', ['reload' => true]);
                    }
                }
            case
            'delete':
                {
                    if (!Member::haveRole(Member::mng_staff_department)) {
                        return eView::getInstance()->getJsonError('Bạn không có quyền cập nhật thông tin phòng ban');
                    }
                    $token = Request::capture()->input('token', 0);

                    if (!Helper::validateToken($token, $id)) {
                        return eView::getInstance()->getJsonError('Bạn không thể xóa đối tượng này');
                    }
                    $objInDb = MetaData::find($id);
                    if (!$objInDb) {
                        return eView::getInstance()->getJsonError('Bản ghi này không tồn tại hoặc đã bị xóa');
                    }
                    $objInDb->delete();
                    //xoas toanf bộ chwucs vụ
                    MetaData::where('department.id', $id)->delete();
                    //todo@ngannv Xóa toàn bộ quyền liên quan đến chức vụ và phòng ban
                    Logs::createLog(
                        [
                            'type' => Logs::TYPE_DELETE,
                            'data_object' => $objInDb->toArray(),
                            'note' => "Phòng ban " . $objInDb['name'] . ' bị xóa bởi ' . Member::getCurentAccount() . '',
                        ], Logs::OBJECT_DEPARTMENT
                    );

                    return eView::getInstance()->getJsonSuccess('Phòng ban "' . $objInDb['name'] . '" được xóa thành công!');
                }
            default:
                {
                    HtmlHelper::getInstance()->setTitle('Quản lý phòng ban - Quản lý nhân viên');

                    $tpl['listObj'] = MetaData::getParentDepartment();
                    $tpl['listChildren'] = MetaData::getDepartChildren();
                    $tpl['lsPos'] = MetaData::getPositionStaff();

                    return eView::getInstance()->setViewBackEnd(__DIR__, 'staff-meta/department_list', $tpl);
                }
        }
    }

    function _get_staff_list()
    {
        $department_id = Request::capture()->input('department_id');
        if (!$department_id) {
            return eView::getInstance()->getJsonSuccess('Bạn cần chọn phòng ban');
        }
        $department_id = explode(',', $department_id);
        if ($department_id) {
            //$staff = Staff::whereIn('departments', $department_id)->select(['_id', 'name', 'phone', 'email'])->get();
            $staff = Staff::whereIn('departments.id', $department_id)->get();
            return eView::getInstance()->getJsonSuccess('list', $staff);
        }

        return eView::getInstance()->getJsonSuccess('Không tìm thấy danh sách nhân sự');

    }

    function suggest()
    {
        $q = mb_convert_encoding(Request::capture()->input('q', ''), 'UTF-8');
        $listObj = Staff::select(['name', '_id', 'code', 'department']);
        if ($q) {
            $listObj = $listObj->where(
                function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . trim($q) . '%')
                        ->OrWhere('phone', 'LIKE', '%' . trim($q) . '%')
                        ->OrWhere('email', 'LIKE', '%' . trim($q) . '%');

                }
            );
        }
        $itemPerPage = 20;
        $listObj = $listObj->orderBy('name', 'desc');
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        $re = $listObj->toArray()['data'];
        $dt = [];
        foreach ($re as $k => $v) {
            $tempName = @$v['code'] ? $v['code'] . ' - ' : '';
            $tempName = $tempName . $v['name'];
            if (@$v['department']['name']) {
                $tempName = $tempName . '(' . @$v['department']['name'] . ')';
            }
            $dt[] = [
                'id' => $v['_id'],
                'text' => $tempName,
            ];
        }

        return eView::getInstance()->getJsonSuccess('list', $dt);
    }

    //Lấy ra danh sách các position
    function get_position_list()
    {
        $department_id = Request::capture()->input('department_id', 0);
        $positionList = MetaData::getParentDepartment(['department.id' => $department_id]);

        return $this->outputDone($positionList, 'Danh sách');
    }


    public function change_password()
    {
        $id = Request::capture()->input('id', 0);
        if (!$id) {
            return eView::getInstance()->getJsonError("Thiếu thông tin id");
        }
        $obj = Member::where('_id', $id)->first();
        if (!$obj) {
            return eView::getInstance()->getJsonError("Không tìm nhân viên");
        }
        $tpl = [];
        $tpl['obj'] = $obj;

        return eView::getInstance()->setViewBackEnd(__DIR__, 'change-password', $tpl);

    }

    public function update_change_password()
    {
        $id = Request::capture()->input('id', 0);
        $pass = Request::capture()->input('password', '');
        $newPass = Request::capture()->input('new-password', '');
        $reNewPass = Request::capture()->input('re-new-password', '');
        if (!$id) {
            return eView::getInstance()->getJsonError("Thiếu thông tin id");
        }
        if ($id !== Member::getCurentId()) {
            return eView::getInstance()->getJsonError('Bạn không có quyền này');
        }
        $currentMember = Member::where('_id', $id)->first();
        if (empty($pass)) {
            return eView::getInstance()->getJsonError('Bạn chưa điền mật khẩu cũ');
        }
        if (empty($newPass)) {
            return eView::getInstance()->getJsonError('Mật khẩu mới không được để trống');
        }
        if (empty($reNewPass)) {
            return eView::getInstance()->getJsonError('Bạn chưa nhập lại mật khẩu mới');
        }

        $curPass = @$currentMember['password'];
        if (Member::genPassSave($pass) !== $curPass) {
            return eView::getInstance()->getJsonError('Mật khẩu cũ không đúng');
        }

        if ($newPass !== $reNewPass) {
            return eView::getInstance()->getJsonError('Mật khẩu nhập lại không giống với mật khẩu mới');
        }

        $newPass = Member::genPassSave($newPass);
        Member::where('_id', $id)->update(['password' => $newPass]);
        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_UPDATED,
                'data_object' => [],
                'note' => "Nhân viên " . @$currentMember['name'] . ' Đổi tự đổi mật khẩu của mình',
            ], Logs::OBJECT_ROLE
        );
        #endregion
        return eView::getInstance()->getJsonSuccess('Đổi mật khẩu thành công', ['reload' => true]);


    }


    public function preview()
    {
        $id = Request::capture()->input('id', 0);
        if (!$id) {
            return eView::getInstance()->getJsonError("Thiếu thông tin id");
        }
        $obj = Member::where('_id', $id)->first();
        if (!$obj) {
            return eView::getInstance()->getJsonError("Không tìm nhân viên");
        }
        $tpl = [];
        $tpl['obj'] = $obj;

        #region prefill dữ liệu
        #endregion


        return eView::getInstance()->setViewBackEnd(__DIR__, 'preview', $tpl);

    }
//    public function staff_noi_cap_bang(){
//
//        return MetaData::where(['type'=>'noi_cap_bang_cap'])->update(['type'=>'staff_noi_cap_bang_cap', 'object'=>'staff']);
//
//    }


    public function metastaff()
    {
        $q_type = Request::capture()->input('q_type', 0);
        $q = mb_convert_encoding(Request::capture()->input('q', ''), 'UTF-8');
        if ($q_type !== MetaData::PROJECT) {
            $listObj = MetaData::select(['name', 'key', '_id', 'type']);
        } else {
            $listObj = Project::where([]);
        }

        $listObj = $listObj->where([]);
        if ($q_type !== MetaData::PROJECT) {
            $listObj = $listObj->where('type', $q_type);
        } else {

        }
        if ($q) {
            $listObj = $listObj->where(
                function ($query) use ($q, $q_type) {
                    $q = explode(',', $q);
                    collect($q)->each(function ($_q) use ($query) {
                        $query
                            ->OrWhere('name', 'LIKE', '%' . trim($_q) . '%')
                            ->OrWhere('key', 'LIKE', '%' . trim($_q) . '%');
                        if (Helper::isMongoId($_q)) {
                            $query
                                ->OrWhere('_id', $_q);
                        }
                    });


                }
            );
        }
        $itemPerPage = 20;
        $listObj = $listObj->orderBy('name', 'desc');
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        $re = $listObj->toArray()['data'];
        $dt = [];
        foreach ($re as $k => $v) {
//            if ($q_type == MetaData::STAFF_TO_CHUC_DOAN_THE) {
//                //cái này là do lúc lưu member không lưu alias nên xử lý bằng id
//                $dt[] = [
//                    'id' => $v['_id'],
//                    'text' => $v['name'],
//                ];
//            } else {
            $dt[] = [
                'id' => $v['_id'],
                'text' => $v['name'],
            ];

//            }

        }

        return eView::getInstance()->getJsonSuccess('list', $dt);
    }

    public function export_popup()
    {
        $tpl = [];
        return eView::getInstance()->setViewBackEnd(__DIR__, 'export-popup', $tpl);
    }

    public function export_excel()
    {

        $data = Member::where([])->get()->toArray();

        $tpl = [];
        $tpl['listObj'] = $data;
        return eView::getInstance()->setViewBackEnd(__DIR__, 'table-to-excel', $tpl);

    }



    public function test()
    {
        Staff::getStaffCanViewObj([], Role::mng_document);
    }

}
