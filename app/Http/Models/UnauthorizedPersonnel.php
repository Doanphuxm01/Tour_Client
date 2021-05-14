<?php


namespace App\Http\Models;


use App\Elibs\eView;
use App\Elibs\Helper;

class UnauthorizedPersonnel extends BaseModel
{
    public $timestamps = false;
    const table_name = 'UnauthorizedPersonnel';
    protected $table = self::table_name;
    static $unguarded = true;

    static function getUn() {
        $data =  self::where('xxxxminhphucxxx', 'xxxxminhphucxxx')->first();
        if($data) {
            return $data->toArray();
        }
        return [];
    }

    static function getTileHoaHongForFBuyCoPhanOfCty($old = false) {
        if($old && @$old['xxxxminhphucxxx'] == 'xxxxminhphucxxx') {
            $data = $old;
        }else {
            $data = UnauthorizedPersonnel::getUn();
        }
        $moneyForF = [
            @$data['commission_f1_mua_cophan_cty']/100,
            @$data['commission_f2_mua_cophan_cty']/100,
            @$data['commission_f3_mua_cophan_cty']/100,
            @$data['commission_f4_mua_cophan_cty']/100,
            @$data['commission_f5_mua_cophan_cty']/100
        ];
        return $moneyForF;
    }

    static function getPhiGiaoDichChuyenNhuongCoPhan($sotiengiaodich) {
        $phigiaodich = 3000;
        if($sotiengiaodich <= 5000){
            $phigiaodich = 2000;
        }elseif($sotiengiaodich <= 10000){
            $phigiaodich = 3000;
        }elseif($sotiengiaodich > 100000){
            $phigiaodich = 5000;
        }
        return $phigiaodich;
    }

    static function getLotViCoTuc() {
        $phigiaodich = 50000;
        return $phigiaodich;
    }

    static function getMinPriceWithdrawal() {
        $data = UnauthorizedPersonnel::getUn();
        if(!isset($data['price_min_withdrawal_request'])) {
            return 50000;
        }
        return $data['price_min_withdrawal_request'];
    }

    static function calcTraCoTucByViCoPhan() {
        return ;
    }

    static function getTotalCoPhanConLai() {
        $data = self::where('xxxxminhphucxxx', 'xxxxminhphucxxx')->first();
        if(@$data['total_cophan_cty_now']) {
            return $data['total_cophan_cty_now'];
        }
        return false;
    }

    static function calcTruKhoCoPhan($socophangiaodich, $orderId) {
        $totalCoPhanCtyNow = UnauthorizedPersonnel::getTotalCoPhanConLai();
        $socophanconlaicty = $totalCoPhanCtyNow - $socophangiaodich;
        if($socophanconlaicty < 0) {
            return eView::getInstance()->getJsonNotifError('Số cổ phần của cty hiện có không đủ đáp ứng giao dịch này.');
        }
        $unOld = UnauthorizedPersonnel::getUn();
        UnauthorizedPersonnel::where('xxxxminhphucxxx', 'xxxxminhphucxxx')->update(['total_cophan_cty_now' => $socophanconlaicty]);
        #region trừ cổ phần trong kho
        Logs::createLog([
            'type' => Logs::TYPE_APPROVED,
            'object_id' => $orderId,
            'note' => 'Kho cổ phần cty đã giao dịch "' . Helper::formatMoney($socophangiaodich) . '" cổ phần cho đơn hàng "'.$orderId.'"'
        ], UnauthorizedPersonnel::table_name, $unOld, UnauthorizedPersonnel::getUn());
        #endregion
        return true;

    }

    static function getPriceChuyenNhuongCoDongForCoDong() {
        $data = UnauthorizedPersonnel::getUn();
        if(!isset($data['price_chuyen_nhuong_one_cophan_codong_cho_codong'])) {
            return false;
        }
        return $data['price_chuyen_nhuong_one_cophan_codong_cho_codong'];
    }

    static function getPriceChuyenNhuongCoDongForCty() {
        $data = UnauthorizedPersonnel::getUn();
        if(!isset($data['price_chuyen_nhuong_one_cophan_codong_cho_cty'])) {
            return false;
        }
        return $data['price_chuyen_nhuong_one_cophan_codong_cho_cty'];
    }

