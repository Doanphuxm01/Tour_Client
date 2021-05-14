<?php


namespace App\Http\Controllers\AdminBooking;


use App\Elibs\eView;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Controllers\Controller;
use App\Http\Models\Booking;
use App\Http\Models\Member;
use App\Http\Models\MetaData;
use Illuminate\Http\Request;

class MngBooking extends Controller
{
    public function index()
    {
        $tpl = [];
        $itemPerPage = (int)Request::capture()->input('row', 20);
        HtmlHelper::getInstance()->setTitle('Hệ thống quản trị cổ phần Minh Phúc');
        $where = [];
        $where['created_by.id'] = Member::getCurentId();
        $listObj = Booking::where($where)->orderBy('_id', 'DESC');
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        $tpl['lsObj'] = $listObj;
        return eView::getInstance()->setViewBackEnd(__DIR__, 'list', $tpl);
    }
}