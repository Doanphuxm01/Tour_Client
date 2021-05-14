<?php


namespace App\Http\Models;


class StaticPage extends Post
{
    public $timestamps = false;
    const table_name = 'io_staticpage';
    protected $table = self::table_name;
    static $unguarded = true;
    static $basicFiledsForList = ['name', 'alias', 'brief', 'avatar', 'type', 'link_source', 'object', 'categories', 'departments', 'created_at', 'updated_at', 'actived_at'];

}