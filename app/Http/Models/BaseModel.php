<?php

namespace App\Http\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Elibs\Debug;
use App\Elibs\Helper;
use App\Traits\AutoNextNumber;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    use AutoNextNumber;

    protected $table = '';
    protected $fillable = [];

    public $timestamps = FALSE;


    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_CRALLW = 'cralw-ok';
    const STATUS_DISABLE = 'disabled';
    const STATUS_DELETED = 'deleted';
    const STATUS_DELETED_PHY = 'remove-phy';//xoa vat ly
    const STATUS_PENDING = 'pending';// chờ duyệt
    const STATUS_DRATF = 'dratf';// bản nháp
    const STATUS_PROCESS_OK = 'processed';// Bản xử lý mọi thứ OK và chờ mở dần dần => dùng job để update mở bài này ra hàng ngày mỗi ngày 1 ít
    const STATUS_PROCESS_DONE = 'done';// Bản xử lý mọi thứ OK và chờ mở dần dần => dùng job để update mở bài này ra hàng ngày mỗi ngày 1 ít
    const STATUS_NO_PAID = 'no_paid'; //chưa thanh toán
    const STATUS_NO_PROCESS = 'no_process'; //chưa xử lý
    const STATUS_PROCESSING = 'process'; //đang xử lý
    const STATUS_CANCEL = 'cancel'; //hủy


    const REMOVED_NO = 'no';
    const REMOVED_YES = 'yes';

    const NOCATE = 'no-category';

    /***
     * @param bool|FALSE $selected
     * @return array
     * @note: Định nghĩa và Lấy danh sách các trạng thái của danh mục trong bảng
     */
    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Đang hoạt động ', 'text-action' => 'Kích hoạt hiển thị'],
            self::STATUS_INACTIVE => ['id' => self::STATUS_INACTIVE, 'style' => 'secondary', 'text' => 'Chờ kích hoạt', 'text-action' => 'Chờ kích hoạt'],
            self::STATUS_PROCESSING => ['id' => self::STATUS_PROCESSING, 'style' => 'warning', 'text' => 'Đang xử lý ', 'text-action' => 'Đang xử lý'],
            self::STATUS_NO_PROCESS => ['id' => self::STATUS_NO_PROCESS, 'style' => 'warning', 'text' => 'Chưa xử lý ', 'text-action' => 'Chưa xử lý'],
            self::STATUS_PROCESS_DONE => ['id' => self::STATUS_PROCESS_DONE, 'style' => 'success', 'text' => 'Đã xử lý', 'text-action' => 'Đã xử lý'],
            self::STATUS_DRATF => ['id' => self::STATUS_DRATF, 'style' => 'secondary', 'text' => 'Bản nháp ', 'text-action' => 'Bản nháp'],
            self::STATUS_CANCEL => ['id' => self::STATUS_CANCEL, 'style' => 'warning', 'text' => 'Hủy', 'text-action' => 'Hủy'],
            self::STATUS_DISABLE => ['id' => self::STATUS_DISABLE, 'style' => 'warning', 'text' => 'Không sử dụng', 'text-action' => 'Hủy'],
            self::STATUS_DELETED => ['id' => self::STATUS_DELETED, 'style' => 'danger', 'text' => 'Đã xóa', 'text-action' => 'Đã xóa'],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
            if($return) {
                return $listStatus[$selected];
            }
        }

        return $listStatus;
    }

    /**
     * @param $selected
     * @return array|mixed
     * @note: dùng trong các view tránh if els quá nhiều
     */
    static function getStatus($selected, $case = false)
    {
        if($case) {
            $list = self::getListStatus($selected, $case);
        }else {
            $list = self::getListStatus($selected);
        }


        if (isset($list[$selected])) {
            return $list[$selected];
        } else {
            switch ($selected) {
                case self::STATUS_NO_PAID:
                    {
                        return [
                            'id' => self::STATUS_NO_PAID,
                            'style' => 'warning',
                            'text' => 'Chưa thanh toán ',
                            'text-action' => 'Chưa thanh toán',
                        ];
                    }
                case self::STATUS_NO_PROCESS:
                    {
                        return [
                            'id' => self::STATUS_NO_PROCESS,
                            'style' => 'warning',
                            'text' => 'Chưa xử lý',
                            'text-action' => 'Chưa xử lý',
                        ];
                    }
                case self::STATUS_PROCESS_DONE:
                    {
                        return [
                            'id' => self::STATUS_PROCESS_DONE,
                            'style' => 'success',
                            'text' => 'Đã xử lý',
                            'text-action' => 'Đã xử lý',
                        ];
                    }
                case Orders::DEBT_NO:
                    {
                        return [
                            'id' => Orders::DEBT_NO,
                            'style' => 'success',
                            'text' => 'Không nợ',
                            'text-action' => 'Không nợ',
                        ];
                    }
                case Orders::DEBT_YES:
                    {
                        return [
                            'id' => Orders::DEBT_YES,
                            'style' => 'danger',
                            'text' => 'Ghi nợ',
                            'text-action' => 'Ghi nợ',
                        ];
                    }
                case Customer::IS_MPMART:
                    {
                        return [
                            'id' => Customer::IS_MPMART,
                            'style' => 'danger',
                            'text' => 'MP MART',
                            'text-action' => 'MP MART',
                        ];
                    }
            }
            return [
                'id' => 0,
                'style' => 'warning',
                'text' => 'Không xác định: ' . $selected,
                'text-action' => 'Không xác định',
            ];
        }
    }

    public static function buildLinkDelete($object, $router)
    {

        return admin_link($router . '/_delete?id=' . $object['_id'] . '&token=' . Helper::buildTokenString($object['_id']));
    }


    public static function getNextNumber($table){

        $tableCounter = DB::table('io_counter');
        $counter = $tableCounter->find($table);
        if(!$counter || $counter['count'] == 0 )
        {
            $count = DB::table($table)->count();
            $count++;
        }
        else{
            $count = $counter['count'] + 1;
        }
        if(!$counter)
        {
            $tableCounter->insert(['_id'=>$table,'count'=>$count]);
        }
        else{
            $tableCounter->where(['_id'=>$table])->update(['count'=>$count]);

        }
        return $count;
    }
    public static function getNextNumberWithYear($table){
        $table = $table.date("Y");
        return self::getNextNumber($table);
    }

    public static function checkIsExist($listObj,$field,$value='co'){
        if($value=='co'){
            $listObj = $listObj->where($field, '$exists', true)->where(
                $field, ['$not' => ['$size' => 0]]
            );
        }else{
            $listObj = $listObj->whereRaw([
                '$or' => [
                    [
                        $field => ['$exists' => false]
                    ],
                    [
                        $field => ['$size' => 0]
                    ]
                ]
            ]);
        }
        return $listObj;
    }

    /**
     * @param $listObj
     * @param $field
     * @param $time : 31/02/2018 - 31/05/2018
     */
    public static function helperBuilderQueryByDate($listObj,$field,$time){
        if (is_string($time) && $time) {
            $updated_at_arr = explode('-', $time);
            if ($updated_at_arr && isset($updated_at_arr[0]) && isset($updated_at_arr[1])) {
                $timeStart = trim($updated_at_arr[0]);
                $timeEnd = trim($updated_at_arr[1]);
                if(Helper::validateDateTime($timeStart,'d/m/Y') && Helper::validateDateTime($timeEnd,'d/m/Y')){
                    $timeStart = Helper::getMongoDate($timeStart, '/', true);
                    $timeEnd = Helper::getMongoDate($timeEnd, '/', false);
                    return $listObj->whereBetween($field, array($timeStart, $timeEnd));
                }
            }
        }
        return $listObj;
    }
    public static function table($table)
    {
        return DB::table($table);
    }

    static function createVi($obj) {
        $objToSave = [
            'account' => @$obj['account'],
            'total_money' => @$obj['total_money'],
            'created_at' => Helper::getMongoDateTime(),
            'status' => static::STATUS_ACTIVE,
        ];
        if(isset($obj['group_doanhthu_theo_thang'])) {
            $objToSave['group_doanhthu_theo_thang'] = $obj['group_doanhthu_theo_thang'];
        }
        return self::insertGetId($objToSave);
    }

    static function getViByAccount($account) {
        $where = [
            'account' => $account,
            'status' => ViTieuDung::STATUS_ACTIVE,
        ];

        return self::where($where)->first();
    }

    static function checkExistsVi($account, $type, $option) {
        $sotiengiaodich = (double)$option['so_tien_giao_dich'];
        switch ($type) {
            case ViCoTuc::table_name :
                $class = ViCoTuc::class;
                $logNote = 'Ví cổ tức của thành viên "' . $account . '" đã được cộng thêm ' . Helper::formatMoney($sotiengiaodich) . ' từ đơn mua "' . (string)$option['order_id'] . '" của tài khoản "' . $option['tai_khoan_nguon']['account'] . '"';
                $typeViNhanTien = Transaction::VICOTUC;
                break;
            case ViCoPhan::table_name :
                $class = ViCoPhan::class;
                $socophangiaodich = $option['so_diem_giao_dich'];
                $logNote = 'Ví cổ phần của thành viên "' . $account . '" đã được cộng thêm ' . Helper::formatMoney($socophangiaodich, '.', ' CP') . ' từ đơn mua "' . (string)$option['order_id'] . '" của tài khoản "' . $option['tai_khoan_nguon']['account'] . '"';
                $typeViNhanTien = Transaction::VICOPHAN;
                break;
            case ViTieuDung::table_name :
                $class = ViTieuDung::class;
                $logNote = 'Ví tiêu dùng của thành viên "' . $account . '" đã được cộng thêm ' . Helper::formatMoney($sotiengiaodich, '.', ' đ') . ' từ đơn mua "' . (string)$option['order_id'] . '" của tài khoản "' . $option['tai_khoan_nguon']['account'] . '"';
                $typeViNhanTien = Transaction::VITIEUDUNG;
                break;
            case ViChietKhau::table_name :
                $class = ViChietKhau::class;
                $logNote = 'Ví chiết khấu của thành viên "' . $account . '" đã được cộng thêm ' . Helper::formatMoney($sotiengiaodich, '.', ' đ') . ' từ đơn mua "' . (string)$option['order_id'] . '" của tài khoản "' . $option['tai_khoan_nguon']['account'] . '"';
                $typeViNhanTien = Transaction::VICHIETKHAU;
                break;
            default:
                $logNote = '';
                $typeViNhanTien = '';
                break;
        }
        $vi = $class::getViByAccount($account);
        $total_money = $sotiengiaodich;
        if(isset($socophangiaodich)) {
            $total_money = $socophangiaodich;
        }
        $dataBeforSave = [];
        if($vi) {
            $total_money += (double)$vi['total_money'];
            if($total_money <= 0) {
                $total_money = 0;
            }
            $class::where('account', $account)->update(['total_money' => $total_money, 'updated_at' => Helper::getMongoDate()]);
            $dataBeforSave = $vi->toArray();
        }else {
            $objToSave = [
                'account' => $account,
                'total_money' => $total_money,
            ];
            $id = $class::createVi($objToSave);
            $vi['_id'] = (string)$id;
        }
        $dataAffterSave = $class::getViByAccount($account)->toArray();
        Logs::createLog([
            'type' => Logs::TYPE_UPDATED,
            'object_id' => $vi['_id'],
            'note' => $logNote
        ], $type, $dataBeforSave, $dataAffterSave);
        $objTranToSave = [
            'so_tien_giao_dich' => $sotiengiaodich,
            'tai_khoan_nguon' => Customer::getTaiKhoanToSaveDb($option['tai_khoan_nguon']),
            'tai_khoan_nhan' => Customer::getTaiKhoanToSaveDb($option['tai_khoan_nhan']),
            'vi_nhan_tien' => $typeViNhanTien,
            'type_giaodich' => @$option['type_giaodich'],
            'order_id' => $option['order_id'],
            'created_at' => Helper::getMongoDateTime(),
            'status' => static::STATUS_ACTIVE,
        ];
        if($type == ViCoPhan::table_name) {
            $objTranToSave['so_diem_giao_dich'] = @$socophangiaodich;
        }
        Transaction::createTransaction($objTranToSave);
    }

    static function CongTruVi($account, $total_money, $table_vi) {
        if($total_money < 0) {
            $n = 'bị trừ';
        }else {
            $n = 'cộng thêm';
        }
        switch ($table_vi) {
            case ViCoPhan::table_name:
                $class = ViCoPhan::class;
                $logNote = 'Ví cổ phần của thành viên "' . $account . '" đã '. $n .' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViCoTuc::table_name:
                $class = ViCoTuc::class;
                $logNote = 'Ví cổ tức của thành viên "' . $account . '" đã '. $n .' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViChietKhau::table_name:
                $class = ViChietKhau::class;
                $logNote = 'Ví chiết khấu của thành viên "' . $account . '" đã '. $n .' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViTieuDung::table_name:
                $class = ViTieuDung::class;
                $logNote = 'Ví tiêu dùng của thành viên "' . $account . '" đã '. $n .' ' . Helper::formatMoney($total_money) . '.';
                break;
            default:
                return false;
                break;
        }
        $vi = $class::getViByAccount($account);
        $dataBeforSave = [];
        if($vi) {
            $total_money += (double)$vi['total_money'];
            if($total_money <= 0) {
                $total_money = 0;
            }
            $class::where('account', $account)->update(['total_money' => $total_money, 'updated_at' => Helper::getMongoDate()]);
            $dataBeforSave = $vi->toArray();
        }else {
            $objToSave = [
                'account' => $account,
                'total_money' => $total_money,
            ];
            $id = $class::createVi($objToSave);
            $vi['_id'] = (string)$id;
        }
        $dataAffterSave = $class::getViByAccount($account)->toArray();
        Logs::createLog([
            'type' => Logs::TYPE_UPDATED,
            'object_id' => $vi['_id'],
            'note' => $logNote
        ], $table_vi, $dataBeforSave, $dataAffterSave);
    }


    static function getListViChuyenDiem($selected = FALSE)
    {
        $listStatus = [
            self::OBJECT_VITICHLUY => ['id' => self::OBJECT_VITICHLUY, 'style' => 'success', 'text' => 'Ví tích lũy', 'text-action' => 'Ví tích lũy'],
            self::OBJECT_VIHOAHONG => ['id' => self::OBJECT_VIHOAHONG, 'style' => 'success', 'text' => 'Ví hoa hồng', 'text-action' => 'Ví hoa hồng'],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
        }

        return $listStatus;
    }
    static function isDeleted($obj, $force_root = true)
    {
        if ($force_root) {
            if (Member::isRoot()) {
                return false;
            }
        }
        return isset($obj['removed']) && $obj['removed'] == self::REMOVED_YES;
    }


    static function CongTruViVer2($account, $total_money, $table_vi) {
        if($total_money < 0) {
            $n = 'bị trừ';
        }else {
            $n = 'cộng thêm';
        }
        switch ($table_vi) {
            case TongDoanhThu::table_name:
                $class = TongDoanhThu::class;
                $group_doanhthu_theo_thang = true;
                $logNote = 'Tổng doanh thu của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViHoaHong::table_name:
                $class = ViHoaHong::class;
                $logNote = 'Ví hoa hồng của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViTieuDung::table_name:
                $class = ViTieuDung::class;
                $logNote = 'Ví tiêu dùng của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            case ViTieuDungSiLe::table_name:
                $class = ViTieuDungSiLe::class;
                $logNote = 'Ví tiêu dùng sỉ lẻ của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            case KhoDiem::table_name:
                $class = KhoDiem::class;
                $logNote = 'Kho điểm của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            case KhoDiemSile::table_name:
                $class = KhoDiemSile::class;
                $logNote = 'Kho điểm sỉ lẻ của thành viên "' . $account . '" đã '.$n.' ' . Helper::formatMoney($total_money) . '.';
                break;
            default:
                return false;
                break;
        }
        $vi = $class::getViByAccount($account);
        $dataBeforSave = [];
        $currentMonth = date('m');
        if($vi) {
            $dataBeforSave = $vi->toArray();
            $sotiengiaodich = $total_money;
            $total_money += (double)$vi['total_money'];
            if($total_money <= 0) {
                $total_money = 0;
            }
            $objToSave = ['total_money' => $total_money, 'updated_at' => Helper::getMongoDate()];

            if(isset($group_doanhthu_theo_thang)) {
                if(isset($vi['group_doanhthu_theo_thang'][0])) {
                    $currentDTMonth = $sotiengiaodich;
                    $objToSave['group_doanhthu_theo_thang'] = $vi['group_doanhthu_theo_thang'];

                    foreach ($vi['group_doanhthu_theo_thang'] as $key => $item) {
                        if($currentMonth == @$item['month']) {
                            $existsMonth = true;
                            $item['total_money'] += $sotiengiaodich;
                            $currentDTMonth = $item['total_money'];
                            $objToSave['group_doanhthu_theo_thang'][$key] = ['month' => $currentMonth, 'total_money' => $currentDTMonth];
                            break;
                        }else {
                            $existsMonth = false;
                        }
                    }
                    if(!$existsMonth) {
                        // nếu chưa tồn tại tháng hiện tại thì update
                        $objToSave['group_doanhthu_theo_thang'][] = ['month' => $currentMonth, 'total_money' => $currentDTMonth];
                    }
                    if(isset($objToSave['group_doanhthu_theo_thang'][3])) {
                        $doanhthuthangdau = array_shift($objToSave['group_doanhthu_theo_thang']);
                        $objToSave['total_money'] -= $doanhthuthangdau['total_money'];
                    }
                }else {
                    $objToSave['group_doanhthu_theo_thang'] = [
                        ['month' => $currentMonth, 'total_money' => $total_money]
                    ];
                }
            }
            $class::where('account', $account)->update($objToSave);

        }else {
            $objToSave = [
                'account' => $account,
                'total_money' => $total_money,
            ];
            if(isset($group_doanhthu_theo_thang)) {
                $objToSave['group_doanhthu_theo_thang'] = [
                    ['month' => $currentMonth, 'total_money' => $total_money]
                ];
            }

            $id = $class::createVi($objToSave);
            $vi['_id'] = (string)$id;
        }
        $dataAffterSave = $class::getViByAccount($account);
        if($dataAffterSave) {
            $dataAffterSave = $dataAffterSave->toArray();
            Logs::createLog([
                'type' => Logs::TYPE_UPDATED,
                'object_id' => $vi['_id'],
                'note' => $logNote
            ], $table_vi, $dataBeforSave, $dataAffterSave);
        }

        return $dataAffterSave;
    }

    static function getBySkuNeId($sku, $id) {
        if (!Helper::isMongoId($id)) {
            $where = [
                'sku' => $sku,
            ];
        }else {
            $where = [
                'sku' => $sku,
                '_id' => [
                    '$ne' => Helper::getMongoId($id)
                ]
            ];
        }
        return self::where($where)->first();
    }

    static function getById($id) {
        if (!Helper::isMongoId($id)) {
            return NULL;
        }
        $where = [
            '_id' => Helper::getMongoId($id)
        ];
        return self::where($where)->first();
    }

    static function getByAlias($string) {
        $item = self::where('alias', $string)->first();
        if ($item) {
            return $item->toArray();
        }
        return $item;
    }

    static function getAll($where = [], $select = [], $keyBy = '_id', $limit = false, $groupBy = false) {
        if(!$where) {
            $where = [
                'status' => self::STATUS_ACTIVE
            ];
        }
        $lsObj = self::where($where);
        if(!$select) {
            $select = ['_id', 'name', 'alias', 'status', 'avatar', 'SEO'];
        }
        $lsObj = $lsObj->select($select);
        if($limit && $groupBy == false) {
            $lsObj = $lsObj->limit($limit);
        }
        $lsObj = $lsObj->orderBy('_id', 'DESC')->get()->keyBy($keyBy);
        if($groupBy) {
            $lsObj = $lsObj->groupBy($groupBy);
            if($limit) {
                $lsObj = $lsObj->map(function($deal, $k) use ($limit) {
                    return $deal->take($limit);
                });
            }
        }
        return $lsObj->toArray();

    }

    static function getGroupByUnwindAll($where = [], $select = [], $keyBy = '_id', $limit = false, $groupBy = false) {
        if(!$where) {
            $where = [
                'status' => self::STATUS_ACTIVE
            ];
        }
        $lsObj = self::where($where);
        if(!$select) {
            $select = ['_id', 'name', 'alias', 'status', 'avatar', 'SEO'];
        }
        $lsObj = $lsObj->select($select);
        if($limit && $groupBy == false) {
            $lsObj = $lsObj->limit($limit);
        }
        $lsObj = $lsObj->orderBy('_id', 'DESC')->get()->keyBy($keyBy);
        if($groupBy) {
            $lsObj = $lsObj->groupBy($groupBy);
            if($limit) {
                $lsObj = $lsObj->map(function($deal, $k) use ($limit) {
                    return $deal->take($limit);
                });
            }
        }
        return $lsObj->toArray();

    }

    static function getToSaveDb($obj)
    {
        $objToSave = [
            'id'      => (string)$obj['_id']??$obj['id'],
            'name'    => $obj['name'],
        ];
        if(@$obj['sku']) {
            $objToSave['sku'] = $obj['sku'];
        }
        return $objToSave;
    }

    static function getByParentId($parent_id, $limit = false) {
        $lsObj = self::where('parent_id', $parent_id);
        if($limit) {
            $lsObj = $lsObj->limit($limit);
        }
        return $lsObj->get()->toArray();
    }
}
