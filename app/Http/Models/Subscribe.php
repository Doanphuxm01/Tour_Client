<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_subscribe';
    protected $table = self::table_name;
    static $unguarded = true;
}
