<?php


namespace App\Http\Models;


class Videos extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_videos';
    protected $table = self::table_name;
    static $unguarded = true;
    static $basicFiledsForList = ['_id', 'status', 'name', 'video_id', 'link', 'image'];
}