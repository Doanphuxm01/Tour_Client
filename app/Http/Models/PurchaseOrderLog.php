<?php


namespace App\Http\Models;


use App\Elibs\Helper;
use Illuminate\Support\Facades\DB;

class PurchaseOrderLog extends BaseModel
{
    public $timestamps = false;
    const table_name = 'io_purchase_orders_logs';
    protected $table = self::table_name;
    static $unguarded = true;
    const table_search_name = '_logs_io_search';


    static function createLog($log, $name, $dataBeforSave = [], $dataAffterSave = [], $notification = true){
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
            'referer' => $_SERVER['HTTP_REFERER'],
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
                    if (@$s['table']) {
                        if ($s['table'] === Tour::table_name) {
                            $link = admin_link('/products/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Post::table_name) {
                            $link = admin_link('/news/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Staff::table_name) {
                            $link = admin_link('/staff/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Cate::table_name) {
                            $link = admin_link('/category/input?id=' . $dataAffterSave['_id']) . '&stab=' . @$dataAffterSave['type'];
                        } else if ($s['table'] === ReportManual::table_name) {
                            $link = admin_link('/report/manual/input?id=' . $dataAffterSave['_id'] . '&stage=edit-created');
                        } else if ($s['table'] === Department::table_name) {
                            $link = admin_link('/staff/department/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Project::table_name) {
                            $link = admin_link('/project/input?id=' . $dataAffterSave['_id']);
                        } else if ($s['table'] === Position::table_name) {
                            $link = admin_link('/staff/position/?id=' . $dataAffterSave['_id']);
                        }
                    }

                    self::sendMail($to = $dataAffterSave['created_by']['email'] ?: @$dataAffterSave ['members'][0]['email'], $tpl = [
                        'obj' => $dataAffterSave,
                        'title' => @$title ?: @$dataAffterSave['name'],
                        'link' => $link,

                    ], $cc);
                }
            }


            self::insert($log);
        }
    }
}