<?php


namespace App\Http\Models;


class Combo extends BaseModel
{
    const table_name = 'io_combo';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = TRUE;
}