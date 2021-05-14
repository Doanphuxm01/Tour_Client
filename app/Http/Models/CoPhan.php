<?php


namespace App\Http\Models;


class CoPhan extends BaseModel
{
    public $timestamps = FALSE;
    const table_name = 'io_cophan';
    protected $table = self::table_name;
    static $unguarded = TRUE;


    static function getSoCoPhanToiThieuPhaiMuaBefRegistration($old = false) {
        if($old) {
            $data = $old;
        }else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['number_cophan_cty_min_before_registration'])) {
            return 2000;
        }
        return $data['number_cophan_cty_min_before_registration'];
    }

    static function getGiaBanCoPhanChoCoDongBefRegistration($old = false) {
        if($old) {
            $data = $old;
        }else {
            $data = UnauthorizedPersonnel::getUn();
        }
        return @$data['price_one_cophan_cty_cho_codong_before_registration'];
    }

    static function getTotalCurrentCoPhanCty() {
        $data = UnauthorizedPersonnel::getUn();
        return @$data['total_cophan_cty_now'];
    }
}