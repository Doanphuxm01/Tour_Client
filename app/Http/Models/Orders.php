<?php

namespace App\Http\Models;
use App\Elibs\EmailHelper;
use App\Elibs\eView;
use App\Elibs\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Orders extends BaseModel
{

    // đơn hàng
    public $timestamps = FALSE;
    const table_name = 'io_orders';
    protected $table = self::table_name;
    static $unguarded = TRUE;

    const DEBT_YES = 'yes'; // nợ vl
    const DEBT_NO = 'no';   // không nợ


    static $_MPOrdersField = [
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
            'label' => 'Thời gian tạo đơn',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'status' => [
            'key' => 'status',
            'label' => 'Trạng thái đơn',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'type' => [
            'key' => 'type',
            'label' => 'Loại đơn',
            'type' => 'text',
            'groupClass' => 'col-md-12',
        ],
        'so_diem_can_mua' => [
            'key' => 'so_diem_giao_dich',
            'label' => 'Số điểm giao dịch',
            'type' => 'number',
            'groupClass' => 'col-md-12',
        ],
        'so_tien_giao_dich' => [
            'key' => 'so_tien_giao_dich',
            'label' => 'Số tiền giao dịch',
            'type' => 'number',
            'groupClass' => 'col-md-12',
        ],

    ];

    static function createOrder($obj) {
        $id = self::insertGetId($obj);
        $orders = self::find($id)->toArray();
        /*Sendmail*/
        if (!empty($orders['tai_khoan_nguon']['email'])) {
            $tpl['success'] = true;
            $tpl['code'] = @$obj['code'];
            $tpl['name'] = 'Đặt đơn mua cổ phần thành công';
            $tpl['tokenString'] = Helper::buildTokenString($id);
            $tpl['url'] = public_link('auth/verifyEmail?uid=' . $id . '&token=' . Helper::buildTokenString($id));
            $tpl['subject'] = '[Hệ thống quản lý MinhPhucGroup] Đặt đơn mua cổ phần thành công';
            $tpl['template'] = "mail.verified_account";
            EmailHelper::sendMail($orders['tai_khoan_nguon']['email'], $tpl);
        }
        if (!empty($orders['tai_khoan_nhan']['email'])) {
            $tpl['success'] = true;
            $tpl['code'] = @$obj['code'];
            $tpl['name'] = 'Đặt đơn mua cổ phần thành công';
            $tpl['tokenString'] = Helper::buildTokenString($id);
            $tpl['url'] = public_link('auth/verifyEmail?uid=' . $id . '&token=' . Helper::buildTokenString($id));
            $tpl['subject'] = '[Hệ thống quản lý MinhPhucGroup] Bạn nhận được đơn đặt mua cổ phần từ thành viên '. @$orders['tai_khoan_nguon']['account'];
            $tpl['template'] = "mail.verified_account";
            EmailHelper::sendMail($orders['tai_khoan_nguon']['email'], $tpl);
        }
        return $orders;
    }

    static function checkLevelDoanhThu($tongdoanhthu)
    {
        $data = UnauthorizedPersonnel::getUn();
        if ($tongdoanhthu >= @$data['total_doanhthu_level_6']) {
            return TongDoanhThu::LV6;
        } elseif ($tongdoanhthu >= @$data['total_doanhthu_level_5']) {
            return TongDoanhThu::LV5;
        } elseif ($tongdoanhthu >= @$data['total_doanhthu_level_4']) {
            return TongDoanhThu::LV4;
        } elseif ($tongdoanhthu >= @$data['total_doanhthu_level_3']) {
            return TongDoanhThu::LV3;
        } elseif ($tongdoanhthu >= @$data['total_doanhthu_level_2']) {
            return TongDoanhThu::LV2;
        } elseif ($tongdoanhthu >= @$data['total_doanhthu_level_1']) {
            return TongDoanhThu::LV1;
        }
        return TongDoanhThu::LV0;
    }

    static function getPercentLevelDoanhThu($level, $lsObj, $account)
    {
        $percent = self::getTotalPercentLevelDoanhThu($level);
        $percentNew = 0;
        $percentOld = 0;
        $levelOld = 0;
        $levelOldTrue = 0;
        $arrayLevels = [];
        if(!empty($lsObj)) {
            foreach ($lsObj as $k => $obj) {
                if ($account == $obj['account']) {
                    break;
                }
    
                if (!isset($obj['level_doanhthu'])) {
                    $obj['level_doanhthu'] = TongDoanhThu::LV0;
                }
    
                // nếu đằng trước mà cấp thấp hơn đằng sau thì trừ
                $percentNew -= $percentOld;
                $percentOld = self::getTotalPercentLevelDoanhThu($obj['level_doanhthu']);
                $percentNew += $percentOld;
                array_push($arrayLevels, $obj['level_doanhthu']);
            }
        }
        return $percent-$percentNew;
    }

    static function getPercentLevelDoanhThuMpMart($level, $lsObj, $account)
    {
        $percent = self::getTotalPercentLevelDoanhThuMpMart($level);
        $percentNew = 0;
        $percentOld = 0;
        $levelOld = 0;
        $arrayLevels = [];
        if(!empty($lsObj)) {
            foreach ($lsObj as $k => $obj) {
                if ($account == $obj['account']) {
                    break;
                }

                if (!isset($obj['level_doanhthu'])) {
                    $obj['level_doanhthu'] = TongDoanhThu::LV0;
                }

                // nếu đằng trước mà cấp thấp hơn đằng sau thì trừ
                $percentNew -= $percentOld;
                $percentOld = self::getTotalPercentLevelDoanhThuMpMart($obj['level_doanhthu']);
                $percentNew += $percentOld;
                array_push($arrayLevels, $obj['level_doanhthu']);

                //$levelOld = $obj['level_doanhthu'];
                //$percentNew -= $percentOld;
                /*$percentOld = self::getTotalPercentLevelDoanhThu($obj['level_doanhthu']);
                $percentNew += $percentOld;*/

            }
        }
        return $percent-$percentNew;
    }

    static function getTotalPercentLevelDoanhThu($level)
    {
        $data = UnauthorizedPersonnel::getUn();
        if ($level === TongDoanhThu::LV0) {
            return @$data['percent_hoahong_doanhthu_level_0'] / 100;
        } elseif ($level === TongDoanhThu::LV1) {
            return @$data['percent_hoahong_doanhthu_level_1'] / 100;
        } elseif ($level === TongDoanhThu::LV2) {
            return @$data['percent_hoahong_doanhthu_level_2'] / 100;
        } elseif ($level === TongDoanhThu::LV3) {
            return @$data['percent_hoahong_doanhthu_level_3'] / 100;
        } elseif ($level === TongDoanhThu::LV4) {
            return @$data['percent_hoahong_doanhthu_level_4'] / 100;
        } elseif ($level === TongDoanhThu::LV5) {
            return @$data['percent_hoahong_doanhthu_level_5'] / 100;
        } elseif ($level === TongDoanhThu::LV6) {
            return @$data['percent_hoahong_doanhthu_level_6'] / 100;
        }
        return 0;
    }

    static function getTotalPercentLevelDoanhThuMpMart($level)
    {
        $data = UnauthorizedPersonnel::getUn();
        if ($level === TongDoanhThu::LV0) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_0'] / 100;
        } elseif ($level === TongDoanhThu::LV1) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_1'] / 100;
        } elseif ($level === TongDoanhThu::LV2) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_2'] / 100;
        } elseif ($level === TongDoanhThu::LV3) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_3'] / 100;
        } elseif ($level === TongDoanhThu::LV4) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_4'] / 100;
        } elseif ($level === TongDoanhThu::LV5) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_5'] / 100;
        } elseif ($level == TongDoanhThu::LV6) {
            return @$data['percent_hoahong_mpmart_doanhthu_level_6'] / 100;
        }
        return 0;
    }

    static function soDuDoanhThu($tongdoanhthu)
    {
        $data = UnauthorizedPersonnel::getUn();
        if ($tongdoanhthu > @$data['total_doanhthu_level_6']) {
            return $tongdoanhthu - $data['total_doanhthu_level_6'];
        } elseif ($tongdoanhthu > @$data['total_doanhthu_level_5']) {
            return $tongdoanhthu - $data['total_doanhthu_level_5'];
        } elseif ($tongdoanhthu > @$data['total_doanhthu_level_4']) {
            return $tongdoanhthu - $data['total_doanhthu_level_4'];
        } elseif ($tongdoanhthu > @$data['total_doanhthu_level_3']) {
            return $tongdoanhthu - $data['total_doanhthu_level_3'];
        } elseif ($tongdoanhthu > @$data['total_doanhthu_level_2']) {
            return $tongdoanhthu - $data['total_doanhthu_level_2'];
        } elseif ($tongdoanhthu > @$data['total_doanhthu_level_1']) {
            return $tongdoanhthu - $data['total_doanhthu_level_1'];
        }
        return false;
    }


    static function getListCustomerTrueHoaHongDoanhThu($giaPha) {
        $lsCustomerTrueHoaHongDoanhThu = [];
        // tạo 1 mảng được thưởng hoa hồng
        $current_level = 0;
        $temp_aaa = 0;
        foreach ($giaPha as $k => $g) {
            if(!isset($g['level_doanhthu'])) {
                $g['level_doanhthu'] = TongDoanhThu::LV0;
            }
            $temp_level = isset($g['level_doanhthu']) ? (int)str_replace('LV','',$g['level_doanhthu']) : 0;

            if(!isset($g['level_doanhthu'])  && $current_level <= $temp_level) {
                $g['level_doanhthu'] = TongDoanhThu::LV0;
            }

            if ((isset($g['level_doanhthu']) && !isset($lsCustomerTrueHoaHongDoanhThu[$g['level_doanhthu']]) && $current_level <= $temp_level) && $temp_level > 0) {
                //echo  $current_level. '|'.$temp_level.'<br>';

                //$lsCustomerTrueHoaHongDoanhThu['LV'.$current_level] = '';
                if($g['account'] != $giaPha[0]['account']) {
                    $current_level = $temp_level;
                    $lsCustomerTrueHoaHongDoanhThu[$g['level_doanhthu']] = $g;
                    $current_level++;
                }

            }elseif((isset($g['level_doanhthu']) && $temp_level == 0 ) && $temp_aaa < 1) {
                if($g['account'] != $giaPha[0]['account']) {

                    $current_level = $temp_aaa == 0 ? $current_level + 1 : $current_level;

                    $lsCustomerTrueHoaHongDoanhThu[$g['level_doanhthu']] = $g;
                    $temp_aaa++;
                }
            }
        }
        ksort($lsCustomerTrueHoaHongDoanhThu);
        if(!isset($giaPha[0]['level_doanhthu'])) {
            $giaPha[0]['level_doanhthu'] = TongDoanhThu::LV0;
        }
        if(!isset($giaPha[1]['level_doanhthu'])) {
            $giaPha[1]['level_doanhthu'] = TongDoanhThu::LV0;
        }
        if(isset($giaPha[0]['level_doanhthu']) && $giaPha[0]['level_doanhthu'] == TongDoanhThu::LV0 && $giaPha[0]['level_doanhthu'] != $giaPha[1]['level_doanhthu']) {
            unset($lsCustomerTrueHoaHongDoanhThu[TongDoanhThu::LV0]);
        }
        /*if(isset($giaPha[1]) && @$giaPha[1]['ma_gioi_thieu'] == @$giaPha[0]['parent_id']) {
            array_unshift($lsCustomerTrueHoaHongDoanhThu, $giaPha[1]);
        }*/

        return $lsCustomerTrueHoaHongDoanhThu;
    }


    static function calcLevelTheoTongDoanhThu($sotiengiaodich, $taikhoannguon, $order)
    {
        $account = $taikhoannguon['account'];
        $temp = [];
        $flagHH = true;
        $percentCTV = true;
        $flagHHLevelMax = false;
        $giaPha = Customer::buildFullTreeNguocBaoGomCaGoc('', $temp, $account); // gia phả dòng họ
        if ($giaPha) {
            //$lsTdt = TongDoanhThu::whereIn('account', array_column($giaPha, 'account'))->get()->keyBy('account')->toArray();
            $levelOld = 0;
            $now = date('Y/m/d');
            $n = new Carbon($now);
            //dump($giaPha);
            //$lsCustomerTreeNew = [];
            //$giaPha = Customer::buildFullTreeNguocBaoGomCaGoc('', $lsCustomerTreeNew, $account); // gia phả dòng họ
            $lsCustomerTrueHoaHongDoanhThu = self::getListCustomerTrueHoaHongDoanhThu($giaPha);
            $dudieukien = true;
            $first = true;
            foreach ($giaPha as $k => $g) {

                /*if (isset($g['dk_nhan_hoahong_doanhthu']['last_time'])) {
                    $a = new Carbon(Helper::showMongoDate($g['dk_nhan_hoahong_doanhthu']['last_time'], 'Y/m/d'));
                }else {
                    $a = new Carbon(Helper::showMongoDate($g['created_at'], 'Y/m/d'));
                }
                $ago = $a->diffInDays($n);
                if ($ago <= 30) {
                    $dudieukien = true;
                }else {
                    $dudieukien = false;
                }*/

                if ($dudieukien) {
                    $tongdoanhthu = TongDoanhThu::getViByAccount($g['account']);
                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        $g['level_doanhthu'] = TongDoanhThu::LV0;
                    }else {
                        $levelDT = $g['level_doanhthu'];
                        $levelDT4TotalMoney = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT4TotalMoney)) {
                            $levelDT = $levelDT4TotalMoney;
                        }
                    }

                    if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT)) {
                        $soduDT = self::soDuDoanhThu($tongdoanhthu['total_money']);
                    } else {
                        $soduDT = $sotiengiaodich;
                    }

                    if(!empty($lsCustomerTrueHoaHongDoanhThu)) {
                        foreach($lsCustomerTrueHoaHongDoanhThu as $t => $i) {

                            if($g['account'] === $i['account']) {
                                $percentLevel = self::getPercentLevelDoanhThu($levelDT, $lsCustomerTrueHoaHongDoanhThu, $i['account']);
                                /*if($first) {
                                    $first = false;
                                    $percentLevel += 7/100;
                                }*/
                                break;
                            }else {
                                $percentLevel = 0;
                            }
                        }
                    }

                    //dump(@$percentLevel .'-'.$g['account'].'-'.$levelDT.'-'. $percentCTV.'-'. $flagHH);


                    if ($soduDT && @$percentLevel > 0) {
                        $flagHH = false;
                        $moneyHHDT = $percentLevel * $soduDT;
                        ViHoaHong::CongTruViVer2($g['account'], $moneyHHDT, ViHoaHong::table_name);
                        $g['level_doanhthu'] = $levelDT;
                        $saveTransactionHoaHong = [
                            'diem_da_nhan' => $moneyHHDT,
                            'percent_level' => $percentLevel,
                            'tai_khoan_nguon' => Customer::getTaiKhoanToSaveDb($taikhoannguon),
                            'tai_khoan_nhan' => Customer::getTaiKhoanToSaveDb($g),
                            'type_giaodich_hoahong_doanhthu' => true,
                            'type_giaodich' => Transaction::HOAHONG_DOANHTHU,
                            'vi_nhan_tien' => Transaction::VIHOAHONG,
                            'detail_type_giaodich' => 'Hoa hồng được thưởng cho tổng doanh thu',
                            'order_id' => @$order['_id'],
                        ];
                        Transaction::createTransaction($saveTransactionHoaHong, Transaction::DIEM_HOAHONG, Transaction::VIHOAHONG);
                    }
                    $levelOld = $levelDT;
                }
            }

            foreach ($giaPha as $k => $g) {
                $dudieukien = true;
                if ($dudieukien) {

                    $tongdoanhthu = TongDoanhThu::CongTruViVer2($g['account'], $sotiengiaodich, TongDoanhThu::table_name);

                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        $g['level_doanhthu'] = TongDoanhThu::LV0;
                    }else {
                        $levelDT = $g['level_doanhthu'];
                        $levelDT4TotalMoney = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT4TotalMoney)) {
                            $levelDT = $levelDT4TotalMoney;
                        }
                    }

                    if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT)) {
                        Customer::where('account', $g['account'])->update(['level_doanhthu' => $levelDT]);
                        $customerAfterSave = Customer::find($g['_id'])->toArray();
                        Logs::createLog([
                            'type' => Logs::TYPE_UPDATED,
                            'object_id' => $g['_id'],
                            'note' => 'Tài khoản "' . $g['account'] . '" đã đạt level: ' . $levelDT
                        ], Customer::table_name, $g, $customerAfterSave);
                    }

                }else {
                    $tongdoanhthu = TongDoanhThu::getViByAccount($g['account']);
                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu(@$tongdoanhthu['total_money']);
                    } else {
                        $levelDT = $g['level_doanhthu'];
                    }
                    if (str_replace('LV', '', $levelDT) > str_replace('LV', '', $levelOld)) {
                        $flagHH = false;
                    }
                    $levelOld = $levelDT;
                }
            }

        }
    }

    static function calcLevelTheoTongDoanhThuMpMart($sotiengiaodich, $taikhoannguon, $order)
    {
        $account = $taikhoannguon['account'];
        $temp = [];
        $flagHH = true;
        $levelBefSave = false;
        $percentCTV = true;
        $flagHHLevelMax = false;
        $giaPha = Customer::buildFullTreeNguocBaoGomCaGoc('', $temp, $account); // gia phả dòng họ
        if ($giaPha) {
            //$lsTdt = TongDoanhThu::whereIn('account', array_column($giaPha, 'account'))->get()->keyBy('account')->toArray();
            $levelOld = 0;
            $now = date('Y/m/d');
            $n = new Carbon($now);
            //$lsCustomerTreeNew = [];
            //$giaPha = Customer::buildFullTreeNguocBaoGomCaGoc('', $lsCustomerTreeNew, $account); // gia phả dòng họ
            if($levelBefSave) {
                $giaPha[0]['level_doanhthu'] = TongDoanhThu::LV0;
            }
            $lsCustomerTrueHoaHongDoanhThu = self::getListCustomerTrueHoaHongDoanhThu($giaPha);
            $dudieukien = true;
            $mocTienThuNhap = 0;
            $levelThuNhap = 0;
            $percentThuNhapTrenThuNhap = [0, 0.05, 0.03, 0.02];

            foreach ($giaPha as $k => $g) {
                /*if (isset($g['dk_nhan_hoahong_doanhthu']['last_time'])) {
                    $a = new Carbon(Helper::showMongoDate($g['dk_nhan_hoahong_doanhthu']['last_time'], 'Y/m/d'));
                }else {
                    $a = new Carbon(Helper::showMongoDate($g['created_at'], 'Y/m/d'));
                }
                $ago = $a->diffInDays($n);
                if ($ago <= 30) {
                    $dudieukien = true;
                }else {
                    $dudieukien = false;
                }*/

                if ($dudieukien) {
                    $tongdoanhthu = TongDoanhThu::getViByAccount($g['account']);
                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        $g['level_doanhthu'] = TongDoanhThu::LV0;
                    }else {
                        $levelDT = $g['level_doanhthu'];
                        $levelDT4TotalMoney = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT4TotalMoney)) {
                            $levelDT = $levelDT4TotalMoney;
                        }
                    }
                    if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT)) {
                        $soduDT = self::soDuDoanhThu($tongdoanhthu['total_money']);
                    } else {
                        $soduDT = $sotiengiaodich;
                    }


                    if(!empty($lsCustomerTrueHoaHongDoanhThu)) {
                        foreach($lsCustomerTrueHoaHongDoanhThu as $i) {
                            if($g['account'] === $i['account']) {
                                $percentLevel = self::getPercentLevelDoanhThuMpMart($levelDT, $lsCustomerTrueHoaHongDoanhThu, $i['account']);
                                break;
                            }else {
                                $percentLevel = 0;
                            }
                        }
                    }

                    //dump(@$percentLevel .'-'.$g['account'].'-'.$levelDT.'-'. $percentCTV.'-'. $flagHH);


                    if ($soduDT && @$percentLevel > 0) {
                        $flagHH = false;
                        $moneyHHDT = $percentLevel * $soduDT;
                        $dataViAffterSave = ViHoaHong::CongTruViVer2($g['account'], $moneyHHDT, ViHoaHong::table_name);
                        ViHoaHong::where('account', $g['account'])->update(['total_money_hoahong_doanhthu' => @$dataViAffterSave['total_money_hoahong_doanhthu']+$moneyHHDT]);
                        $g['level_doanhthu'] = $levelDT;
                        if($mocTienThuNhap == 0) {
                            $mocTienThuNhap = $moneyHHDT;
                            $levelThuNhap = $levelDT;
                        }
                        $saveTransactionHoaHong = [
                            'diem_da_nhan' => $moneyHHDT,
                            'percent_level' => $percentLevel,
                            'tai_khoan_nguon' => Customer::getTaiKhoanToSaveDb($taikhoannguon),
                            'tai_khoan_nhan' => Customer::getTaiKhoanToSaveDb($g),
                            'type_giaodich_hoahong_doanhthu' => true,
                            'type_giaodich' => Transaction::HOAHONG_DOANHTHU,
                            'vi_nhan_tien' => Transaction::VIHOAHONG,
                            'detail_type_giaodich' => 'Hoa hồng được thưởng cho tổng doanh thu',
                            'order_id' => @$order['_id'],
                        ];
                        Transaction::createTransaction($saveTransactionHoaHong, Transaction::DIEM_HOAHONG, Transaction::VIHOAHONG);
                    }
                    $levelOld = $levelDT;
                }else {
                    $tongdoanhthu = TongDoanhThu::getViByAccount($g['account']);
                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu(@$tongdoanhthu['total_money']);
                    } else {
                        $levelDT = $g['level_doanhthu'];
                    }
                    if (str_replace('LV', '', $levelDT) > str_replace('LV', '', $levelOld)) {
                        $flagHH = false;
                    }
                    $levelOld = $levelDT;
                }
            }

            // thu nhập trên thu nhập

            /*foreach ($giaPha as $k => $g) {
                if ($mocTienThuNhap && $k != 0 && $k < 4 && $levelThuNhap == $g['level_doanhthu']) {
                    $saveTransactionHoaHong = [
                        'diem_da_nhan' => $mocTienThuNhap*$percentThuNhapTrenThuNhap[$k],
                        'percent_level' => $percentThuNhapTrenThuNhap[$k],
                        'tai_khoan_nguon' => Customer::getTaiKhoanToSaveDb($taikhoannguon),
                        'tai_khoan_nhan' => Customer::getTaiKhoanToSaveDb($g),
                        'type_giaodich_hoahong_thunhap_thunhap' => true,
                        'type_giaodich' => Transaction::THUNHAP_THUNHAP,
                        'vi_nhan_tien' => Transaction::VIHOAHONG,
                        'detail_type_giaodich' => 'Thu nhập trên thu nhập',
                        'order_id' => @$order['_id'],
                    ];
                    Transaction::createTransaction($saveTransactionHoaHong);
                }
            }*/

            // cập nhật leveldoanhthu
            foreach ($giaPha as $k => $g) {
                $dudieukien = true;

                if ($dudieukien) {

                    $tongdoanhthu = TongDoanhThu::CongTruViVer2($g['account'], $sotiengiaodich, TongDoanhThu::table_name);

                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        $g['level_doanhthu'] = TongDoanhThu::LV0;
                    }else {
                        $levelDT = $g['level_doanhthu'];
                        $levelDT4TotalMoney = self::checkLevelDoanhThu($tongdoanhthu['total_money']);
                        if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT4TotalMoney)) {
                            $levelDT = $levelDT4TotalMoney;
                        }
                    }

                    if (str_replace('LV', '', $g['level_doanhthu']) < str_replace('LV', '', $levelDT)) {
                        Customer::where('account', $g['account'])->update(['level_doanhthu' => $levelDT]);
                        $customerAfterSave = Customer::find($g['_id'])->toArray();
                        Logs::createLog([
                            'type' => Logs::TYPE_UPDATED,
                            'object_id' => $g['_id'],
                            'note' => 'Tài khoản "' . $g['account'] . '" đã đạt level: ' . $levelDT
                        ], Customer::table_name, $g, $customerAfterSave);
                        if($k == 0) {
                            if(!isset($giaPha[$k]['level_doanhthu'])) {
                                $giaPha[$k]['level_doanhthu'] = TongDoanhThu::LV0;
                            }
                            if(!$levelBefSave && $giaPha[$k]['level_doanhthu'] == TongDoanhThu::LV0 && $giaPha[$k]['level_doanhthu'] != $levelDT) {
                                $levelBefSave = true;
                            }
                        }
                    }

                }else {
                    $tongdoanhthu = TongDoanhThu::getViByAccount($g['account']);
                    if (!isset($g['level_doanhthu'])) {
                        $levelDT = self::checkLevelDoanhThu(@$tongdoanhthu['total_money']);
                    } else {
                        $levelDT = $g['level_doanhthu'];
                    }
                    if (str_replace('LV', '', $levelDT) > str_replace('LV', '', $levelOld)) {
                        $flagHH = false;
                    }
                    $levelOld = $levelDT;
                }
            }

        }
    }


    static function getMinDaiLy($old = false)
    {
        if ($old) {
            $data = $old;
            if (!isset($data['order_is_daily'])) {
                $data = UnauthorizedPersonnel::getUn();
            }
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['order_is_daily'])) {
            return false;
        }
        return $data['order_is_daily'];
    }

    static function getMinMPMart($old = false)
    {
        if ($old) {
            $data = $old;
            if (!isset($data['order_is_mpmart'])) {
                $data = UnauthorizedPersonnel::getUn();
            }
        } else {
            $data = UnauthorizedPersonnel::getUn();
        }
        if (!isset($data['order_is_mpmart'])) {
            return false;
        }
        return $data['order_is_mpmart'];
    }

    static function getByCode($code) {
        if (!$code) {
            return [];
        }
        $where = [
            'code' => $code,
        ];
        $member = static::where($where)->first();
        return $member;
    }

    static function getDanhSachDonHangChuaCapNhatViMoiNgay($keyBy = false)
    {
        // lấy danh sahcs đơn hàng chưa được 10 tháng cập nhật ví tiêu dùng, tích luỹ mỗi ngày,
        // tạm thời thì đáp ứng đc những đơn có no_run_hang_ngay true, chưa thực sự đúng vs giá trị false
        $now = Helper::getMongoDate(date('d/m/Y'));
        $where = [
            'status' => self::STATUS_PROCESS_DONE,
            'type' => MetaData::SANPHAM,
            'no_run_hang_ngay' => ['$exists' => false],
            '$or' => [
                [
                    'end_updated_vi_at' => ['$exists' => false],
                ],
                [
                    'end_updated_vi_at' => ['$gt' => $now],
                ]
            ],
            '$or' => [
                [
                    'updated_vi_at' => ['$exists' => false],
                ],
                [
                    'updated_vi_at' => ['$lt' => $now],
                ]
            ],
        ];
        $data = self::where($where)->get();
        if ($keyBy) {
            return $data->keyBy($keyBy)->toArray();
        }
        return $data->toArray();
    }


}