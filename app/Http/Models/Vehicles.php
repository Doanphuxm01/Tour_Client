<?php


namespace App\Http\Models;


class Vehicles extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_vehicles';
    const STATUS_DELETED = 'deleted';
    protected $table = self::table_name;
    static $unguarded = true;

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
}