<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use App\Elibs\Debug;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\eView;

class Agency extends BaseModel
{
    public $timestamps = FALSE;
    const table_name        = 'io_agency';
    protected $table              = self::table_name;
    static    $unguarded          = TRUE;
    static    $basicFiledsForList = ['name', 'alias', 'city', 'district', 'town', 'street', 'agency', 'member', 'created_at', 'updated_at', 'actived_at'];
    // protected $dates              = [];

    // trang thai
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const AGENCY_TRA_HANG  = 'dai-ly-tra-hang';
    const AGENCY_TRA_HANG_CAP_TINH  = 'dai-ly-tra-hang-cap-tinh';
    const AGENCY_TRA_HANG_CAP_HUYEN  = 'dai-ly-tra-hang-cap-huyen';
    const AGENCY_UY_QUYEN  = 'dai-ly-uy-quyen';
    const AGENCY_MP_MART   = 'dai-ly-mp-mart';
    const AGENCY_MPG   = 'cong-ty-mpg';
    // const AGENCY_CAP_TINH  = 'dai-ly-cap-tinh';

    static $objectAgency = [

        self::AGENCY_TRA_HANG_CAP_TINH => [
            'key' => self::AGENCY_TRA_HANG_CAP_TINH,
            'name' => 'Đại lý trả hàng cấp tỉnh',
            'style' => 'primary',
            'icons' => 'success',
        ],
        self::AGENCY_TRA_HANG_CAP_HUYEN => [
            'key' => self::AGENCY_TRA_HANG_CAP_HUYEN,
            'name' => 'Đại lý trả hàng cấp huyện',
            'style' => 'warning',
            'icons' => 'warning',
        ],
        self::AGENCY_UY_QUYEN => [
            'key' => self::AGENCY_UY_QUYEN,
            'name' => 'Đại lý ủy quyền',
            'style' => 'teal-400',
            'icons' => 'teal',
        ],
        self::AGENCY_MP_MART => [
            'key' => self::AGENCY_MP_MART,
            'name' => 'Siêu thị MP Mart',
            'style' => 'yellow',
            'hex' => '#FFC120',
            'icons' => 'icon-star-empty3',
        ],
    ];

    static function getListAgency($selected = FALSE, $status = FALSE)
    {
        $listStatus = self::$objectAgency;
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }elseif($status !== FALSE) {
            if(isset($listStatus[$status])) {
                return $listStatus[$status];
            }
            return false;
        }

