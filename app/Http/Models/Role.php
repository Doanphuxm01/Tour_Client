<?php
/**
 * Created by khoait109@gmail.com
 * Website: https://kayn.pro
 */


namespace App\Http\Models;


use App\Elibs\Debug;
use App\Elibs\Helper;

class Role extends BaseModel
{

    public $timestamps = FALSE;
    const table_name = 'setting_roles';//key role
    protected $table = self::table_name;
    static $unguarded = TRUE;
    static $currentRole = [];//luu id member và id project làm key, value là danh sách role tương ứng

    static $permissionByGroup = [];
    const SESSION_KEY_FOR_ROLE_MEMBER = 'SESSION_KEY_FOR_ROLE_MEMBER';

    static function isAdmin()
    {
        return self::isRoot();
    }

    static function isRoot()
    {

        if (!Member::$currentMember) {
            return FALSE;
        }
        return in_array(Member::$currentMember['account'], Member::ROOT_ACCOUNT);
    }

    /*
     * Các module cần phân quyền
     * */
    static $_MP_SYSTEM = 'SYSTEM';
    static $_MP_MEMBER = 'MEMBER';
    static $_MP_POSITION = 'POSITION';
    static $_MP_DEPARTMENT = 'DEPARTMENT';
    static $_MP_NEWS = 'NEWS';
    static $_MP_VIDEOS = 'VIDEOS';
    static $_MP_MENU = 'MENU';
    static $_MP_CALENDAR = 'CALENDAR';
    static $_MP_MEDIA = 'MEDIA';
    static $_MP_CATEGORY = 'CATEGORY_';
    static $_MP_LOCATION = 'LOCATION';
    static $_MP_PRODUCT = 'PRODUCT';
    static $_MP_VEHICLE = 'VEHICLE';
    static $_MP_BOOKING = 'BOOKING';
    static $_MP_TOUR = 'TOUR';
    static $_MP_COMMENT = 'COMMENT';
    static $_MP_VOUCHER = 'VOUCHER';
    static $_MP_QUATANG = 'QUATANG';
    static $_MP_CUSTOMER = 'CUSTOMER';
    static $_MP_SUBSCRIBER = 'SUBSCRIBER';
    static $_MP_CONFIG_WEBSITE = 'CONFIG_WEBSITE_';
    static $_MP_SOCIAL = 'SOCIAL';
    static $UNAUTHORIZEDPERSONNEL = '$UNAUTHORIZEDPERSONNEL';
    static $_MP_ODER = 'ORDER_';
    static $_MP_WITHDRAWAL = 'WITHDRAWAL';
    static $_MP_MUAHANG = 'MUAHANG';
    static $_MP_VICHIETKHAU = 'VICHIETKHAU';

    static $_MP_VIHOAHONG = 'VIHOAHONG';
    static $_MP_VITICHLUY = 'VITICHLUY';
    static $_MP_KHODIEM = 'KHODIEM';
    static $_MP_AGENCY = 'AGENCY';

    /*
     * CÁC ACTION CHO MEMBER
     * */

    static $ACTION_LIST = 'LIST_';      // XEM ALL DANH SÁCH
    static $ACTION_LIST_OF_ME = 'LIST_OF_ME_';   // XEM DANH SÁCH CỦA CHÍNH MÌNH
    static $ACTION_LIST_OF_NOT_ME = 'LIST_OF_NOT_ME_';   // XEM DANH SÁCH CỦA NGƯỜI KHÁC
    static $ACTION_EDIT = 'EDIT_';   // SỬA ALL VĂN BẢN
    static $ACTION_EDIT_OF_ME = 'EDIT_OF_ME_';   // SỬA VĂN BẢN CỦA CHÍNH MÌNH
    static $ACTION_EDIT_OF_NOT_ME = 'EDIT_OF_NOT_ME_';   // SỬA VĂN BẢN CỦA NGƯỜI KHÁC
    static $ACTION_DELETE = 'DELETE_';   // XÓA ALL VĂN BẢN
    static $ACTION_DELETE_OF_ME = 'DELETE_OF_ME_';   // xÓA VĂN BẢN CỦA CHÍNH MÌNH
    static $ACTION_DELETE_OF_NOT_ME = 'DELETE_OF_NOT_ME_';   // xÓA VĂN BẢN CỦA NGƯỜI KHÁC
    static $ACTION_APPROVE = 'APPROVE_';   // XÉT DUYỆT
    static $ACTION_ROLE = 'ROLE_';   // PHÂN QUYỀN
    static $ACTION_ROLE_ADMIN = 'ROLE_ADMIN';   // BỐ MÀY LÀ CHỦ TỊCH
    static $ACTION_PASSWORD = 'PASSWORD';   // tài khoản/mật khẩu
    static $ACTION_BUY = 'BUY_';   // mua gì
    static $ACTION_CHUYENNHUONG = 'CHUYENNHUONG_';   // mua gì
    static $ACTION_EDIT_IMPORTANT = 'EDIT_IMPORTANT_';

