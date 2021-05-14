<?php


namespace App\Http\Models;


class Booking extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_booking';
    protected $table = self::table_name;
    static $unguarded = true;

    static function getListStatus($selected = FALSE, $return = FALSE)
    {
        $listStatus = [
            self::STATUS_PENDING => [
                'id' => self::STATUS_PENDING, 'alias' => 'pending',
                'style' => 'warning',
                'icon' => 'icon-file-download',
                'text' => 'Đang xử lý',
                'text-action' => 'Đang xử lý',
                'group-action' => [
                    /*self::STATUS_NO_PAID,*/ self::STATUS_PROCESS_DONE, self::STATUS_DELETED
                ]
            ],
            self::STATUS_PROCESS_DONE => [
                'id' => self::STATUS_PROCESS_DONE, 'alias' => 'done',
                'style' => 'info',
                'icon' => 'icon-checkmark4',
                'text' => 'Đã xử lý',
                'text-action' => 'Đã xử lý',
                'group-action' => []
            ],
            self::STATUS_DELETED => [
                'id' => self::STATUS_DELETED, 'alias' => 'deleted',
                'style' => 'danger',
                'icon' => 'icon-trash',
                'text' => 'Đã xóa',
                'text-action' => 'Đã xóa',
                'group-action' => []
            ],
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