        return $listStatus;
    }

    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Hoạt Động', 'text-action' => 'Hoạt Động'],
            // self::STATUS_INACTIVE => ['id' => self::STATUS_INACTIVE, 'style' => 'secondary', 'text' => 'Chờ kích hoạt', 'text-action' => 'Chờ kích hoạt'],
            self::STATUS_DISABLE => ['id' => self::STATUS_DISABLE, 'style' => 'warning', 'text' => 'Khóa', 'text-action' => 'Khóa'],
        ];

        if($selected && !isset($listStatus[$selected])) {
            return false;
        }
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }

        return $listStatus;
    }

    public static function _insert($data){
        if($data['member']){
            $member = Customer::where('_id',$data['member'])->first();
        }
        $get = [];
        if(isset($member)){
            $get = [
                'name' => $data['name']?? '',
                'agency' => $data['agency']?? '',
                'member' => [
                    'name' => (isset($member['name'])) ? $member['name'] : '',
                    'account'=>(isset($member['account']) ? $member['account'] : ''),
                    'email'=>(isset($member['email']) ? $member['email'] : ''),
                    'id'=>(isset($member['id']) ? $member['id'] : ''),
                    'phone' =>(isset($member['phone']) ? $member['phone'] : ''),
                    'code' =>(isset($member['code']) ? $member['code'] : ''),
                ],
                'street' => $data['street']?? '',
                'city' => $data['city']?? '',
                'district' => $data['district']?? '',
                'dai_ly_tra_hang' => ($data['trahang']) ??'',
                'town' => $data['town']?? '',
                'alias' => str_slug($data['name'])?? '',
                'status'    => BaseModel::STATUS_ACTIVE,
                'created_at' => Helper::getMongoDateTime(),
                'created_by' => Member::getCreatedByToSaveDb()
            ];
            return $get;
        }else{
            return eView::getInstance()->getJsonError("xin lỗi đối tượng không được áp dụng mới bạn chọn lại");
        }
    }
    public static function _edit($data){
        if($data['member']){
            $member = Customer::where('_id',$data['member'])->first();
        }
        if(isset($member)){
        $get = [
            'name' => $data['name']?? '',
            'agency' => $data['agency']?? '',
            'member' => [
                'name' => (isset($member['name'])) ? $member['name'] : '',
                'account'=>(isset($member['account']) ? $member['account'] : ''),
                'email'=>(isset($member['email']) ? $member['email'] : ''),
                'id'=>(isset($member['id']) ? $member['id'] : ''),
                'phone' =>(isset($member['phone']) ? $member['phone'] : ''),
                'code' =>(isset($member['code']) ? $member['code'] : ''),
            ],
            'street' => $data['street']?? '',
            'city' => $data['city']?? '',
            'district' => $data['district']?? '',
            'dai_ly_tra_hang' => ($data['trahang']) ??'',
            'town' => $data['town']?? '',
            'alias' => str_slug($data['name'])?? '',
            'status'    => BaseModel::STATUS_ACTIVE,
            'updated_by' => Member::getCreatedByToSaveDb(),
            'updated_at' => Helper::getMongoDate(),
        ];
            return $get;
        }else{
            return eView::getInstance()->getJsonError("xin lỗi đối tượng không được áp dụng mới bạn chọn lại");
        }

    }
    public static function _editNot($data){
        if($data['member']){
            $member = Customer::where('_id',$data['member'])->first();
        }
        if(isset($member)){
            $get = [
                'name' => $data['name'] ?? '',
                'agency' => $data['agency']?? '',
                'member' => [
                    'name' => (isset($member['name'])) ? $member['name'] : '',
                    'account'=>(isset($member['account']) ? $member['account'] : ''),
                    'email'=>(isset($member['email']) ? $member['email'] : ''),
                    'id'=>(isset($member['id']) ? $member['id'] : ''),
                    'phone' =>(isset($member['phone']) ? $member['phone'] : ''),
                    'code' =>(isset($member['code']) ? $member['code'] : ''),
                ],
                'street' => $data['street']?? '',
                'city' => $data['city']?? '',
                'district' => $data['district']?? '',
                'town' => $data['town']?? '',
                'alias' => str_slug($data['name'])?? '',
                'status'    => BaseModel::STATUS_ACTIVE,
                'updated_by' =>Member::getCreatedByToSaveDb(),
                'updated_at' => Helper::getMongoDate(),
            ];
            return $get;
        }else{
            return eView::getInstance()->getJsonError("xin lỗi đối tượng không được áp dụng mới bạn chọn lại");
        }

    }
    public static function _insertNot($data){
        if($data['member']){
            $member = Customer::where('_id',$data['member'])->first();
        }
        if(isset($member)){
            $get = [
                'name' => $data['name']?? '',
                'agency' => $data['agency']?? '',
                'member' => [
                    'name' => (isset($member['name'])) ? $member['name'] : '',
                    'account'=>(isset($member['account']) ? $member['account'] : ''),
                    'email'=>(isset($member['email']) ? $member['email'] : ''),
                    'id'=>(isset($member['id']) ? $member['id'] : ''),
                    'phone' =>(isset($member['phone']) ? $member['phone'] : ''),
                    'code' =>(isset($member['code']) ? $member['code'] : ''),
                ],
                'street' => $data['street']?? '',
                'city' => $data['city']?? '',
                'district' => $data['district']?? '',
                'town' => $data['town']?? '',
                'alias' => str_slug($data['name'])?? '',
                'status'    => BaseModel::STATUS_ACTIVE,
                'created_at' => Helper::getMongoDateTime(),
                'created_by' => Member::getCreatedByToSaveDb()
            ];
            return $get;
        }else{
            return eView::getInstance()->getJsonError("xin lỗi đối tượng không được áp dụng mới bạn chọn lại");
        }
        

    }

    static function getLsAgencyByIdCityNeIdAgency($id_city, $id_agency) {
        if(!$id_city) {
            return false;
        }
        $where = [
            'status' => self::STATUS_ACTIVE,
            'city.id' => $id_city,
            'dai_ly_tra_hang' => self::AGENCY_TRA_HANG_CAP_TINH,
            'is_cty' => [
                '$exists' => false
            ],
            'id' => [
                '$ne' => $id_agency
            ],
        ];
        return self::where($where)->get()->toArray();
    }

    static function getLsAgencyByIdCustomer($id_customer) {
        if(!$id_customer) {
            return false;
        }
        $where = [
            'status' => self::STATUS_ACTIVE,
            'member.account' => $id_customer,
        ];
        return self::where($where)->get()->toArray();
    }

    public function agencyRoot(){
        $id = '5f101b39c9f38f3bb1694793';
        $member = Customer::where('_id',$id)->first();
        if(isset($member)){
            $get = [];
            $get = [
                'name' => 'Công Ty Minh Phúc Group',
                'agency' => self::AGENCY_TRA_HANG,
                'dai_ly_tra_hang' => 'captinh',
                'is_cty' => true,
                'member' => [
                    'name' => (isset($member['name'])) ? $member['name'] : '',
                    'account'=>(isset($member['account']) ? $member['account'] : ''),
                    'email'=>(isset($member['email']) ? $member['email'] : ''),
                    'id'=>(isset($member['id']) ? $member['id'] : ''),
                    'phone' =>(isset($member['phone']) ? $member['phone'] : ''),
                    'code' =>(isset($member['code']) ? $member['code'] : ''),
                ],
                'street' => 'BT2-3 Khu Đô Thị Văn Khê, La Khê, Hà Đông, Hà Nội',
                'city' => 'Thành Phố Hà Nội',
                'district' => 'Quận Hà Đông',
                'town' => 'Phường La Khê',
                'alias' => str_slug($member['name']),
                'status'    => BaseModel::STATUS_ACTIVE,
                'created_at' => Helper::getMongoDateTime(),
            ];
            self::insertGetId($get);
        }else{
            return eView::getInstance()->getJsonError("xin lỗi đối tượng không được  mới bạn chọn lại");
        }
    }

    static function getFieldName($key)
    {
        if (isset(self::field_name[$key])) {
            return self::field_name[$key];
        } else {
            return $key;
        }
    }

}
