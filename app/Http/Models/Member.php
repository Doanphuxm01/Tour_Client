<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\EmailHelper;
use App\Elibs\Helper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Cấu trúc khách hàng
 */
class Member extends BaseModel
{
    const table_name = 'io_customers';
    protected $table = self::table_name;
    const SESSION_KEY_FOR_CUR_MEMBER = 'clgt_session';
    const COOKIE_KEY_FOR_CUR_MEMBER = 'clgt';
    static $currentMember = [];
    static $currentPosition = [];
    static $unguarded = true;


    const ROLE_ROOT = 'root';//root
    const ROLE_ADMIN = 'admin';
    const ROLE_AUTHOR = 'author';//quyền tác giả => được viết, được sửa nhưng không được xóa
    const ROLE_CONTENT_EDITOR = 'content';//quyền quản lý nội dung
    const ROLE_MEMBER = 'member';//Thành viên đăng ký thông thường
    const ROOT_ACCOUNT = ['Khoa'];
    const VERIFIED_NO = 'VERIFIED_NO';
    const VERIFIED_YES = 'VERIFIED_YES';
    const STATUS_NO_WOKING = 'no_working';//nghỉ việc

    static $GENDER = [
        'femail' => [
            'id' => 'female',
            'name' => 'Nữ'
        ],
        'male' => [
            'id' => 'male',
            'name' => 'Nam'
        ],
    ];
    static $Select_email = [
        'yes' => [
            'id' => 'yes',
            'name' => 'Đồng ý'
        ],
        'no' => [
            'id' => 'no',
            'name' => 'Không đồng ý'
        ],
    ];
    static $_MPMemberField = [
        'code' => [
            'key' => 'code',
            'label' => 'Mã nhân sự',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'name' => [
            'key' => 'name',
            'label' => 'Họ & tên ',
            'type' => 'text',
            'groupClass' => 'col-md-12'
        ],
        'account' => [
            'key' => 'account',
            'label' => 'Tài khoản',
            'type' => 'text',
            'groupClass' => 'col-md-12'
        ],
        'email' => [
            'key' => 'email',
            'label' => 'Email',
            'type' => 'text',
            'groupClass' => 'col-md-12'
        ],
        'phone' => [
            'key' => 'phone',
            'label' => 'Số điện thoại',
            'type' => 'text',
            'groupClass' => 'col-md-12'
        ],
        'created_at' => [
            'key' => 'created_at',
            'label' => 'Thời gian tạo',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'created_by' => [
            'key' => 'created_by',
            'label' => 'Ngườitạo',
            'type' => 'text',
            'groupClass' => 'col-md-12',
            'children' => [
                'id' => [
                    'key' => 'id',
                    'label' => 'ID người tạo',
                    'type' => 'text',
                    'groupClass' => 'col-md-6'
                ],
                'name' => [
                    'key' => 'name',
                    'label' => 'Tên người tạo',
                    'type' => 'text',
                    'groupClass' => 'col-md-6'
                ],
                'account' => [
                    'key' => 'account',
                    'label' => 'Tài khoản người tạo',
                    'type' => 'text',
                    'groupClass' => 'col-md-6'
                ],
                'email' => [
                    'key' => 'email',
                    'label' => 'Email người tạo',
                    'type' => 'text',
                    'groupClass' => 'col-md-6'
                ],
                'phone' => [
                    'key' => 'phone',
                    'label' => 'Số điện thoại người tạo',
                    'type' => 'text',
                    'groupClass' => 'col-md-6'
                ],
            ]
        ],

        'status' => [
            'key' => 'status',
            'label' => 'Trạng thái đơn',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
    ];

    /**
     * Customer schema
     */


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    static function isLogin()
    {
        return self::$currentMember;
    }

    static function isContentAuthor()
    {
        if (!self::$currentMember) {
            return false;
        }
        if (self::isAdmin()) {
            return true;
        }
        if (in_array(self::$currentMember['role'], [self::ROLE_CONTENT_EDITOR, self::ROLE_AUTHOR])) {
            return true;
        }
    }

    static function isContentEditor()
    {
        return true;
        if (!self::$currentMember) {
            return false;
        }
        if (in_array(self::$currentMember['role'], [self::ROLE_CONTENT_EDITOR, self::ROLE_ADMIN, self::ROLE_ROOT])) {
            return true;
        }
    }

    static function setLogin($member)
    {
        if (!is_array($member)) {
            if (!$member) {
                Helper::delSession(Member::SESSION_KEY_FOR_CUR_MEMBER);
                Helper::delCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER);
                return Redirect(admin_link('/'));
            }
            $member = $member->toArray();
            if (isset($member['extra']) && $member['extra']) {
                $member['extra'] = json_decode($member['extra'], 1);
            }
        }
        if (isset($member['projects'])) {
            $project = [];
            foreach ($member['projects'] as $item) {
                $project[$item['id']] = $item;
            }
            $member['projects'] = $project;
        }
        Helper::setSession(Member::SESSION_KEY_FOR_CUR_MEMBER, $member);
        Helper::setCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER, $member['account'] . ':' . md5($member['account'] . 'ngannv'));
    }

    static function setLogOut()
    {
        Helper::delSession(Member::SESSION_KEY_FOR_CUR_MEMBER);
        Helper::delCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER);
    }

