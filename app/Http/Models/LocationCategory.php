<?php


namespace App\Http\Models;


class LocationCategory extends Location
{
    const table_name = 'io_locations_category';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = TRUE;
}