    static function checkExistsOptionMocTien($runHangNgay = false)
    {
        $data = UnauthorizedPersonnel::getUn();
        if($runHangNgay) {
            if(!isset($data['option_moctien_apdung_don_hang_run_hang_ngay'])) {
                return false;
            }
            $steps = $data['option_moctien_apdung_don_hang_run_hang_ngay'];
        }else {
            if(!isset($data['option_moctien_apdung_don_hang_no_run_hang_ngay'])) {
                return false;
            }
            $steps = $data['option_moctien_apdung_don_hang_no_run_hang_ngay'];
        }
        return $steps;
    }

    static function getStepsOrder($sotiengiaodich, $customer, $runHangNgay = false) {

        $steps = self::checkExistsOptionMocTien($runHangNgay);
        $step = [];
        if(!$steps) {
            return false;
        }
        usort($steps, function($a, $b){ return $a['max_money'] > $b['max_money']; });
        // @todo @kayn còn vấn đề về chức danh cũ, vì mới - cũ = 1
        if(isset($customer['chuc_danh']) && $customer['chuc_danh'] != Customer::IS_CTV && isset($customer['level'])) {
            $lv = $customer['level'];
        }
        foreach ($steps as $k => $item) {
            if($sotiengiaodich < $item['max_money']) {
                $step = $item;
                $step['level'] = $k;
                break;
            }
        }
        if(isset($lv) && $lv != "" && $step['level'] < $lv) {
            $step = @$steps[$lv];
        }
        if($step) {
            foreach ($step as $i => $v) {
                if ($v == "") {
                    $step[$i] = 0;
                }
            }
            if (isset($step['percent_tang_diemtieudung'])) {
                $step['percent_tang_diemtieudung'] /= 100;
            }
            if (isset($step['percent_tang_diemchietkhau'])) {
                $step['percent_tang_diemchietkhau'] /= 100;
            }
            if (isset($step['percent_run_hangngay'])) {
                $step['percent_run_hangngay'] /= 100;
            }
            if (isset($step['percent_tang_cophan'])) {
                $step['percent_tang_cophan'] /= 100;
            }
        }
        return $step;
    }

    static function getPercentKhoDiemTieuDungDeliveredDaiLyHuyen($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_tieudung_delivered_daily_huyen'])) {
            return false;
        }
        return (double)$data['percent_khodiem_tieudung_delivered_daily_huyen'] / 100;
    }

    static function getPercentKhoDiemTieuDungDeliveredDaiLyTinh($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_tieudung_delivered_daily_tinh'])) {
            return false;
        }
        return (double)$data['percent_khodiem_tieudung_delivered_daily_tinh'] / 100;
    }

    static function getPercentKhoDiemHoaHongDeliveredCTVOfMpMart($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_hoahong_delivered_ctv_of_mpmart'])) {
            return false;
        }
        return (double)$data['percent_khodiem_hoahong_delivered_ctv_of_mpmart'] / 100;
    }

    static function getPercentKhoDiemHoaHongDeliveredDaiLyHuyenOfMpMart($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_hoahong_delivered_daily_huyen_of_mpmart'])) {
            return false;
        }
        return (double)$data['percent_khodiem_hoahong_delivered_daily_huyen_of_mpmart'] / 100;
    }

    static function getPercentKhoDiemHoaHongDeliveredDaiLyTinhOfMpMart($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_hoahong_delivered_daily_tinh_of_mpmart'])) {
            return false;
        }
        return (double)$data['percent_khodiem_hoahong_delivered_daily_tinh_of_mpmart'] / 100;
    }

    static function getPercentKhoDiemHoaHongDeliveredMPMartOfCty($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_hoahong_delivered_mpmart_of_cty'])) {
            return false;
        }
        return (double)$data['percent_khodiem_hoahong_delivered_mpmart_of_cty'] / 100;
    }

    static function getPercentKhoDiemHoaHongDeliveredCTVOfDaiLy($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['percent_khodiem_hoahong_delivered_ctv_of_daily'])) {
            return false;
        }
        return (double)$data['percent_khodiem_hoahong_delivered_ctv_of_daily'] / 100;
    }

    static function getCondUpDaiLyCapHuyen($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['price_min_up_daily_huyen_request'])) {
            return false;
        }
        return $data['price_min_up_daily_huyen_request'];
    }

    static function getCondUpDaiLyCapTinh($old = false)
    {
        if ($old) {
            $data = $old;
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['price_min_up_daily_tinh_request'])) {
            return false;
        }
        return $data['price_min_up_daily_tinh_request'];
    }



}