    /***
     *
     * @param $account
     *
     * @return array
     */
    static function getMemberByAccount($account)
    {
        if (!$account) {
            return [];
        }
        $where = [
            'account' => $account,
        ];
        $member = static::where($where)->first();
        return $member;
    }

    /***
     *
     * @param $email
     *
     * @return array
     */
    static function getMemberByEmail($email)
    {
        if (!$email) {
            return [];
        }
        $where = [
            'email' => $email,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    /***
     *
     * @param $phone
     *
     * @return array
     */
    static function getMemberByPhone($phone)
    {
        if (!$phone) {
            return [];
        }
        $where = [
            'phone' => $phone,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    /***
     *
     * @param $password
     *
     * @return mixed
     * @note:  gen mật khẩu
     */
    static function encodePassword($password)
    {
        return hash_hmac('sha256', $password, 'ngannv-techhandle-sakura');
    }

    static function genPassSave($password)
    {
        return hash_hmac('sha256', $password, 'ngannv-techhandle-sakura');
        //return Hash::make(self::encodePassword($password));
    }

    /**
     * @return array
     * dùng lưu vào db các chỗ created_by
     */
    static function getCreatedByToSaveDb()
    {
        return [
            'id'      => Member::getCurentId(),
            'name'    => Member::getCurrentName(),
            'account' => Member::getCurentAccount(),
            'email' => Member::getCurrentEmail(),
        ];
    }

    static function getApprovedByToSaveDb()
    {
        return [
            'id'      => Member::getCurentId(),
            'name'    => Member::getCurrentName(),
            'account' => Member::getCurentAccount(),
            'email' => Member::getCurrentEmail(),
            'created_at' => Helper::getMongoDateTime(),
        ];
    }

    static function getApprovedBy($obj)
    {
        return [
            'name' => @$obj['approved_by']['name']?:$obj['updated_by']['name'],
            'account' => @$obj['approved_by']['account']?:$obj['updated_by']['account'],
            'email' => @$obj['approved_by']['email']?:$obj['updated_by']['email'],
            'created_at' => @$obj['approved_by']['created_at']?:$obj['updated_at'],
        ];
    }

    static function setCurent($curent = [])
    {
        if ($curent) {
            self::$currentMember = $curent;
        } else {
            self::$currentMember = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER);
        }
    }

    static function getCurent()
    {
        if (self::$currentMember) {
            return self::$currentMember;
        } else {

            self::$currentMember = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER);
            if (isset(self::$currentMember['_id'])) {
                $member = Member::find(self::$currentMember['_id']);
                //dump(session()->all(), Member::getCurent());
                self::setLogin($member);
            }
            //return self::$currentMember = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER);
        }
    }

    static function getCurentId()
    {
        if(!isset(self::$currentMember['_id'])) {
            return redirect(route('AuthGate', ['action' => 'logout']));
        }
        return self::$currentMember['_id'];
    }

    static function getCurentCode()
    {
        return self::$currentMember['code'];
    }

    static function getCurentAccount()
    {
        return @self::$currentMember['account'];
    }

    static function getCurrentEmail()
    {
        return @self::$currentMember['email'];
    }

    static function getCurrentName()
    {
        return @self::$currentMember['name'];
    }

    /**
     *
     * @param  array $rolesAllow
     * @return bool
     */
    static function haveRole($rolesAllow = [])
    {
        if (Member::isRoot()) {
            return true;
        }
        if (!$rolesAllow) {
            return false;
        }
        if (!is_array($rolesAllow)) {
            $rolesAllow = array($rolesAllow);
        }
        if (!isset(self::$currentMember['roles']) || !is_array(self::$currentMember['roles'])) {
            return false;
        }
        if (count($rolesAllow) < count(self::$currentMember['roles'])) {
            $roleSub = $rolesAllow;
            $roleParent = self::$currentMember['roles'];
        } else {
            $roleSub = self::$currentMember['roles'];
            $roleParent = $rolesAllow;
        }
        if (array_intersect($roleSub, $roleParent) == $roleSub) {
            return true;
        }

        return false;

    }

    static function haveAccessProject($project)
    {
        return true;
        if (self::isAdmin()) {
            return true;
        }
        if (isset($project['_id'])) {
            $project_id = $project['_id'];
        } else {
            $project_id = $project;
        }
        return (isset(self::$currentMember['projects']) && isset(self::$currentMember['projects'][$project_id]));
    }

    static function isAdmin()
    {
        return self::isRoot();
    }

    static function isRoot($account = false)
    {
        if($account) {
            return in_array($account, Member::ROOT_ACCOUNT);
        }elseif ($account == null) {
            return false;
        }

        if (!self::$currentMember) {
            return false;
        }

        return in_array(self::$currentMember['account'], Member::ROOT_ACCOUNT);
    }

    static function getCurrentPosition()
    {
        $currentMember = self::getCurent();
        if (!@$currentMember['position']['id']) {
            self::$currentPosition = null;
        }
        if (empty(self::$currentPosition) && !is_null(self::$currentPosition)) {
            self::$currentPosition = Position::where('_id', $currentMember['position']['id'])->first();
        }

        return self::$currentPosition;
    }


    static function getListRole()
    {
        return Role::getListRole();
    }

    /**
     * Kiểm tra, validate thông tin của công nhân viên
     *
     * @param String|String[] $id là id Bài viết
     *
     * @return array ["msg" =>"Tin nhắn validate", "valid" =>Boolean]
     */
    static function validate_tab_info(&$obj)
    {
        $ret = [
            "valid" => false,
            "msg" => '',
        ];
        if (!isset($obj['info'])) {
            $ret['msg'] = "Bạn chưa nhập thông tin nào cả";
            return $ret;
        }

        /*parse lại dữ liệu dạng mảng*/
        foreach ($obj['info'] as $key => $item) {
            if (is_array($item)) {
                $converted = [];
                foreach ($item as $key_ => $val_) {
                    foreach ($val_ as $key__ => $val__) {
                        /*$key__ là số thứ tự*/
                        /*$key_ là số trường*/
                        /*{key1: [1,2,3], key2:[5,6,7]} => {1:{key1: 1, key2: 5}, 2: {......}}*/
                        $converted[$key__][$key_] = $val__;
                    }

                }
                $obj['info'][$key] = $converted;
            }
        }
        /*xoá nhưng thông tin rỗng trong mảng*/
        foreach ($obj['info'] as $key => $item) {
            if (is_array($item)) {
                $temp = array_filter($item, function ($item_) {
                    foreach ($item_ as $item__) {
                        if ($item__ != "") {
                            return true;
                        }
                    }
                    return false;
                });
                $obj['info'][$key] = $temp;
            }
        }
        /*validate*/
        $schema_tab_info = self::$schema['info'];

        foreach ($schema_tab_info as $field) {
            $field_key = $field['key'];
            $field_text = $field['text'];
            $field_type = $field['type'];
            if (isset($obj['info'][$field_key])) {
                $value = $obj['info'][$field_key];
                if ($field_type == 'date') {
                    if ($value && !strtotime($value)) {
                        $ret['msg'] = "Dữ liệu $field_text: \"$value\" phải là $field_type";
                        return $ret;
                    } else if (!$value) {
                        $obj['info'][$field_key] = "";
                    } else {
                        $obj['info'][$field_key] = strtotime($value);
                    }
                }
                if ($field_type == 'multi' && $value) {
                    $obj['info'][$field_key] = explode(',', $obj['info'][$field_key]);
                }
            }

        }

        $ret = [
            'msg' => 'ok',
            'valid' => true
        ];

        return $ret;
    }


    static function getFieldTypeTabInfo($field_key, $child_field_key)
    {
        $infoSchema = self::$schema['info'];
        $type = '';
        if ($field_key) {
            $temp = array_first($infoSchema, function ($item) use ($field_key) {
                return $item['key'] == $field_key;
            });
            if ($temp) {
                $type = $temp['type'];
            }
        }
        if (isset($temp['rows']) && $child_field_key) {
            $type = '';
            $temp = array_first($temp['rows'], function ($item) use ($field_key) {
                return $item['key'] == $field_key;
            });
            if ($temp) {
                $type = $temp['type'];
            }
        }

        return $type;

    }

    const field_name = [
        'code' => 'Mã nhân viên',
        'department' => 'Phòng ban đang công tác',
        'position' => 'Vị trí đảm nhiệm',
        'name' => 'Tên',
        'account' => 'Tài khoản',
        'gender' => 'Giới tinh',
        'date_of_birth' => 'Ngày sinh',
        'noi_sinh' => 'Nơi sinh',
        'role_group' => 'Nhóm quyền',
        'nguyen_quan' => 'Nguyên quán',
        'dan_toc' => 'Dân tộc',
        'ton_giao' => 'Tôn giáo',
        'quoc_tich' => 'Quốc tịch',
        'ma_so_thue' => 'Mã số thuế',
        'tinh_trang_hon_nhan' => 'Tình trạng hôn nhân',
        'tien_an_tien_su' => 'Tiền án tiền sự',
        'so_bhxh' => 'Số BHXH',
        'ngoai_ngu' => 'Ngoại ngữ',
        'ho_khau_thuong_chu' => 'Hộ khẩu thường trú',
        'emails' => 'Các email',
        'phones' => 'Các số điện thoại',
        'tinh_trang_cong_viec' => 'Tình trạng công việc',
//        'department' => 'Phòng ban hiện tại',
        'chuc_vu_hien_tai' => 'Chức vụ hiện tại',
        'giay_to' => 'Giấy tờ',
        'tk_ngan_hang' => 'Tài khoản ngân hàng',
        'to_chuc_doan_the' => 'Tổ chức đoàn thể',
        'lien_he_khac' => 'Liên hệ khác',
        'files_thong_tin_co_ban' => 'Các file đính kèm',
        'files_thong_tin_gia_dinh' => 'Các file đính kèm',
        'files_thong_tin_dao_tao' => 'Các file đính kèm',
        'files_thong_tin_cong_viec' => 'Các file đính kèm',
    ];

    static function getFieldName($key)
    {
        if (isset(self::field_name[$key])) {
            return self::field_name[$key];
        } else {
            return $key;
        }
    }

    static function getAllMember()
    {
        return self::orderBy('_id', 'desc')->get()->keyBy('_id')->toArray();
    }

    static function getMemberByCanCuocCongDan($cancuoccongdan)
    {
        if (!$cancuoccongdan) {
            return [];
        }
        $where = [
            'can_cuoc_cong_dan' => $cancuoccongdan,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    static function getGender($gender){
        if($gender=='nam'){
            return [
                'label'=>'Nam',
            ];
        }
        return [
            'label'=>'Nữ',
        ];
    }

    static function createMember($obj) {
        $id = self::insertGetId($obj);
        $customer = self::find($id)->toArray();
        /*Sendmail*/
        /*if (!empty($customer['email']) && 1 == 2) {
            $tpl['success'] = true;
            $tpl['code'] = @$obj['code'];
            $tpl['name'] = 'Xác thực địa chỉ email';
            $tpl['tokenString'] = Helper::buildTokenString($id);
            $tpl['url'] = public_link('auth/verifyEmail?uid=' . $id . '&token=' . Helper::buildTokenString($id));
            $tpl['subject'] = '[Hệ thống quản lý Vietrantour] Yêu cầu xác thực tài khoản';
            $tpl['template'] = "mail.verified_account";
            EmailHelper::sendMail($customer['email'], $tpl);
        }*/
        return $customer;
    }
}

Member::getCurent();
