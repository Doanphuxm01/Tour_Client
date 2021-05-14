<?php

namespace App\Http\Models;


use App\Elibs\eView;
use Illuminate\Support\Facades\DB;

class Menu extends BaseModel
{
    public $timestamps = FALSE;
    const table_name = 'io_menu';
    protected $table = self::table_name;
    static $unguarded = TRUE;


    static function getMainMenuBackEnd($selected = '')
    {
        $item = [
            'label' => 'Cá nhân',
            'group' => 'AdminSystem',
            'link' => admin_link('staff/my-info'),
            'icon' => '<i class="icon-stack3"></i>',
            'action' => '',
            'sub' => [
                [
                    'label' => 'Đổi mật khẩu',
                    'link' => admin_link('news/cate/input'),
                    'icon' => '<i class="icon-database-add"></i>',
                ], [],

            ],
        ];
        $menu['mng_system'] = $item;

        $item = [
            'label' => 'Booking',
            'group' => 'AdminBooking',
            'link' => 'javascript:void(0)',
            'icon' => '<i class="icon-stack3"></i>',
            'action' => '',
            'sub' => [
                [
                    'label' => 'Danh sách tất cả đơn hàng',
                    'link' => admin_link('news'),
                    'icon' => '<i class="icon-newspaper"></i>',
                ], [],
                [
                    'label' => 'Đơn hàng thanh toán tiền mặt',
                    'link' => admin_link('news'),
                    'icon' => '<i class="icon-newspaper"></i>',
                ], [],
                [
                    'label' => 'Đơn hàng thanh toán ATM nội địa',
                    'link' => admin_link('news'),
                    'icon' => '<i class="icon-newspaper"></i>',
                ], [],
                [
                    'label' => 'Đơn hàng thanh toán trực tuyến thẻ Visa, Master Card quốc tế',
                    'link' => admin_link('news'),
                    'icon' => '<i class="icon-newspaper"></i>',
                ], [],


            ],
        ];
        $menu['mng_booking'] = $item;
        #region ITEM MENU


        return $menu;
    }

    static function buildLinkAdmin($router)
    {
        return admin_link('' . $router);
    }


}
