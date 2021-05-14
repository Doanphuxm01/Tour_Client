<?php


namespace App\Http\Controllers\_Dev;


use App\Elibs\eView;
use App\Elibs\Helper;
use App\Http\Models\BaseModel;
use App\Http\Models\Customer;
use App\Http\Models\Logs;
use App\Http\Models\Orders;
use App\Http\Models\Transaction;
use App\Http\Models\UnauthorizedPersonnel;
use App\Http\Models\ViChietKhau;
use App\Http\Models\ViHoaHong;
use App\Http\Models\ViTichLuy;
use App\Http\Models\ViTieuDung;
use App\Http\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DevData
{
    function run($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        }
    }

    function hoan_tra_tien_am_khi_rut_tien() {
        /*$date = date('14/08/2020');
        $where = [
            'created_at' => ['$gte' => Helper::getMongoDate($date)]
        ];
        $lsObj = Withdrawal::where($where)->get()->toArray();
        foreach ($lsObj as $obj) {
            if($obj['type_vi'] == 'vihoahong') {
                $vi = ViHoaHong::getViByAccount($obj['created_by']['account']);
            }elseif ($obj['type_vi'] == 'vitichluy') {
                $vi = ViTichLuy::getViByAccount($obj['created_by']['account']);
            }
            $total_money = $vi['total_money']+$obj['so_tien_muon_rut']+$obj['phi_giao_dich'];
            $vi->update(['total_money' => $total_money, 'so_diem_treo_gio' => 0]);
        }
        return eView::getInstance()->getJsonSuccess('Cập nhật thành công', []);*/
    }


    function nhay_lui_ngay() {
        dd('Cút');
        $ls_don_da_xu_ly_31_07 = BaseModel::table('ls_don_da_xu_ly_31_07')->get()->keyBy('id')->toArray();
        $ls_orders = BaseModel::table('io_orders')->where('status', 'done')->get()->keyBy('_id')->toArray();
        $don_lui_ngay = array_intersect_key($ls_orders, $ls_don_da_xu_ly_31_07);
        $count = 0;
        foreach ($don_lui_ngay as $id => $o) {
            $ls_don_da_xu_ly_31_07[$id]['actived_at'] = Carbon::parse(Helper::showMongoDate($ls_don_da_xu_ly_31_07[$id]['actived_at'], 'Y/m/d'));
            $o['actived_at'] = Carbon::parse(Helper::showMongoDate($o['actived_at'], 'Y/m/d'));
            $a = $ls_don_da_xu_ly_31_07[$id]['actived_at']->diffInDays($o['actived_at']);
            $now = date('d/m/Y');

            for ($i = 0; $i < $a; $i++) {
                // mỗi $i là 1 ngày nhảy
                if(!isset($o['debt']) && isset($o['everyday_percent_ctv_type'])) {
                    if ($o['everyday_percent_ctv_type'] == Orders::EVERYDAY_PERCENT_CTV_CKIETKHAU_TIEUDUNG) {

                        // case nhảy 0.05% vào ví tiêu dùng với đơn hàng áp dụng chính sách cho ctv
                        /*if (!isset($o['so_diem_duoc_nhan'])) {
                            $o['so_diem_duoc_nhan'] = $o['so_diem_vi_chiet_khau'];
                        }*/
                        $percent = Orders::getEveryDayPercentChietKhauTieuDungDebtNoCTV(@$o['percents']);
                        if (!isset($o['moc_tien'])) {
                            $o['moc_tien'] = $o['so_diem_vi_chiet_khau'] * $percent;
                        }

                        $toSaveTranViTieuDung = [
                            'diem_da_nhan' => $o['moc_tien'],
                            'created_by' => [],
                            'created_at' => Helper::getMongoDate(),
                            'status' => Transaction::STATUS_ACTIVE,
                            'type_giaodich' => Transaction::CHIETKHAU_TIEUDUNG,
                            'detail_type_giaodich' => Helper::formatPercent($percent) . '%/ngày từ ví chiết khấu -> ví tiêu dùng áp dụng với ctv',
                            'object' => Transaction::VITIEUDUNG,
                            'tai_khoan_nguon' => $o['tai_khoan_nguon'],
                            'tai_khoan_nhan' => $o['tai_khoan_nhan'],
                            'order_id' => $o['_id'],
                            'don_lui_ngay_thang07' => true,
                        ];
                        Transaction::insertGetId($toSaveTranViTieuDung);

                        // cập nhật đơn đã nhảy tiền theo ngày
                        $order = Orders::find($o['_id']);
                        $updated_vi_at = [
                            'updated_vi_at' => Helper::getMongoDate($now),
                            'so_diem_vi_chiet_khau' => $o['so_diem_vi_chiet_khau'] - $toSaveTranViTieuDung['diem_da_nhan'],
                        ];
                        if ($updated_vi_at['so_diem_vi_chiet_khau'] <= 0) {
                            $updated_vi_at['status'] = Orders::STATUS_DISABLE;
                        }
                        if (!isset($order['moc_tien'])) {
                            $updated_vi_at['moc_tien'] = $o['moc_tien'];
                        }
                        if (!isset($order['start_updated_vi_at'])) {
                            $updated_vi_at['start_updated_vi_at'] = Helper::getMongoDate($now);
                        }
                        $o['so_diem_vi_chiet_khau'] = $updated_vi_at['so_diem_vi_chiet_khau'];
                        Orders::where('_id', $o['_id'])->update($updated_vi_at);

                        $whereViTieuDung = [
                            'account' => $o['tai_khoan_nguon']['account'],
                        ];

                        $exitsViTieuDung = ViTieuDung::where($whereViTieuDung)->first();
                        $total_money = 0.00;
                        $total_money += $toSaveTranViTieuDung['diem_da_nhan'];
                        ViChietKhau::CongTruVi($o['tai_khoan_nguon']['account'], $total_money, false, true);
                        if (!$exitsViTieuDung) {
                            $toSaveViTieudung = [
                                'total_money' => $total_money,
                                'account' => $o['tai_khoan_nguon']['account'],
                                'status' => ViTieuDung::STATUS_ACTIVE,
                                'created_at' => Helper::getMongoDate(),
                            ];
                            $idViTieuDung = ViTieuDung::insertGetId($toSaveViTieudung);
                            Logs::createLogNew([
                                'type' => Logs::TYPE_UPDATED,
                                'object_id' => (string)$idViTieuDung,
                                'note' => 'Ví tiêu dùng của acc: ' . @$toSaveViTieudung['account'] . ' đã được cập nhật thêm MPG từ đơn hàng ' . (string)$o['_id']
                            ], ViTieuDung::table_name, [], ViTieuDung::find($idViTieuDung)->toArray());
                        } else {
                            $total_money += $exitsViTieuDung['total_money'];
                            $toSaveViTieudung = [
                                'total_money' => $total_money,
                            ];
                            ViTieuDung::where($whereViTieuDung)->update($toSaveViTieudung);
                            Logs::createLogNew([
                                'type' => Logs::TYPE_UPDATED,
                                'object_id' => (string)$exitsViTieuDung['_id'],
                                'note' => 'Ví tiêu dùng của acc: ' . @$exitsViTieuDung['account'] . ' đã được cập nhật thêm MPG từ đơn hàng ' . (string)$o['_id']
                            ], ViTieuDung::table_name, $exitsViTieuDung->toArray(), ViTieuDung::find($exitsViTieuDung['_id'])->toArray());
                        }
                        /*$root = $o['tai_khoan_nguon'];
                        $congthuc = Orders::getPreCentChietKhauTieuDungForF();
                        Customer::ChiaTienChoF($root, $o, $congthuc, $vihoahong = false, $vitieudung = true);*/
                    }
                }
                else if(isset($o['debt']) && $o['debt'] == Orders::DEBT_NO || !isset($o['mpmart'])) {
                    // case nhảy 0.5% vào ví tiêu dùng
                    /*if(!isset($o['so_diem_duoc_nhan'])) {
                        $o['so_diem_duoc_nhan'] = $o['so_diem_vi_chiet_khau'];
                    }*/

                    $percent = Orders::getEveryDayPercentChietKhauTieuDungDebtNo(@$o['percents']);
                    if (!isset($o['moc_tien'])) {
                        $o['moc_tien'] = $o['so_diem_vi_chiet_khau'] * $percent;
                    }
                    $toSaveTranViTieuDung = [
                        'diem_da_nhan' => $o['moc_tien'],
                        'created_by' => [],
                        'created_at' => Helper::getMongoDate(),
                        'status' => Transaction::STATUS_ACTIVE,
                        'type_giaodich' => Transaction::CHIETKHAU_TIEUDUNG,
                        'detail_type_giaodich' => Helper::formatPercent($percent). '%/ngày từ ví chiết khấu -> tiêu dùng',
                        'object' => Transaction::VITIEUDUNG,
                        'tai_khoan_nguon' => $o['tai_khoan_nguon'],
                        'tai_khoan_nhan' => $o['tai_khoan_nhan'],
                        'order_id' => $o['_id'],
                        'don_lui_ngay_thang07' => true,
                    ];
                    Transaction::insertGetId($toSaveTranViTieuDung);

                    // cập nhật đơn đã nhảy tiền theo ngày
                    $order = Orders::find($o['_id']);
                    $updated_vi_at = [
                        'updated_vi_at' => Helper::getMongoDate($now),
                        'so_diem_vi_chiet_khau' => $o['so_diem_vi_chiet_khau'] - $toSaveTranViTieuDung['diem_da_nhan'],
                    ];
                    if($updated_vi_at['so_diem_vi_chiet_khau'] <= 0) {
                        $updated_vi_at['status'] = Orders::STATUS_DISABLE;
                    }
                    if (!isset($order['moc_tien'])) {
                        $updated_vi_at['moc_tien'] = $o['moc_tien'];
                    }
                    if(!isset($order['start_updated_vi_at'])) {
                        $updated_vi_at['start_updated_vi_at'] = Helper::getMongoDate($now);
                    }
                    $o['so_diem_vi_chiet_khau'] = $updated_vi_at['so_diem_vi_chiet_khau'];
                    Orders::where('_id', $o['_id'])->update($updated_vi_at);

                    // case update vào ví tích luỹ
                    $whereViTieuDung = [
                        'account' => $o['tai_khoan_nguon']['account'],
                    ];

                    $exitsViTieuDung = ViTieuDung::where($whereViTieuDung)->first();
                    $total_money = 0;
                    $total_money += $toSaveTranViTieuDung['diem_da_nhan'];
                    ViChietKhau::CongTruVi($o['tai_khoan_nguon']['account'], $total_money, false, true);
                    if(!$exitsViTieuDung) {
                        $toSaveViTieudung = [
                            'total_money' => $total_money,
                            'account' => $o['tai_khoan_nguon']['account'],
                            'status' => ViTichLuy::STATUS_ACTIVE,
                            'created_at' => Helper::getMongoDate(),
                        ];
                        $idViTieuDung = ViTieuDung::insertGetId($toSaveViTieudung);
                        Logs::createLogNew([
                            'type' => Logs::TYPE_CREATE,
                            'object_id' => (string)$idViTieuDung,
                            'note' => 'Ví tiêu dùng của acc: ' . @$toSaveViTieudung['account'] . ' đã được cập nhật thêm MPG từ đơn hàng ' . (string)$o['_id']
                        ], ViTieuDung::table_name, [], ViTieuDung::find($idViTieuDung)->toArray());
                    }else {
                        $total_money += $exitsViTieuDung['total_money'];
                        $toSaveViTieudung = [
                            'total_money' => $total_money,
                        ];
                        ViTieuDung::where($whereViTieuDung)->update($toSaveViTieudung);
                        Logs::createLogNew([
                            'type' => Logs::TYPE_UPDATED,
                            'object_id' => (string)$exitsViTieuDung['_id'],
                            'note' => 'Ví tiêu dùng của acc: ' . @$exitsViTieuDung['account'] . ' đã được cập nhật thêm MPG từ đơn hàng ' . (string)$o['_id']
                        ], ViTieuDung::table_name, $exitsViTieuDung->toArray(), ViTieuDung::find((string)$exitsViTieuDung['_id'])->toArray());

                    }
                    $root = $o['tai_khoan_nguon'];
                    $o['so_diem_duoc_nhan'] = $toSaveTranViTieuDung['diem_da_nhan'];
                    $congthuc = Orders::getPreCentChietKhauTieuDungForF(@$o['percents']);
                    Customer::ChiaTienChoFCuaChietKhauTieuDungMoiNgay($root, $o, $congthuc, $vihoahong = false, $vitieudung = true);
                }
                $count++;
            }
        }
        dump($count);
    }
}
