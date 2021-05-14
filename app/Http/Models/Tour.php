<?php


namespace App\Http\Models;


use App\Elibs\eCache;
use App\Elibs\Helper;
use App\Elibs\Pager;

class Tour extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_tours';
    const STATUS_DELETED = 'deleted';
    protected $table = self::table_name;
    static $unguarded = true;
    static $bonus = false;
    static $basicFiledsForList = ['_id', 'name', 'alias', 'status', 'avatar', 'mo_ta_ngan', 'ngay_khoi_hanh', 'ngay_ket_thuc','so_ngay_di_tour', 'dia_diem_den', 'gia_nguoi_lon', 'gia_niem_yet',
        'sku', 'tuyen_tour', 'score', 'ratings', 'so_luong_khach_toi_da', 'so_luong_khach_treo_gio', 'thoi_gian_khoi_hanh_hang_tuan', 'tour_hang_tuan'];
    static $basicFiledsForBooking = ['_id', 'name', 'alias', 'status', 'avatar', 'mo_ta_ngan', 'ngay_khoi_hanh', 'ngay_ket_thuc','so_ngay_di_tour', 'don_vi_tien_te', 'thoi_gian_khoi_hanh_hang_tuan', 'tour_hang_tuan',
        'dia_diem_den', 'gia_nguoi_lon', 'gia_niem_yet', 'dia_diem_khoi_hanh', 'sku', 'tuyen_tour', 'score', 'ratings', 'so_luong_khach_toi_da', 'so_luong_khach_treo_gio', 'gia_tre_em', 'gia_tre_nho'];
    const RANGE_TIME = 60;
    static $objectRegister = [

        'product' => [
            'key' => 'product',
            'name' => 'Sản phẩm',
        ],

    ];

    const TOURHANGTUAN = 'TOURHANGTUAN';
    const TOURHANGNGAY = 'TOURHANGNGAY';
    const TOURLE = 'TOURLE';
    static $TOURLETOURHANGNGAY = [
        self::TOURHANGTUAN => [
            'key' => self::TOURHANGTUAN,
            'name' => 'Tour hàng tuần',
        ],
        self::TOURHANGNGAY => [
            'key' => self::TOURHANGNGAY,
            'name' => 'Tour hàng ngày',
        ],
        self::TOURLE => [
            'key' => self::TOURLE,
            'name' => 'Tour lẻ',
        ],
    ];
    public function isDeleteBonus(){
        $this->qua_tang = self::STATUS_DELETED;
        return $this->save();
        // Product::find($id)->isDeleteBonus();
    }


    public static function getProductByIdsCate($where = [], $groupBy = false,  $limit = false)
    {
        $listItem = self::where($where);
        if($groupBy) {
            $listItem = $listItem->groupBy($groupBy);
        }

        if($limit) {
            $listItem = $listItem->limit($limit);
        }

        return $listItem->select(self::$basicFiledsForList)->get()->toArray();
    }

    public static function getProductByIds($ids, $keyBy = '_id')
    {
        $cates = self::table(self::table_name)->whereIn('_id', $ids)->get();
        if($keyBy) {
            $cates = $cates->keyBy('_id');
        }
        if ($cates) {
            return collect($cates)->map(
                function ($item) {
                    return [
                        'id' => $item['_id'],
                        'name' => $item['name'],
                        'alias' => $item['alias']
                    ];
                }
            )->toArray();
        }
        return null;
    }

    static function getById($appId)
    {
        $item = eCache::get(__FUNCTION__ . $appId);
        if ($item) {
            return $item;
        }
        $item = self::where('_id', $appId)->first();
        if ($item) {
            $item = $item->toArray();
        }
        eCache::add(__FUNCTION__ . $appId, $item);

        return $item;
    }

    static function getBySku($appId)
    {
        $item = eCache::get(__FUNCTION__ . $appId);
        if ($item) {
            return $item;
        }
        $item = self::where('sku', $appId)->first();

        if ($item) {
            $item = $item->toArray();
        }

        eCache::add(__FUNCTION__ . $appId, $item);
        return $item;
    }

}