    static $_MP_ROLE_GROUP = [
        [
            'key' => 'role_admin',
            'label' => 'Toàn quyền'
        ],
        [
            'key' => 'news',
            'label' => 'Nhóm quyền tin tức, sự kiện, videos'
        ],
        [
            'key' => 'tour',
            'label' => 'Nhóm quyền quản lý sản phẩm'
        ],
        [
            'key' => 'booking',
            'label' => 'Nhóm quyền quản lý đơn booking'
        ],
        [
            'key' => 'voucher',
            'label' => 'Nhóm quyền quản lý vouchers'
        ],
        [
            'key' => 'location',
            'label' => 'Nhóm quyền quản lý địa điểm, vùng miền'
        ],
        [
            'key' => 'vehicle',
            'label' => 'Nhóm quyền quản lý phương tiện'
        ],
        [
            'key' => 'media',
            'label' => 'Nhóm quyền upload ảnh, tài liệu'
        ],
        [
            'key' => 'customer',
            'label' => 'Nhóm quyền quản lý danh sách khách hàng'
        ],
        [
            'key' => 'member',
            'label' => 'Nhóm quyền nhân sự'
        ],
        [
            'key' => 'department',
            'label' => 'Nhóm quyền phòng ban'
        ],
        [
            'key' => 'position',
            'label' => 'Nhóm quyền chức danh/chức vụ'
        ],
        [
            'key' => 'config_website',
            'label' => 'Nhóm quyền cấu hình website'
        ],
        [
            'key' => 'system',
            'label' => 'Nhóm quyền hệ thống'
        ],
    ];

