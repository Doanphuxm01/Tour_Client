<?php


namespace App\Http\Models;


use App\Elibs\eCache;
use App\Elibs\Helper;
use Carbon\Carbon;

class TourKhoiHanh extends Tour
{
    const table_name = 'io_tours_khoi_hanh';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = true;
    public $timestamps = false;

    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Hiển thị', 'text-action' => 'Hiển thị'],
            self::STATUS_INACTIVE => ['id' => self::STATUS_INACTIVE, 'style' => 'secondary', 'text' => 'Không hiển thị', 'text-action' => 'Không hiển thị'],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
            if($return) {
                return $listStatus[$selected];
            }
        }

        return $listStatus;
    }

    static function getAllByParentId($id) {
        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');
        $data =  self::where([
            'ngay_khoi_hanh' => [
                '$gte' => Helper::getMongoDate($timeStart, '/', true),
            ],
        ])->where([
            ['status', self::STATUS_ACTIVE],
            ['parent_id', $id],
        ])->orderBy('ngay_khoi_hanh', 'ASC')->get()->toArray();
        return $data;
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