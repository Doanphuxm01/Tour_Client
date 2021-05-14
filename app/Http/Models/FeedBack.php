<?php
namespace App\Http\Models;

class FeedBack extends BaseModel 
{
    public $timestamps = false;
    const table_name = 'io_feedbacks';
    protected $table = self::table_name;
    static $unguarded = true;
}