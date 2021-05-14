<?php


namespace App\Http\Models;


use App\Elibs\Helper;

class Transaction extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_transaction';
    protected $table = self::table_name;
    static $unguarded = true;

    const THUNHAP_THUNHAP  = 'THUNHAP_THUNHAP';
    const HOAHONG_DOANHTHU  = 'HOAHONG_DOANHTHU';
    const HOAHONG_VICOTUC  = 'HOAHONG_VICOTUC';
    const BUYCOPHAN_COPHAN  = 'BUYCOPHAN_COPHAN';
    const BUYCOPHAN  = 'BUYCOPHAN';
    const BUYSANPHAM  = 'BUYSANPHAM';
    const BUYCOPHAN_SANPHAM  = 'BUYCOPHAN_SANPHAM';
    const GIAODICHHANGNGAY  = 'GIAODICHHANGNGAY';
    const VICOTUC  = 'VICOTUC';
    const VICOPHAN  = 'VICOPHAN';
    const VICHIETKHAU  = 'VICHIETKHAU';
    const VIHOAHONG  = 'VIHOAHONG';
    const VITIEUDUNG  = 'VITIEUDUNG';
    const VITICHLUY  = 'VITICHLUY';

    const DIEM_TIEUDUNG = 'DIEM_TIEUDUNG';
    const DIEM_HOAHONG = 'DIEM_HOAHONG';
    const DIEM_CHIETKHAU = 'DIEM_CHIETKHAU';
    const DIEM_CONGNO = 'DIEM_CONGNO';
    const KICHHOATBYCODE = 'KICHHOATBYCODE';

    const KHODIEM_TIEUDUNG = 'KHODIEM_TIEUDUNG';
    const KHODIEM_HOAHONG = 'KHODIEM_HOAHONG';
    const AUTO_THANHTOANCOTUC = 'AUTO_THANHTOANCOTUC';
    const CHIETKHAU_TICHLUY = 'CHIETKHAU_TICHLUY';
    const CHIETKHAU_TIEUDUNG = 'CHIETKHAU_TIEUDUNG';
    const THUHOIHOAHONG = 'THUHOIHOAHONG';

    static $objectViRegister = [
        self::VICOTUC => [
            'key' => self::VICOTUC,
            'name' => 'Ví cổ tức',
        ],self::VICOPHAN => [
            'key' => self::VICOPHAN,
            'name' => 'Ví cổ phần',
        ],self::VICHIETKHAU => [
            'key' => self::VICHIETKHAU,
            'name' => 'Ví chiết khấu',
        ],self::VIHOAHONG => [
            'key' => self::VIHOAHONG,
            'name' => 'Ví hoa hồng',
        ],self::VITIEUDUNG => [
            'key' => self::VITIEUDUNG,
            'name' => 'Ví tiêu dùng',
        ],self::GIAODICHHANGNGAY => [
            'key' => self::GIAODICHHANGNGAY,
            'name' => 'hàng ngày',
        ],
    ];

    static $objectRegister = [
        self::HOAHONG_VICOTUC => [
            'key' => self::HOAHONG_VICOTUC,
            'name' => 'Hoa Hồng -> Ví cổ tức',
        ],self::BUYCOPHAN_COPHAN => [
            'key' => self::BUYCOPHAN_COPHAN,
            'name' => 'Mua cổ phần -> Ví cổ phần',
        ],self::BUYSANPHAM => [
            'key' => self::BUYSANPHAM,
            'name' => 'Mua sản phẩm',
        ],
    ];

    static $_MPTransactionField = [
        'created_by' => [
            'key' => 'created_by',
            'label' => 'Người tạo đơn',
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
        'tai_khoan_nguon' => [
            'key' => 'tai_khoan_nguon',
            'label' => 'Tài khoản nguồn',
            'type' => 'text',
            'groupClass' => 'col-md-12',
            'children' => [
                'id' => [
                    'key' => 'id',
                    'label' => 'ID',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'name' => [
                    'key' => 'name',
                    'label' => 'Họ & tên người giao dịch ',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'account' => [
                    'key' => 'account',
                    'label' => 'Tài khoản người giao dịch',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'email' => [
                    'key' => 'email',
                    'label' => 'Email người giao dịch',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'phone' => [
                    'key' => 'phone',
                    'label' => 'Số điện thoại người giao dịch',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
            ]
        ],
        'tai_khoan_nhan' => [
            'key' => 'tai_khoan_nhan',
            'label' => 'Tài khoản hưởng',
            'type' => 'text',
            'groupClass' => 'col-md-6',
            'children' => [
                'id' => [
                    'key' => 'id',
                    'label' => 'ID',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'name' => [
                    'key' => 'name',
                    'label' => 'Họ tên người hưởng',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'account' => [
                    'key' => 'account',
                    'label' => 'Tài khoản người hưởng',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'email' => [
                    'key' => 'email',
                    'label' => 'Email người hưởng',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
                'phone' => [
                    'key' => 'phone',
                    'label' => 'Số điện thoại người hưởng',
                    'type' => 'text',
                    'groupClass' => 'col-md-12'
                ],
            ]
        ],
        'created_at' => [
            'key' => 'created_at',
            'label' => 'Thời gian giao dịch',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'status' => [
            'key' => 'status',
            'label' => 'Trạng thái giao dịch',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'type_giaodich' => [
            'key' => 'type_giaodich',
            'label' => 'Loại giao dịch',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'vi_nhan_tien' => [
            'key' => 'vi_nhan_tien',
            'label' => 'Ví nhận tiền',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'so_cophan_giao_dich' => [
            'key' => 'so_cophan_giao_dich',
            'label' => 'Số cổ phần giao dịch',
            'type' => 'number',
            'groupClass' => 'col-md-12',
        ],
        'so_tien_giao_dich' => [
            'key' => 'so_tien_giao_dich',
            'label' => 'Số tiền giao dịch',
            'type' => 'number',
            'groupClass' => 'col-md-12',
        ],
        'order_id' => [
            'key' => 'order_id',
            'label' => 'ID đơn hàng',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],

    ];

    /***
     * @param bool|FALSE $selected
     * @return array
     * @note: Định nghĩa và Lấy danh sách các trạng thái của danh mục trong bảng
     */
    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::HOAHONG_VICOTUC => ['id' => self::HOAHONG_VICOTUC, 'style' => 'pink', 'text' => 'Hoa hồng -> Cổ tức'],
            self::HOAHONG_DOANHTHU => ['id' => self::HOAHONG_DOANHTHU, 'style' => 'success', 'text' => 'Hoa hồng doanh thu'],
            self::BUYCOPHAN_SANPHAM => ['id' => self::BUYCOPHAN_SANPHAM, 'style' => 'warning', 'text' => 'Đơn mua Cổ Phần & Sản Phẩm'],
            self::BUYCOPHAN_COPHAN => ['id' => self::BUYCOPHAN_COPHAN, 'style' => 'warning', 'text' => 'Đơn mua Cổ Phần'],
            self::BUYSANPHAM => ['id' => self::BUYSANPHAM, 'style' => 'success', 'text' => 'Đơn mua Sản Phẩm'],
            self::BUYCOPHAN => ['id' => self::BUYCOPHAN, 'style' => 'success', 'text' => 'Đơn mua Cổ Phần'],
            self::KHODIEM_HOAHONG => ['id' => self::KHODIEM_HOAHONG, 'style' => 'info', 'text' => 'Hoa hồng khi giao hàng'],
            self::CHIETKHAU_TIEUDUNG => ['id' => self::CHIETKHAU_TIEUDUNG, 'style' => 'info', 'text' => 'Chiết khấu -> Tiêu dùng'],
            self::CHIETKHAU_TICHLUY => ['id' => self::CHIETKHAU_TICHLUY, 'style' => 'danger', 'text' => 'Chiết khấu -> Tích luỹ'],
            self::THUHOIHOAHONG => ['id' => self::THUHOIHOAHONG, 'style' => 'success', 'text' => 'Thu hồi hoa hồng'],
        ];
        $listStatus = array_merge(MetaData::$orderTypeRegister, $listStatus);
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
            if($return) {
                return $listStatus[$selected];
            }
        }

        return $listStatus;
    }

    static function getTransactionNotUpdatedByType($type, $object, $keyBy = false) {
        $now = Helper::getMongoDate('d/m/Y');
        $where = [
            'object' => $object,
            'type_giaodich' => $type,
            '$or' => [
                [
                    'updated_vi_at' => ['$exists' => false],
                ],
                [
                    'updated_vi_at' => ['$lt' => $now],
                ]
            ],
        ];
        if($keyBy) {
            return self::where($where)->get()->keyBy($keyBy)->toArray();
        }
        return self::where($where)->get()->toArray();
    }

    static function getTransactionToSave($data) {
        return $objTransactionChietKhauToSave = [
            'created_by' => @$data['created_by'],
            'created_at' => Helper::getMongoDate(),
            'status' => @$data['status']??Transaction::STATUS_ACTIVE,
            'type_giaodich' => $data['type_giaodich'],
            'object' => $data['type_giaodich'],
            'diem_da_nhan' => $data['diem_da_nhan'],
            'tai_khoan_nguon' => $data['tai_khoan_nguon'],
            'tai_khoan_nhan' => $data['tai_khoan_nhan'],
            'order_id' => @$data['order_id'],
        ];
    }

    static function createTransaction($objToSave) {
        $objToSave['status'] = self::STATUS_ACTIVE;
        $objToSave['created_at'] = Helper::getMongoDate();
        return Transaction::insertGetId($objToSave);
    }
}