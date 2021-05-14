<?php


namespace App\Http\Models;


use App\Elibs\eView;

class PurchaseOrder extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_purchase_orders';
    protected $table = self::table_name;
    static $unguarded = true;
    const STATUS_NO_PAID = 'no_paid'; // chưa thanh toán
    const STATUS_PENDING = 'pending'; // đang xử lý
    const STATUS_READY_SHIP = 'ready_ship'; // sẵn sàng giao hàng
    const STATUS_SHIPED = 'shiped'; // đang giao hàng
    const STATUS_DELIVERED = 'delivered'; // đã giao hàng
    const STATUS_CANCELLED = 'cancelled'; // đã hủy do khách ko đặt nữa, chỉ đc hủy khi chưa giao hàng
    const STATUS_RETURNED = 'returned'; // khách feedback trả lại khi hàng đã giao do ko đúng yêu cầu
    const STATUS_FAIL_DELIVERY = 'fail_delivery'; // Giao hàng ko thành công (do thay đổi địa chỉ hoặc người nhận boom)
    const STATUS_DELETED = 'deleted'; // đã xóa

    const PAYMENT_VITIEUDUNG = 'payment_vitieudung'; // khách feedback trả lại khi hàng đã giao do ko đúng yêu cầu
    const PAYMENT_KHODIEM = 'payment_khodiem'; // khách feedback trả lại khi hàng đã giao do ko đúng yêu cầu


    static function getListStatus($selected = FALSE, $status = FALSE)
    {
        $listStatus = [
            /*self::STATUS_NO_PAID => [
                'id' => self::STATUS_NO_PAID, 'alias' => 'no-paid',
                'style' => 'secondary',
                'icon' => 'icon-file-plus',
                'text' => 'Đơn mới',
                'text-action' => 'Đơn mới',
                'group-action' => [
                    self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_DELETED
                ]
            ],*/
            self::STATUS_PENDING => [
                'id' => self::STATUS_PENDING, 'alias' => 'pending',
                'style' => 'warning',
                'icon' => 'icon-file-download',
                'text' => 'Đang xử lý',
                'text-action' => 'Đang xử lý',
                'group-action' => [
                    /*self::STATUS_NO_PAID,*/ self::STATUS_READY_SHIP, self::STATUS_CANCELLED, self::STATUS_DELETED
                ]
            ],
            self::STATUS_READY_SHIP => [
                'id' => self::STATUS_READY_SHIP, 'alias' => 'ready_ship',
                'style' => 'info',
                'icon' => 'icon-file-check',
                'text' => 'Sẵn sàng giao hàng',
                'text-action' => 'Sẵn sàng giao hàng',
                'group-action' => [
                    /*self::STATUS_NO_PAID,*/ self::STATUS_SHIPED, self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_DELETED
                ]
            ],
            self::STATUS_SHIPED => [
                'id' => self::STATUS_SHIPED, 'alias' => 'shiped',
                'style' => 'info',
                'icon' => ' icon-truck',
                'text' => 'Đang giao hàng',
                'text-action' => 'Đang giao hàng',
                'group-action' => [
                    self::STATUS_FAIL_DELIVERY, self::STATUS_DELIVERED, self::STATUS_CANCELLED, self::STATUS_DELETED
                ]
            ],
            self::STATUS_DELIVERED => [
                'id' => self::STATUS_DELIVERED, 'alias' => 'delivered',
                'style' => 'info',
                'icon' => 'icon-checkmark4',
                'text' => 'Đã giao hàng',
                'text-action' => 'Đã giao hàng',
                'group-action' => [
                    self::STATUS_RETURNED, self::STATUS_DELETED
                ]
            ],
            self::STATUS_CANCELLED => [
                'id' => self::STATUS_CANCELLED, 'alias' => 'cancelled',
                'style' => 'danger',
                'icon' => 'icon-cancel-circle2',
                'text' => 'Đã hủy',
                'text-action' => 'Đã hủy',
                'group-action' => [
                    // chỉ xóa và tạo đơn mới
                    self::STATUS_DELETED
                ]
            ],
            self::STATUS_RETURNED => [
                'id' => self::STATUS_RETURNED, 'alias' => 'returned',
                'style' => 'danger',
                'icon' => 'icon-undo2',
                'text' => 'Trả hàng',
                'text-action' => 'Trả hàng',
                'group-action' => [
                    // chỉ xóa và tạo đơn mới
                    self::STATUS_DELETED
                ]
            ],
            self::STATUS_FAIL_DELIVERY  => [
                'id' => self::STATUS_FAIL_DELIVERY, 'alias' => 'fail-delivery',
                'style' => 'danger',
                'icon' => 'icon-alert',
                'text' => 'Giao hàng không thành công',
                'text-action' => 'Giao hàng không thành công',
                'group-action' => [
                    self::STATUS_DELETED
                ]
            ],
            self::STATUS_DELETED => [
                'id' => self::STATUS_DELETED, 'alias' => 'deleted',
                'style' => 'danger',
                'icon' => 'icon-trash',
                'text' => 'Đã xóa',
                'text-action' => 'Đã xóa',
                'group-action' => []
            ],
        ];
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

    public static function paymentType($selected = FALSE, $status = FALSE){
        $options = [
            self::PAYMENT_VITIEUDUNG => [
                'id' => self::PAYMENT_VITIEUDUNG, 'alias' => 'payment-vitieudung',
                'style' => 'success',
                'icon' => 'mdi mdi-bullseye-arrow',
                'text' => 'Thanh toán bằng ví tiêu dùng',
                'text-action' => 'Thanh toán bằng ví tiêu dùng',
            ],
        ];

        if ($selected && isset($options[$selected])) {
            $options[$selected]['checked'] = 'checked';
        }elseif($status !== FALSE) {
            if(isset($options[$status])) {
                return $options[$status];
            }
            return false;
        }

        return $options;
    }

    protected function createOrder($data) {
        return self::create($data);
    }

    static function getPercentTraHang($agency, $customer, $order) {
        if(@$agency['is_cty'] == true) {
            if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfDaiLy();
            }elseif ($customer['chuc_danh'] == Customer::IS_DAILY || @$customer['chuc_danh'] === "") {
                $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredDaiLyHuyenOfMpMart();
            }elseif ($customer['chuc_danh'] == Customer::IS_MPMART) {
                $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredMPMartOfCty();
            }
        }else {
            if(is_string($agency['dai_ly_tra_hang'])) {
                if ($agency['dai_ly_tra_hang'] == Agency::AGENCY_TRA_HANG_CAP_HUYEN || $agency['dai_ly_tra_hang'] == Agency::AGENCY_TRA_HANG_CAP_TINH) {
                    if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfDaiLy();
                    }
                }elseif ($agency['dai_ly_tra_hang'] == Agency::AGENCY_MP_MART) {
                    if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfMpMart();
                    }elseif ($customer['chuc_danh'] == Customer::IS_DAILY) {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredDaiLyHuyenOfMpMart();
                    }
                }
            }
            else if(is_array($agency['dai_ly_tra_hang'])) {
                if (in_array(Agency::AGENCY_TRA_HANG_CAP_HUYEN, $agency['dai_ly_tra_hang'])) {
                    if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfDaiLy();
                    }
                }elseif (in_array(Agency::AGENCY_TRA_HANG_CAP_TINH, $agency['dai_ly_tra_hang'])) {
                    if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfDaiLy();
                    }
                }elseif (in_array(Agency::AGENCY_MP_MART, $agency['dai_ly_tra_hang'])) {
                    if($customer['chuc_danh'] == Customer::IS_CTV || @$customer['chuc_danh'] === "") {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredCTVOfMpMart();
                    }elseif ($customer['chuc_danh'] == Customer::IS_DAILY) {
                        $percent = UnauthorizedPersonnel::getPercentKhoDiemHoaHongDeliveredDaiLyHuyenOfMpMart();
                    }
                }
            }
        }

        $result = [];
        if(isset($percent)) {
            $result = [
                'percent' => @$percent,
            ];
        }
        return $result;
    }
}