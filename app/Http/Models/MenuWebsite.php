<?php


namespace App\Http\Models;


class MenuWebsite extends BaseModel
{
    const table_name = 'io_menu_website';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = TRUE;
    const HEADER = 'HEADER';
    const FOOTER = 'FOOTER';
    static $basicFiledsForList = ['_id', 'link', 'alias', 'name', 'status', 'avatar', 'mo_ta_ngan', 'loai_menu'];
    static $menuRegister = [
        self::HEADER => [
            'id' => self::HEADER,
            'name' => 'Menu Header'
        ],
        self::FOOTER => [
            'id' => self::FOOTER,
            'name' => 'Menu Footer'
        ]
    ];

    public static $objectRegister = [
        TourCategory::table_name => [
            'name' => 'Danh mục tour',
            'id' => TourCategory::table_name,
        ],
        Cate::table_name => [
            'name' => 'Danh mục bài viết',
            'id' => Cate::table_name,
        ],
        Post::table_name => [
            'name' => 'Bài viết',
            'id' => Post::table_name,
        ],
    ];

    public static function buildTree(array &$menu_data, $parent_id = 0, $selected = [], $loop = 0)
    {
        $data = [];
        foreach ($menu_data as $k => &$item) {
            if (@$item['parent_id'] === $parent_id) {
                $children = self::buildTree($menu_data, (string)$item['_id'], [], $loop);
                if ($children) {
                    $item['children'] = $children;
                }
                if(isset($data[$item['parent_id']])) {
                    $data[(string)$item['_id']] = $item;
                }else {
                    $data[$item['parent_id']] = $item;
                }
                unset($menu_data[$k]);
            }
        }

        return $data;
    }
}