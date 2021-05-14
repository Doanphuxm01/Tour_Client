<?php


namespace App\Http\Models;

class TourCategory extends Cate
{
    const table_name = 'io_tours_category';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = true;
    public $timestamps = false;
    static $basicFiledsForList = ['_id', 'name', 'alias', 'status', 'avatar', 'sku'];
}