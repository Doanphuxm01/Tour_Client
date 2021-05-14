<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eCache;
use App\Elibs\Helper;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\False_;

class Customer extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_customers';
    protected $table = self::table_name;
    static $unguarded = true;
    static $basicFiledsForList = ['name', 'account', '_id', 'fullname', 'status', 'verified', 'created_at', 'actived_at', 'updated_at', 'updated_by', 'ma_gioi_thieu', 'email', 'phone', 'parent_id', 'chuc_danh', 'level_doanhthu'];
    static $basicFiledsForBuildTree = ['account', '_id', 'parent_id', 'ma_gioi_thieu', 'status', 'name', 'verified', 'phone', 'email', 'level_doanhthu', 'recruits', 'created_at', 'dk_nhan_hoahong_doanhthu'];
    protected $dates = [];
    protected $dateFormat = 'd/m/Y';
    const IS_DAILY = 'daily';// ĐẠI LÝ
    const IS_CTV = 'ctv';// ĐẠI LÝ
    const IS_MPMART = 'mpmart';// ĐẠI LÝ
    const LEVEL = 'step';// ĐẠI LÝ
    const floor = 5;    // số tầng cần lấy

    static function getByPhone($alias)
    {
        $where = [
            'phone' => $alias
        ];
        return self::where($where)->first();
    }

    static function getById($id)
    {
        return self::find($id);
    }

    static function getByEmail($alias)
    {
        $where = [
            'email' => $alias
        ];
        return self::where($where)->first();
    }

    static function getByAccount($account)
    {
        $where = [
            'account' => $account
        ];
        return self::where($where)->first();
    }

    static function getMemberByMaGioiThieu($account)
    {
        if (!$account) {
            return [];
        }
        $where = [
            'ma_gioi_thieu' => $account,
        ];
        $member = static::where($where)->first();
        return $member;
    }

    static function getTaiKhoanToSaveDb($customer)
    {
        return [
            'id'    => @$customer['id'],
            'account' => $customer['account'],
            'email' => @$customer['email'],
            'phone' => @$customer['phone'],
            'verified' => @$customer['verified'],
            'level_doanhthu' => @$customer['level_doanhthu'],
            // 'id'      => Member::getCurentId(),
            // 'name'    => Member::getCurrentName(),
            // 'account' => Member::getCurentAccount(),
            // 'email' => Member::getCurrentEmail(),
        ];
    }


    static function buildLinkMaGioiThieu($ma_gioi_thieu) {
        if(!$ma_gioi_thieu) {
            return 'javascript:void(0);';
        }
        return tv_admin_link('auth/register?ma_gioi_thieu=' . $ma_gioi_thieu . '&token=' . Helper::buildTokenString($ma_gioi_thieu));
    }

    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Đang hoạt động ', 'text-action' => 'Kích hoạt hiển thị'],
            self::STATUS_INACTIVE => ['id' => self::STATUS_INACTIVE, 'style' => 'secondary', 'text' => 'Chờ kích hoạt', 'text-action' => 'Chờ kích hoạt'],
            self::STATUS_DISABLE => ['id' => self::STATUS_DISABLE, 'style' => 'warning', 'text' => 'Khóa', 'text-action' => 'Hủy'],
        ];

        if($selected && !isset($listStatus[$selected])) {
            return false;
        }
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }

        return $listStatus;
    }

    static function getListChucDanh($selected = FALSE)
    {
        $listStatus = [
            self::IS_CTV => ['id' => self::IS_CTV, 'style' => 'primary', 'text' => 'Cộng tác viên ', 'text-action' => 'Cộng tác viên'],
            self::IS_DAILY => ['id' => self::IS_DAILY, 'style' => 'success', 'text' => 'Đại lý', 'text-action' => 'Đại lý'],
            self::IS_MPMART => ['id' => self::IS_MPMART, 'style' => 'warning', 'text' => 'MP Mart', 'text-action' => 'MP Mart'],
        ];

        if($selected && !isset($listStatus[$selected])) {
            return false;
        }
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }

        return $listStatus;
    }

    static function getChucDanh($selected, $case = false)
    {
        $list = self::getListChucDanh($selected);
        if (isset($list[$selected])) {
            return $list[$selected];
        }
        return [
            'id' => 0,
            'style' => 'warning',
            'text' => 'Không xác định: ' . $selected,
            'text-action' => 'Không xác định',
        ];
    }

    static function buildTree(array &$menu_data, $parent_id = '0', $selected = [], $loop = 0) {
        $data = [];
        foreach ($menu_data as $k => &$item) {
            if (@$item['parent_id'] === $parent_id) {
                $children = self::buildTree($menu_data, $item['ma_gioi_thieu']??$item['account']);
                if ($children) {
                    $item['children'] = $children;
                }
                $data[@$item['account']] = $item;
            }
        }
        return $data;
    }

    public static function buildTreeNguoc($cur_id,&$data,$account = '',$dept = 1, &$temp = 0)
    {
        if($temp < $dept || $dept === 'all')
            $cur_item = Customer::select(self::$basicFiledsForBuildTree)->where('status', '!=', Customer::STATUS_INACTIVE)->where(!$cur_id ? 'account' : 'ma_gioi_thieu', !$cur_id ? $account : $cur_id)->first();
        if(!empty($cur_item)) {
            $cur_item = $cur_item->toArray();
            $p_item = Customer::select(self::$basicFiledsForBuildTree)->where('status', '!=', Customer::STATUS_INACTIVE)->where('ma_gioi_thieu', $cur_item['parent_id'])->first();
            if(!empty($p_item)) {
                $p_item = $p_item->toArray();
                $temp++;
                $data[] = $p_item;
                self::buildTreeNguoc($p_item['ma_gioi_thieu'], $data,$p_item['account'],$dept,$temp);
            }
        }
        return $data;
    }
    public static function ChiaTienChoFCuaChietKhauTieuDungMoiNgay($root, $order, $congthuc, $vihoahong = false, $vitieudung = false) {
        $temp = [];
        // danh sách dòng họ gần nhất
        $giaPha = Customer::buildTreeNguoc('', $temp, $root['account'],Customer::floor); // gia phả dòng họ
        if($giaPha) {
            foreach ($giaPha as $k => $g) {
                $saveMoney = [
                    'account' => $g['account'],
                    'total_money' => $order['so_diem_duoc_nhan']*$congthuc[$k],
                    'created_at' => Helper::getMongoDate(),
                    'status' => BaseModel::STATUS_ACTIVE,
                ];
                $saveTransaction = [
                    'account' => $g['account'],
                    'diem_da_nhan' => $order['so_diem_duoc_nhan']*$congthuc[$k],
                    'tai_khoan_nguon' => Customer::getTaiKhoanToSaveDb($root),
                    'tai_khoan_nhan' => Customer::getTaiKhoanToSaveDb($g),
                    'created_at' => Helper::getMongoDate(),
                    'detail_type_giaodich' => Helper::formatPercent($congthuc[$k]). '%/ngày từ ví chiết khấu -> tiêu dùng',
                    'status' => BaseModel::STATUS_ACTIVE,
                    'order_id' => $order['_id']
                ];
                // kiểm tra xem ví của tk này đã được tạo hay chưa.
                if ($vihoahong) {
                    $saveTransaction['type_giaodich'] = '';
                    $viHoaHongOfCus = ViHoaHong::where('account', $g['account'])->first();
                    if($viHoaHongOfCus) {
                        $money = (int)$viHoaHongOfCus['total_money'] + (int)$saveTransaction['diem_da_nhan'];
                        $viHoaHongOfCus->update(['total_money' => $money, 'updated_at' => Helper::getMongoDate()]);
                        Logs::createLog([
                            'type' => Logs::TYPE_UPDATED,
                            'data_object' => $saveTransaction,
                            'note' => "Ví hoa hồng của " . $g['account'] . ' được thêm ' . $money .' MPG từ '. $root['account']
                        ], Logs::OBJECT_HOAHONG);
                    }else {
                        ViHoaHong::insert($saveMoney);
                        Logs::createLog([
                            'type' => Logs::TYPE_CREATE,
                            'data_object' => $saveMoney,
                            'note' => "Ví hoa hồng của " . $g['account'] . ' được thêm ' . $saveMoney['total_money'] .' MPG từ '. $root['account']
                        ], Logs::OBJECT_HOAHONG);
                    }
                    Transaction::insert($saveTransaction);
                }

                if ($vitieudung) {
                    $saveTransaction['type_giaodich'] = Transaction::CHIETKHAU_TIEUDUNG;
                    $viOfCus = ViTieuDung::where('account', $g['account'])->first();
                    if($viOfCus) {
                        $money = (int)$viOfCus['total_money'] + (int)$saveTransaction['diem_da_nhan'];
                        $viOfCus->update(['total_money' => $money, 'updated_at' => Helper::getMongoDate()]);
                        Logs::createLog([
                            'type' => Logs::TYPE_UPDATED,
                            'data_object' => $saveTransaction,
                            'note' => "Ví tiêu dùng của " . $g['account'] . ' được thêm ' . $money .' MPG từ'. $root['account']
                        ], Logs::OBJECT_HOAHONG);
                    }else {
                        ViTieuDung::insert($saveMoney);
                        Logs::createLog([
                            'type' => Logs::TYPE_CREATE,
                            'data_object' => $saveMoney,
                            'note' => "Ví tiêu dùng của " . $g['account'] . ' được thêm ' . $saveMoney['total_money'] .' MPG từ'. $root['account']
                        ], Logs::OBJECT_HOAHONG);
                    }
                    Transaction::insert($saveTransaction);
                }

            }
        }
    }
    public function export_popup()
    {
        $tpl = [];
        return eView::getInstance()->setViewBackEnd(__DIR__, 'export-popup', $tpl);
    }

    public static function getAllCustomerActive($keyBy = false, $toArray = true) {
        $data = self::where('status', self::STATUS_ACTIVE)->get();
        if($keyBy) {
            $data = $data->keyBy($keyBy);
        }
        if($toArray) {
            return $data->toArray();
        }
        return $data;
    }

    public static function getAllCustomerByPluck($pluck) {
        return self::where('status', self::STATUS_ACTIVE)->get()->pluck($pluck)->toArray();
    }

    // hàm build full cây bao gồm cả account gốc
    public static function buildFullTreeNguocBaoGomCaGoc($cur_id,&$data,$account = '',$dept = 1, &$temp = 0)
    {
        $cur_item = Customer::select(self::$basicFiledsForBuildTree)->where('status', '!=', Customer::STATUS_INACTIVE)
            ->where(!$cur_id ? 'account' : 'ma_gioi_thieu', !$cur_id ? $account : $cur_id)->first();
        if(!empty($cur_item)) {
            $cur_item = $cur_item->toArray();
            $p_item = Customer::select(self::$basicFiledsForBuildTree)->where('status', '!=', Customer::STATUS_INACTIVE)
                ->where('ma_gioi_thieu', $cur_item['parent_id'])->first();
            if(!empty($p_item)) {
                $p_item = $p_item->toArray();
                $data[] = $cur_item;
                self::buildTreeNguoc(@$cur_item['ma_gioi_thieu'], $data,$cur_item['account'], 'all');
            }
        }
        return $data;
    }

    static function getLsCustomerByLevelDoanhThu($level_group, $groupBy = false) {
        if(!$level_group) {
            return false;
        }
        $where = [
            'status' => self::STATUS_ACTIVE,
            'level_doanhthu' => [
                '$in' => $level_group
            ],
        ];
        $data = self::where($where)->select(self::$basicFiledsForBuildTree)->get();
        if($groupBy) {
            $data = $data->groupBy('level_doanhthu');
        }
        return $data->toArray();
    }


}
