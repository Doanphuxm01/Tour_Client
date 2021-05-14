<?php


namespace App\Http\Models;


class ConfigWebsite extends BaseModel
{
    const table_name = 'io_config_website';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = TRUE;

    const SOCIAL = 'SOCIAL';
    const HOMEPAGE = 'HOMEPAGE';

}
