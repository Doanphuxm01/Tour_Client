<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eCache;
use App\Elibs\EmailHelper;
use App\Elibs\Helper;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationForStaff;
use Illuminate\Support\Facades\DB;

class Logs extends BaseModel
{
    public $timestamps = FALSE;
    const table_name = 'io_logs_customer';
    protected $table     = self::table_name;
    static    $unguarded = TRUE;
    const table_search_name = '_logs_io_search';
    const table_logorder_name = '_logs_io_order';

    /**
     * @param $log
     * @param $name
     *  Logs.create({
     * created_by: authMiddle.getCurrent(),
     * type: 'created',,up[date
     * note: 'Thêm thông tin xe',
     * data_object: docToSave,
     * collection_name: 'car',
     * client_info: {
     * agent: req.headers["user-agent"], // User Agent we get from headers
     * referrer: req.headers["referrer"], //  Likewise for referrer
     * ip: req.ip // Get IP - allow for proxy
     * }
     * });
     */
    static function createLogNew($log, $name, $dataBeforSave = [], $dataAffterSave = [], $notification = true)
    {
        /**
         * Ai làm: nhân viên nào
         * Làm gì: insert, delete, update...
         * lúc nào? created_at
         * dự án nào?
         * phòng ban nào? deparment_id, nghĩa là ông này ở phòng nào
         * đối tượng nào? => object_id
         */
        $log['created_by'] = Staff::getCreatedByToSaveDb();
        $log['table'] = $name;
        $log['created_at'] = Helper::getMongoDateTime();
        $log['client_info'] = (object)[
            'agent' => @$_SERVER['HTTP_USER_AGENT'],
            'referer' => @$_SERVER['HTTP_REFERER'],
            'ip' => @$_SERVER['REMOTE_ADDR'],
        ];
        $log['before'] = $dataBeforSave;
        $log['after'] = $dataAffterSave;
        //update thêm vào bảng All data để thực hiện search cho máu
        if (isset($dataAffterSave['name'])) {
            $s = [
                //'object_id'  => (string)$dataAffterSave['_id'],
                '_id' => $dataAffterSave['_id'],
                'table' => $log['table'],
                'name' => @$dataAffterSave['name'],
                'keyword' => strtolower(@$dataAffterSave['name']) . strtolower(@$dataAffterSave['description']),
                'updated_by' => $log['created_by'],
                'created_at' => $log['created_at'],
                'removed' => isset($dataAffterSave['removed']) ? $dataAffterSave['removed'] : 'no',
            ];
            //$saveSearch = array_merge($dataAffterSave, $s);
            $saveSearch = $s;
            /*DB::getCollection('io_search')->findOneAndUpdate(
                array('_id' => $dataAffterSave['_id']),
                array('$set' => $saveSearch),
                array('new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER)
            );  */
            DB::getCollection(self::table_search_name)->findOneAndUpdate(
                ['_id' => $dataAffterSave['_id']],
                ['$set' => $saveSearch],
                ['new' => true, 'upsert' => true]
            );
            if ($dataBeforSave) {
                $title = $log['created_by']['name'] . ' đã cập nhật ' . $dataAffterSave['name'];
            } else {
                $title = $log['created_by']['name'] . ' đã thêm mới ' . $dataAffterSave['name'];
            }
            if ($notification) {
                Notification::pushNotificationChangeObject(@$title, $dataAffterSave, $name);
                if (@$dataAffterSave['created_by']['email'] || @$dataAffterSave ['members'][0]['email']) {

                    $cc = collect(@$dataAffterSave['members'])->map(function ($item) {
                        return @$item['email'];
                    })->filter(function ($item) {
                        return $item;
                    })->toArray();
                    $link = '';
                    if (@$s['table']) {
                        if ($s['table'] === Post::table_name) {
                            $link = admin_link('/news/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Agency::table_name) {
                            $link = admin_link('/agency/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Staff::table_name) {
                            $link = admin_link('/staff/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Tour::table_name) {
                            $link = admin_link('/products/input?id=' . $dataAffterSave['_id']) . '&stab=' . @$dataAffterSave['type'];
                        } else if ($s['table'] === Position::table_name) {
                            $link = admin_link('/staff/position/?id=' . $dataAffterSave['_id']);
                        }
                    }

                    $tpl['success'] = true;
                    $tpl['name'] = 'Xác thực email';
                    $tpl['subject'] = '[Hệ thống quản lý MinhPhucGroup] Yêu cầu xác thực tài khoản';
                    $tpl['template'] = "mail.verified_account";
                    $to = $dataAffterSave['created_by']['email'] ?: @$dataAffterSave ['members'][0]['email'];
                    EmailHelper::sendMail($to, $tpl);

                }
            }

        }

        self::insert($log);
    }

    static function createLog($log, $name, $dataBeforSave = [], $dataAffterSave = [], $notification = true)
    {
        /**
         * Ai làm: nhân viên nào
         * Làm gì: insert, delete, update...
         * lúc nào? created_at
         * dự án nào?
         * phòng ban nào? deparment_id, nghĩa là ông này ở phòng nào
         * đối tượng nào? => object_id
         */
        $log['created_by'] = Member::getCreatedByToSaveDb();
        $log['table'] = $name;
        $log['created_at'] = Helper::getMongoDateTime();
        $log['client_info'] = (object)[
            'agent' => $_SERVER['HTTP_USER_AGENT'],
            'referer' => @$_SERVER['HTTP_REFERER'],
            'ip' => $_SERVER['REMOTE_ADDR'],
        ];
        $log['before'] = $dataBeforSave;
        $log['after'] = $dataAffterSave;
        //update thêm vào bảng All data để thực hiện search cho máu
        if (isset($dataAffterSave['name'])) {
            $s = [
                //'object_id'  => (string)$dataAffterSave['_id'],
                '_id' => $dataAffterSave['_id'],
                'table' => $log['table'],
                'name' => @$dataAffterSave['name'],
                'keyword' => strtolower(@$dataAffterSave['name']) . strtolower(@$dataAffterSave['description']),
                'updated_by' => $log['created_by'],
                'created_at' => $log['created_at'],
                'removed' => isset($dataAffterSave['removed']) ? $dataAffterSave['removed'] : 'no',
            ];
            //$saveSearch = array_merge($dataAffterSave, $s);
            $saveSearch = $s;
            /*DB::getCollection('io_search')->findOneAndUpdate(
                array('_id' => $dataAffterSave['_id']),
                array('$set' => $saveSearch),
                array('new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER)
            );  */
            DB::getCollection(self::table_search_name)->findOneAndUpdate(
                ['_id' => $dataAffterSave['_id']],
                ['$set' => $saveSearch],
                ['new' => true, 'upsert' => true]
            );
            if ($dataBeforSave) {
                $title = $log['created_by']['name'] . ' đã cập nhật ' . $dataAffterSave['name'];
            } else {
                $title = $log['created_by']['name'] . ' đã thêm mới ' . $dataAffterSave['name'];
            }
            if ($notification) {
                Notification::pushNotificationChangeObject(@$title, $dataAffterSave, $name);
                if (@$dataAffterSave['created_by']['email'] || @$dataAffterSave ['members'][0]['email']) {

                    $cc = collect(@$dataAffterSave['members'])->map(function ($item) {
                        return @$item['email'];
                    })->filter(function ($item) {
                        return $item;
                    })->toArray();
                    $link = '';
                    if(@$s['table']){
                        if($s['table'] === Orders::table_name){
                            $link = admin_link('/orders/input?id='.$dataAffterSave['_id'].'&preview=true&view=popup');
                        }else if($s['table'] === Position::table_name){
                            $link = admin_link('/staff/position/?id='.$dataAffterSave['_id']);
                        }
                    }

                    self::sendMail($to = $dataAffterSave['created_by']['email']?:@$dataAffterSave ['members'][0]['email'], $tpl = [
                        'obj' => $dataAffterSave,
                        'title' => @$title ?: @$dataAffterSave['name'],
                        'link' => $link,

                    ], $cc);
                }
            }

        }

        self::insert($log);

    }
    const TYPE_LOGIN = 'login';
    const TYPE_CREATE = 'created';
    const TYPE_UPDATED = 'updated';
    const TYPE_APPROVED = 'approved';//duyệt don
    const TYPE_DELETE = 'deleted';
    const TYPE_REVERT = 'revert';//khôi phục dữ liệu
    const TYPE_CHANGE_PASSWORD = 'change_pass';//khôi phục dữ liệu
    static function sendMail($to = 'jekayn109@gmail.com', $tpl, $cc = [])
    {
        /// tham khảo thêm tại http://backend.com/demo_sendmail
        ///
        if (!empty($cc)) {
            Mail::to($to)->cc($cc)->send(new NotificationForStaff($tpl));

        } else {
            Mail::to($to)->send(new NotificationForStaff($tpl));

        }
        ///
        /// Hoặc gửi qua base
        ///
        ///
    }

    const TYPE_UPDATED_LUINGAY = 'updated_luingay';

    const TYPE_COMPANY          = 'OBJECT_COMPANY';
    const TYPE_DOC              = 'OBJECT_DOC_TYPE';

    const OBJECT_DEPARTMENT     = 'OBJECT_DEPARTMENT';// phòng ban
    const OBJECT_PRODUCT     = 'OBJECT_PRODUCT';// phòng ban
    const OBJECT_POSITION_STAFF = 'OBJECT_POSITION_STAFF';// nhân sự
    const OBJECT_STAFF_INFO = 'OBJECT_STAFF_INFO';// nhân sự cơ bản
    const OBJECT_STAFF_WORK = 'OBJECT_STAFF_WORK';// nhân sự công việc
    const OBJECT_STAFF_FAMILY = 'OBJECT_STAFF_FAMILY';// nhân sự gia đình,nhân thân
    const OBJECT_STAFF_EDU = 'OBJECT_STAFF_EDU';// nhân sự học vấn
    const OBJECT_STAFF          = 'OBJECT_STAFF';// nhân viên
    const OBJECT_NEWS           = 'OBJECT_NEWS';// tin tức
    const OBJECT_SUBSCRIBE           = 'OBJECT_SUBSCRIBE';// SUBSCRIBE
    const OBJECT_CALENDAR       = 'OBJECT_CALENDAR';// lịch cơ quan
    const OBJECT_DOCUMENT       = 'OBJECT_DOCUMENT';//văn bản
    const OBJECT_FORUM_TOPIC       = 'OBJECT_FORUM_TOPIC';//Chuyên đề diễn đàn
    const OBJECT_FORUM_POST       = 'OBJECT_FORUM_POST';//bài viết trong chuyên đề diễn đàn
    const OBJECT_PROJECT       = 'OBJECT_PROJECT';//văn bản
    const OBJECT_ROLE       = 'OBJECT_ROLE';//quyền
    const OBJECT_FOLDER       = 'OBJECT_FOLDER';//folder
    const OBJECT_CATEGORY       = 'OBJECT_CATEGORY';//category
    const OBJECT_FILE       = 'OBJECT_FILE';//thư viện số
    const OBJECT_CONTRACT      = 'OBJECT_CONTRACT';//hợp đồng
    const OBJECT_PROFILE      = 'OBJECT_PROFILE';//hồ sơ
    const OBJECT_LIBRARY      = 'OBJECT_LIBRARY';//tài liệu tham khảo
    const OBJECT_MEDIA      = 'OBJECT_MEDIA';
    const OBJECT_ALBUM      = 'OBJECT_ALBUM';//ảnh
    const OBJECT_VICHIETKHAU      = 'OBJECT_VICHIETKHAU';//ví chiết khấu
    const OBJECT_VITIEUDUNG      = 'OBJECT_VITIEUDUNG';//ví tiêu dùng
    const OBJECT_CONGNO      = 'OBJECT_CONGNO';//ví tiêu dùng
    const OBJECT_HOAHONG      = 'OBJECT_HOAHONG';//ví tiêu dùng
    const OBJECT_TICHLUY      = 'OBJECT_TICHLUY';//ví tiêu dùng
    const OBJECT_CUSTOMER      = 'OBJECT_CUSTOMER';//ví tiêu dùng
    const OBJECT_KHOHANG      = 'OBJECT_KHOHANG';//ví tiêu dùng
    const OBJECT_DONHANG      = 'OBJECT_DONHANG';//ví tiêu dùng
}
