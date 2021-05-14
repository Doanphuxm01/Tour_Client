<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eCache;
use App\Elibs\Helper;
use Illuminate\Support\Facades\DB;

class Payment extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_payments';
    protected $table = self::table_name;
    static $unguarded = true;
    static $basicFiledsForList = '*';

    const PAY_TIENMAT = 'PAY_TIENMAT';
    const PAY_CHUYENKHOAN = 'PAY_CHUYENKHOAN';
    const PAY_THETINDUNG_NOIDIA = 'PAY_THETINDUNG_NOIDIA';
    const PAY_THETINDUNG_GHINOQUOCTE = 'PAY_THETINDUNG_GHINOQUOCTE';
    const PAY_ATM_INTERNET_BANKING = 'PAY_ATM_INTERNET_BANKING';

    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Đã thanh toán ', 'text-action' => 'Đã thanh toán'],
            self::STATUS_DISABLE => ['id' => self::STATUS_DISABLE, 'style' => 'warning', 'text' => 'Đang khóa ', 'text-action' => 'Khóa lại'],
            self::STATUS_NO_PAID => ['id' => self::STATUS_NO_PAID, 'style' => 'danger', 'text' => 'Chưa thanh toán ', 'text-action' => 'Chưa thanh toán'],
            self::STATUS_PENDING => ['id' => self::STATUS_PENDING, 'style' => 'warning', 'text' => 'Chờ duyệt', 'text-action' => 'Chờ duyệt'],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }

        return $listStatus;
    }


    static function getListPayment($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::PAY_TIENMAT => ['id' => self::PAY_TIENMAT, 'style' => 'success', 'text' => 'Tiền mặt', 'text-action' => 'Quý khách có thể tới trực tiếp văn phòng Vietrantour tại 33 Tràng Thi - Hoàn Kiếm - Hà Nội để thực hiện thủ tục thanh toán tiền mặt và nhận thêm tư vấn khác.'],
            self::PAY_CHUYENKHOAN => ['id' => self::PAY_CHUYENKHOAN, 'style' => 'warning', 'text' => 'Chuyển khoản / Internet Banking',],
            self::PAY_THETINDUNG_NOIDIA => ['id' => self::PAY_THETINDUNG_NOIDIA, 'style' => 'warning', 'text' => 'Thẻ ATM nội địa', 'text-action' => ''],
            self::PAY_THETINDUNG_GHINOQUOCTE => ['id' => self::PAY_THETINDUNG_GHINOQUOCTE, 'style' => 'warning', 'text' => 'Thẻ tín dụng / Thẻ ghi nợ quốc tế', 'text-action' => ''],
            // self::PAY_ATM_INTERNET_BANKING => ['id' => self::PAY_ATM_INTERNET_BANKING, 'style' => 'danger', 'text' => ''],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
            if($return) {
                return $listStatus[$selected];
            }
        }

        return $listStatus;
    }
}