    static $_MP_ROLE_GROUP_DETAILS = [
        'role_admin' => [
            ['key' => 'ROLE_ADMIN', 'text' => 'Toàn quyền (ưu tiên cao nhất)'],
        ],
        'system' => [
            ['key' => 'LIST_SYSTEM', 'text' => 'Xem logs hệ thống'],
        ],
        'news' => [
            ['key' => 'LIST_NEWS', 'text' => 'Xem danh sách tin bài/sự kiện'],
            ['key' => 'EDIT_NEWS', 'text' => 'Cập nhật tin bài/sự kiện'],
            ['key' => 'DELETE_NEWS', 'text' => 'Xóa tin bài/sự kiện'],
            ['key' => 'APPROVE_NEWS', 'text' => 'Xét duyệt tin bài/sự kiện'],
            ['key' => 'LIST_CATEGORY_NEWS', 'text' => 'Xem danh sách danh mục bài viết'],
            ['key' => 'EDIT_CATEGORY_NEWS', 'text' => 'Sửa danh mục bài viết'],
            ['key' => 'DELETE_CATEGORY_NEWS', 'text' => 'Xóa danh mục bài viết'],
            ['key' => 'LIST_VIDEOS', 'text' => 'Xem danh sách videos'],
            ['key' => 'EDIT_VIDEOS', 'text' => 'Cập nhật thông tin videos'],
            ['key' => 'DELETE_VIDEOS', 'text' => 'Xóa videos'],
            ['key' => 'APPROVE_VIDEOS', 'text' => 'Xét duyệt videos'],
            ['key' => 'LIST_CATEGORY_VIDEOS', 'text' => 'Xem danh sách danh mục videos'],
            ['key' => 'EDIT_CATEGORY_VIDEOS', 'text' => 'Sửa danh mục videos'],
            ['key' => 'DELETE_CATEGORY_VIDEOS', 'text' => 'Xóa danh mục videos'],
        ],
        'customer' => [
            ['key' => 'LIST_CUSTOMER', 'text' => 'Xem danh sách khách hàng'],
            ['key' => 'EDIT_CUSTOMER', 'text' => 'Cập nhật thông tin khách hàng'],
            ['key' => 'APPROVE_CUSTOMER', 'text' => 'Xét duyệt thông tin khách hàng'],
            ['key' => 'LIST_SUBSCRIBER', 'text' => 'Xem danh sách khách hàng liên hệ'],
            ['key' => 'DELETE_SUBSCRIBER', 'text' => 'Cập nhật thông tin khách hàng liên hệ'],
        ],
        'tour' => [
            ['key' => 'LIST_TOUR', 'text' => 'Xem danh sách tour'],
            ['key' => 'EDIT_TOUR', 'text' => 'Cập nhật thông tin tour'],
            ['key' => 'APPROVE_TOUR', 'text' => 'Xét duyệt tour'],
            ['key' => 'DELETE_TOUR', 'text' => 'Xoá tour'],
            ['key' => 'LIST_CATEGORY_TOUR', 'text' => 'Xem danh sách loại tour'],
            ['key' => 'EDIT_CATEGORY_TOUR', 'text' => 'Cập nhật thông tin loại tour'],
            ['key' => 'DELETE_CATEGORY_TOUR', 'text' => 'Xoá loại tour'],
        ],
        'booking' => [
            ['key' => 'LIST_BOOKING', 'text' => 'Xem danh sách booking'],
            ['key' => 'EDIT_BOOKING', 'text' => 'Cập nhật thông tin booking'],
            ['key' => 'APPROVE_BOOKING', 'text' => 'Xét duyệt booking'],
            ['key' => 'DELETE_BOOKING', 'text' => 'Xoá booking'],
        ],
        'voucher' => [
            ['key' => 'LIST_VOUCHER', 'text' => 'Xem danh sách vouchers'],
            ['key' => 'EDIT_VOUCHER', 'text' => 'Cập nhật thông tin vouchers'],
            ['key' => 'APPROVE_VOUCHER', 'text' => 'Xét duyệt vouchers'],
            ['key' => 'DELETE_VOUCHER', 'text' => 'Xoá vouchers'],
        ],
        'vehicle' => [
            ['key' => 'LIST_VEHICLE', 'text' => 'Xem danh sách phương tiện'],
            ['key' => 'EDIT_VEHICLE', 'text' => 'Cập nhật thông tin phương tiện'],
            ['key' => 'DELETE_VEHICLE', 'text' => 'Xoá phương tiện'],
        ],
        'location' => [
            ['key' => 'LIST_LOCATION', 'text' => 'Xem danh sách địa điểm, vùng miền'],
            ['key' => 'EDIT_LOCATION', 'text' => 'Cập nhật thông tin địa điểm, vùng miền'],
            ['key' => 'DELETE_LOCATION', 'text' => 'Xoá địa điểm, vùng miền'],
            ['key' => 'LIST_CATEGORY_LOCATION', 'text' => 'Xem danh sách loại địa điểm, vùng miền'],
            ['key' => 'EDIT_CATEGORY_LOCATION', 'text' => 'Cập nhật thông tin loại địa điểm, vùng miền'],
            ['key' => 'DELETE_CATEGORY_LOCATION', 'text' => 'Xoá loại địa điểm, vùng miền'],
        ],
        'media' => [
            ['key' => 'LIST_MEDIA', 'text' => 'Xem danh sách ảnh, tài liệu'],
            ['key' => 'EDIT_MEDIA', 'text' => 'Cập nhật, chỉnh sửa ảnh, tài liệu'],
            ['key' => 'DELETE_MEDIA', 'text' => 'Xóa ảnh, tài liệu'],
        ],
        'member' => [
            ['key' => 'LIST_MEMBER', 'text' => 'Xem danh sách nhân sự'],
            ['key' => 'EDIT_MEMBER', 'text' => 'Cập nhật thông tin nhân sự'],
            ['key' => 'LIST_OF_ME_MEMBER', 'text' => 'Xem thông tin cá nhân'],
            ['key' => 'EDIT_OF_ME_MEMBER', 'text' => 'Cập nhật thông tin cá nhân'],
            ['key' => 'DELETE_MEMBER', 'text' => 'Xóa nhân sự'],
            ['key' => 'APPROVE_MEMBER', 'text' => 'Xét duyệt nhân sự'],
            ['key' => 'EDIT_IMPORTANT_PASSWORD_MEMBER', 'text' => 'Thay đổi tài khoản mật khẩu truy cập hệ thống'],
        ],
        'department' => [
            ['key' => 'LIST_DEPARTMENT', 'text' => 'Xem phòng ban'],
            ['key' => 'EDIT_DEPARTMENT', 'text' => 'Sửa phòng ban'],
            ['key' => 'DELETE_DEPARTMENT', 'text' => 'Xóa phòng ban'],
        ],
        'position' => [
            ['key' => 'LIST_POSITION', 'text' => 'Xem chức vụ'],
            ['key' => 'EDIT_POSITION', 'text' => 'Sửa chức vụ'],
            ['key' => 'DELETE_POSITION', 'text' => 'Xóa chức vụ'],
            ['key' => 'EDIT_IMPORTANT_ROLE_MEMBER', 'text' => 'Phân quyền'],
        ],
        'config_website' => [
            ['key' => 'LIST_CONFIG_WEBSITE_MENU', 'text' => 'Xem danh sách menu'],
            ['key' => 'EDIT_CONFIG_WEBSITE_MENU', 'text' => 'Cập nhật danh sách menu'],
            ['key' => 'DELETE_CONFIG_WEBSITE_MENU', 'text' => 'Xoá menu'],
            ['key' => 'EDIT_CONFIG_WEBSITE_SOCIAL', 'text' => 'Cập nhật thông tin MXH'],
            ['key' => 'LIST_COMMENT', 'text' => 'Xem danh sách bình luận tour'],
            ['key' => 'EDIT_COMMENT', 'text' => 'Cập nhật thông tin bình luận tour'],
            ['key' => 'APPROVE_COMMENT', 'text' => 'Xét duyệt bình luận tour'],
            ['key' => 'DELETE_COMMENT', 'text' => 'Xoá bình luận tour'],
        ],

    ];

