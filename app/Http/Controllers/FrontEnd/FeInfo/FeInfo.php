<?php

namespace App\Http\Controllers\FrontEnd\FeInfo;

use App\Elibs\Debug;
use App\Elibs\eCalendar;
use App\Elibs\eView;
use App\Elibs\FileHelper;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Controllers\Controller;
use App\Http\Models\Agency;
use App\Http\Models\BaseModel;
use App\Http\Models\Calendar;
use App\Http\Models\Customer;
use App\Http\Models\Document;
use App\Http\Models\Department;
use App\Http\Models\ForumPost;
use App\Http\Models\KhoDiem;
use App\Http\Models\Library;
use App\Http\Models\Logs;
use App\Http\Models\Member;
use App\Http\Models\MetaData;
use App\Http\Models\Orders;
use App\Http\Models\Post;
use App\Http\Models\Profile;
use App\Http\Models\Project;
use App\Http\Models\ProjectPermission;
use App\Http\Models\PurchaseOrder;
use App\Http\Models\Role;
use App\Http\Models\Staff;
use App\Http\Models\Transaction;
use App\Http\Models\ViChietKhau;
use App\Http\Models\ViCongNo;
use App\Http\Models\ViHoaHong;
use App\Http\Models\ViTichLuy;
use App\Http\Models\ViTieuDung;
use App\Http\Models\Cate;
use App\Http\Models\Tour;
use App\Http\Requests;

use App\Mail\MailBase;
use App\Mail\NotificationForStaff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FeInfo extends Controller
{

    function index()
    {
        HtmlHelper::getInstance()->setTitle('Thông tin cá nhân');
        $tpl[] = MetaData::getAllByType();
        // eView::getInstance()->setMsgInfo("Chào mừng bạn đến với hệ thống quản lý dữ liệu");

        return eView::getInstance()->setViewBackEnd(__DIR__, 'index', $tpl);
    }
    function _save()
    {
        HtmlHelper::getInstance()->setTitle('Thông tin cá nhân');
        $url = Request::capture()->input('obj', []);
        $objToSave = [
            'Facebook' => Helper::isLink($url['Facebook']),
            'Youtube' => Helper::isLink($url['Youtube']),
            'Skype' => Helper::isLink($url['Skype']),
            'created_at' => Helper::getMongoDateTime(),
            'created_by' => Member::getCreatedByToSaveDb(),
        ];
        $currentObj = ConfigWebsite::first();
        
        if($currentObj) {
            // cập nhật
            if (!$currentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
            if($objToSave['Facebook'] == false && $objToSave['Youtube'] == false && $objToSave['Skype'] == false){
                return eView::getInstance()->getJsonError('Bạn vui lòng kiểm tra lại đường dẫn theo hợp lệ');
            }
            ConfigWebsite::First()->update($objToSave);
            Logs::createLog(
            [
                'type' => Logs::TYPE_UPDATED,
                'object_id' => (string)$currentObj['_id'],
                'data_object' => $objToSave,
                'note' => ' Mạng xã hội được sửa bởi '.Member::getCurentAccount(),
            ], ConfigWebsite::table_name, $currentObj->toArray(), ConfigWebsite::First()->toArray()
        );}else {
            $objToSave['created_at'] = Helper::getMongoDate();
            $objToSave['created_by'] = Member::getCreatedByToSaveDb();
            $id = ConfigWebsite::insertGetId($objToSave);
            Logs::createLog([
                'object_id' => $id,
                'type' => Logs::TYPE_CREATE,
                'data_object' => $objToSave,
                'note' => ' Mạng xã hội đã được thêm mới bởi '.Member::getCurentAccount()
            ], 
            ConfigWebsite::table_name, [], ConfigWebsite::First()->toArray()
        );}
        $return = [
            'redirect' => route('AdminConfigWebsite', ['action' => 'input']),
        ];
        return eView::getInstance()->getJsonSuccess('Cập nhật thông tin thành công', $return);
//        dd(__DIR__);

    }



}
