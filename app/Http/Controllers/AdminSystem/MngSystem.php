<?php

namespace App\Http\Controllers\AdminSystem;

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

class MngSystem extends Controller
{

    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->info();
            return $this->changepass();
        }
    }


    public function info()
    {
        HtmlHelper::getInstance()->setTitle('Thông tin cá nhân');
        $tpl['dataGroup'] = MetaData::getAllByType();
        $tpl['GENDER'] = Member::$GENDER;
        // eView::getInstance()->setMsgInfo("Chào mừng bạn đến với hệ thống quản lý dữ liệu");

        return eView::getInstance()->setViewBackEnd(__DIR__, 'dashboard', $tpl);
    }

    public function _save()
    {
        HtmlHelper::getInstance()->setTitle('Thông tin cá nhân');
        $id = Member::getCurentID();
        $obj = Request::capture()->input('obj', []);

        // $obj['name'] = strip_tags(trim($obj['name']));
        // $obj['phone'] = strip_tags(trim($obj['phone']));
        // $obj['email'] = strip_tags(trim($obj['email']));
        // $obj['addr'] = strip_tags(trim($obj['addr']));
        // $obj['can_cuoc_cong_dan'] = strip_tags(trim($obj['can_cuoc_cong_dan']));
        // $obj['email'] = strip_tags(trim($obj['email']));


        $objToSave = [
            'name' => (isset($obj['name']) && $obj['name']) ? strip_tags(trim($obj['name'])) : '',
            'email' => (isset($obj['email']) && $obj['email']) ? $obj['email'] : '',
            'phone' => (isset($obj['phone']) && $obj['phone']) ? $obj['phone'] : '',
            'gender' => (isset($obj['gender']) && $obj['gender']) ? $obj['gender'] : '',
            'can_cuoc_cong_dan' => (isset($obj['can_cuoc_cong_dan']) && $obj['can_cuoc_cong_dan']) ? $obj['can_cuoc_cong_dan'] : '',
            'addr' => (isset($obj['addr']) && $obj['addr']) ? $obj['addr'] : '',
            'birthday' => (isset($obj['birthday']) && $obj['birthday']) ? Helper::getMongoDate($obj['birthday']) : '',
            'updated_at' => Helper::getMongoDate(),
        ];        
            // cập nhật
            $currentObj = Customer::getById($id);

            if (!$currentObj) {
                return eView::getInstance()->getJsonError('Không tìm thấy đối tượng. Vui lòng kiểm tra lại');
            }
            Customer::where('_id', $id)->update($objToSave);
            Logs::createLog(
            [
                'type' => Logs::TYPE_UPDATED,
                'object_id' => $id,
                'note' => "Thông tin" . $objToSave['name'] . ' được được sửa bởi '.Customer::getById($id),
            ], Customer::table_name, $currentObj->toArray(), Customer::find($id)->toArray());
        $return = [
            'redirect' => route('AdminSystem')
        ];
        return eView::getInstance()->getJsonSuccess('Cập nhật thông tin thành công', $return);
    }

    public function changepass()
    {
        HtmlHelper::getInstance()->setTitle('Đổi mật khẩu');
        $tpl = [];
        // eView::getInstance()->setMsgInfo("Chào mừng bạn đến với hệ thống quản lý dữ liệu");

        return eView::getInstance()->setViewBackEnd(__DIR__, 'input', $tpl);

    }
    public function _change()
    {
        HtmlHelper::getInstance()->setTitle('Đổbẻi mật khẩu');
        $id = Member::getCurentID();
        $pass = Request::capture()->input('password', []);
        $newPass = Request::capture()->input('new-password', []);
        $reNewPass = Request::capture()->input('re-new-password', []);
        $currentMember = Customer::getById($id);
        if (empty($pass)) {
            return eView::getInstance()->getJsonError('Bạn chưa điền mật khẩu cũ');
        }
        if (empty($newPass)) {
            return eView::getInstance()->getJsonError('Mật khẩu mới không được để trống');
        }
        if (empty($reNewPass)) {
            return eView::getInstance()->getJsonError('Bạn chưa nhập lại mật khẩu mới');
        }

        $curPass = @$currentMember['password'];
        if (Member::genPassSave($pass) !== $curPass) {
            return eView::getInstance()->getJsonError('Mật khẩu cũ không đúng');
        }

        if ($newPass !== $reNewPass) {
            return eView::getInstance()->getJsonError('Mật khẩu nhập lại không giống với mật khẩu mới');
        }

        $newPass = Member::genPassSave($newPass);
        Member::where('_id', $id)->update(['password' => $newPass]);
        #region ghi log
        Logs::createLog(
            [
                'type' => Logs::TYPE_UPDATED,
                'data_object' => [],
                'note' => "Thông Tin " . @$currentMember['name'] . ' Đổi tự đổi mật khẩu của mình'.Customer::getById($id),
            ], Logs::OBJECT_ROLE
        );
        #endregion
        $return = [
            'redirect' => route('AdminSystem', ['action' => 'changepass']).'?id='.$id,
        ];
        return eView::getInstance()->getJsonSuccess('Đổi mật khẩu thành công', $return);

    }



}