    static function isAllowTo($role_key = "")
    {

        if (self::isAdmin()) {
            return true;
        }

        $currentPosition = Member::getCurrentPosition();
        if (empty($role_key)) {
            return true;
        }

        $currentRole = collect(@$currentPosition['roles']);

        if (!$currentRole->count()) {
            return false;
        }
        $isAllow = $currentRole->first(function ($item) use ($role_key) {
            return $item == $role_key || $item ===Role::$ACTION_ROLE_ADMIN;
        });
        return $isAllow;
    }
    static $isOwn = "not-set";
    static function isMyOwn($obj)
    {
        $currentMember = Member::getCurent();

        if (Role::$isOwn !== 'not-set') {
            return Role::$isOwn;
        }
        if (!$obj) {
            Role::$isOwn = 'yes';
        }
        @$obj['created_by']['id'] == $currentMember ['_id'] ? Role::$isOwn = 'yes' : Role::$isOwn = 'no';
        return Role::$isOwn ==='yes';
    }

    static function isOwner($obj, $member_id = "")
    {
        if ($member_id) {
            $currentId = $member_id;
        } else {
            $currentId = Member::getCurentId();
        }

        if (isset($obj['created_by'])) {
            if ($obj['created_by'] == $currentId) {
                return true;
            }
        }
        if (isset($obj['created_by']['id'])) {
            if ($obj['created_by']['id'] == $currentId) {
                return true;
            }
        }
        return false;


    }
}
