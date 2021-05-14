<?php
namespace App\Elibs;


use App\Http\Models\BaseModel;
use App\Http\Models\Logs;
use App\Http\Models\Notes;
use App\Http\Models\Report;
use App\Http\Models\Role;
use App\Http\Models\Staff;

class BeaconsHelper
{
    /**
     * @param $obj
     * @param $table_name
     * @param array $options
     * @param array $tpl_extend
     * Dùng để xử lý hiển thị phần cột phải trong các form có cột phải
     */
    static function getRightBox($obj, $table_name, $options = ['tabs' => ['notes', 'histories']], $tpl_extend = [])
    {
        $tpl = [];
        if (!$options) {
            $options = ['tabs' => ['notes', 'histories']];
        }
        $tpl['options'] = $options;
        foreach ($tpl_extend as $key => $value) {
            $tpl[$key] = $value;
        }
        if (!isset($obj['_id'])) {
            $tpl['table_name'] = $table_name;
            return eView::getInstance()->setView('', 'components/right-bar-form-disabled', $tpl, true);
        } else {

            $notes = BaseModel::table(Notes::table_name)->where('object_id', $obj['_id'])->orderBy('_id', 'DESC')->limit(50)->get();
            $tpl['obj'] = $obj;
            $tpl['notes'] = $notes;
            $tpl['table_name'] = $table_name;
            $histories = BaseModel::table(Logs::table_name)->where('object_id', $obj['_id'])->orderBy('_id', 'DESC')->limit(50)->get();
            $tpl['histories'] = $histories;


            return eView::getInstance()->setView('', 'components/right-bar-form', $tpl, true);
        }
    }

    static function processMemberRelated($members, $key = 'members')
    {
        if (!$members) {
            $members = request($key, []);
        }
        $lsMember = [];
        if ($members) {
            foreach ($members as $key => $member) {
                if (is_array($member)) {
                    foreach ($member as $_item) {
                        $staff = Staff::find($_item);
                        if ($staff) {
                            if (isset($lsMember[$_item])) {
                                $lsMember[$_item][$key] = true;
                            } else {
                                $lsMember[$_item] = [
                                    'id' => @$staff['_id'],
                                    'name' => @$staff['name'],
                                    'email' => @$staff['email'],
                                    $key => true
                                ];
                            }
                        }

                    }
                }
            }
        }

        return $lsMember;


    }

    /**
     * @param $obj
     * @param $allStaff
     * @param array $lsReturn
     * @param array $memberMain
     * @param array $memberRelated
     * @param array $memberRequester
     * @param string $optionMemberMain
     * @param string $optionMemberRelated
     * @param string $optionMemberRequester
     *  $lsReturn = [
     * 'key'=>['main','related','requester'],
     * ];
     * self::processMemberRelatedPreviewInput($obj,$allStaff,$lsReturn);
     */
    static function processMemberRelatedPreviewInput(
        $obj, $allStaff,
        &$lsReturn = [
            'key' => ['main', 'related', 'requester'],
            'root' => 'members'
        ],
        &$memberMain = [], &$memberRelated = [], &$memberRequester = [], &$optionMemberMain = '', &$optionMemberRelated = '', &$optionMemberRequester = ''
    )
    {
        if (!isset($lsReturn['root'])) {
            $lsReturn['root'] = 'members';
        }

        foreach ($lsReturn['key'] as $ks) {
            $lsReturn['select_option_html'][$ks] = '';
        }
        if (isset($obj[$lsReturn['root']]) && is_array($obj[$lsReturn['root']])) {
            foreach ($obj[$lsReturn['root']] as $_item) {
                foreach ($lsReturn['key'] as $ks) {
                    if (isset($_item[$ks]) && $_item[$ks]) {
                        $lsReturn['data'][$ks][$_item['id']] = $_item['name'];
                    }
                }

                /*if (isset($_item['main']) && $_item['main']) {
                    $memberMain[$_item['id']] = $_item['name'];
                }
                if (isset($_item['related']) && $_item['related']) {
                    $memberRelated[$_item['id']] = $_item['name'];
                }
                if (isset($_item['requester']) && $_item['requester']) {
                    $memberRequester[$_item['id']] = $_item['name'];
                }*/

            }
        }

        foreach ($allStaff as $key => $_item) {
            foreach ($lsReturn['key'] as $ks) {
                $lsReturn['select_option_html'][$ks] .= ' <option value="' . $_item['_id'] . '" title="' . /*@$_item['code'] .' | ' .*/
                    @$_item['email'] . '" ';
                if (isset($lsReturn['data'][$ks][(string)$_item['_id']])) {
                    $lsReturn['select_option_html'][$ks] .= ' selected ';
                }
                $lsReturn['select_option_html'][$ks] .= '>' . $_item['name'] . '</option>';
            }
            $optionMemberMain .= ' <option value="' . $_item['_id'] . '" title="' . $_item['email'] . '" ';
            $optionMemberRelated .= ' <option value="' . $_item['_id'] . '" title="' . $_item['email'] . '" ';
            $optionMemberRequester .= ' <option value="' . $_item['_id'] . '" title="' . $_item['email'] . '" ';

            if (isset($memberMain[(string)$_item['_id']])) {
                $optionMemberMain .= ' selected ';
            }
            if (isset($memberRelated[(string)$_item['_id']])) {
                $optionMemberRelated .= ' selected ';
            }
            if (isset($memberRequester[(string)$_item['_id']])) {
                $optionMemberRequester .= ' selected ';
            }


            $optionMemberMain .= '>' . $_item['name'] . '</option>';
            $optionMemberRelated .= '>' . $_item['name'] . '</option>';
            $optionMemberRequester .= '>' . $_item['name'] . '</option>';

        }
    }

    /**
     * Xử lý lưu file vào db
     * yêu cầu form luôn phải có 2 thành phần này
     * và chỉ áp dụng với cases init data lưu nhiều file
     */

    static function processFilesToSave($field = 'files')
    {
        $files = request($field);
        $files_name = request($field . '_name');
        if (!is_array($files)) {
            return [];
        }
        $_files = [];
        foreach ($files as $key => $val) {
            if ($val) {
                $_files[$key]['src'] = $val;
                if (isset($files_name[$key])) {
                    $_files[$key]['name'] = $files_name[$key];
                } else {
                    $_files[$key]['name'] = '';
                }
            }
        }

        return $_files;
    }

    public static function saveInputBasic($setting, $obj, $savePost = [], $luyKe = [])
    {

        $id = request('id');
        $members = request('members', []);

        $curObjInDb = [];
        if ($id) {
            $curObjInDb = $setting['model']::find($id);
            if (!$curObjInDb) {
                return eView::getInstance()->getJsonNotifError('Bản ghi này không tồn tại hoặc đã bị xóa');
            } else {
                if (Role::isMyOwn($curObjInDb)) {
                    $isAllow = Role::isAllowTo(Role::$ACTION_EDIT_OF_ME . $setting['object']);
                } else {
                    $isAllow = Role::isAllowTo(Role::$ACTION_EDIT . $setting['object']);
                }
                if (!$isAllow) {
                    return eView::getInstance()->cannnotAccess(['msg' => 'Bạn không có quyền thực hiện hành động này']);
                }
            }
        }

        if ($obj) {
            //Nếu đã tiền xử lý thì k cần quăng biến này lên
            $processLK = [];
            foreach ($obj as $key => $val) {
                if (!isset($savePost[$key])) {
                    if (isset($setting['fields'][$key])) {
                        $value = $setting['fields'][$key];
                        if ($value['type'] == 'string') {
                            $val = @trim($val);
                        }
                        if (isset($value['require']) && $value['require']) {
                            if (!$val) {
                                return eView::getInstance()->getJsonNotifError($value['require']);
                            }
                        }
                        if ($value['type'] == 'date') {
                            if ($obj[$key]) {
                                $savePost[$key] = Helper::getMongoDateTime($val, $value['format']);
                            } else if (@$value['default'] == 'now') {
                                $savePost[$key] = Helper::getMongoDateTime();
                            }

                        } elseif ($value['type'] == 'string') {
                            if (isset($value['html']) && !$value['html']) {
                                $savePost[$key] = strip_tags($val);
                            } else {
                                $savePost[$key] = $val;
                            }

                        } elseif ($value['type'] == 'int') {
                            $savePost[$key] = (int)$val;
                        } elseif ($value['type'] == 'bool') {
                            $savePost[$key] = (bool)$val;
                        }

                    } else {
                        $savePost[$key] = $val;
                    }
                }
            }
        }

        #region xử lý nhân viên liên quan
        $savePost['members'] = array_values(BeaconsHelper::processMemberRelated($members));
        $savePost['files'] = BeaconsHelper::processFilesToSave();
        #endregion xử lý nhân viên liên quan

        #region xử lý dự án nếu có
        if (isset($savePost['projects']) && $savePost['projects']) {
            $allProjectKeyId = all_project(true);
            $_projectSaveToDb = [];
            foreach ($savePost['projects'] as $_project_id) {
                if (isset($allProjectKeyId[$_project_id])) {
                    $_projectSaveToDb[] = [
                        'id' => $_project_id,
                        'name' => $allProjectKeyId[$_project_id]['name']
                    ];
                }
            }
            $savePost['projects'] = $_projectSaveToDb;
        }

        $savePost['updated_at'] = Helper::getMongoDateTime();

        if (!$id) {
            //thêm mới
            $savePost['created_at'] = Helper::getMongoDateTime();
            $savePost['removed'] = BaseModel::REMOVED_NO;
            $savePost['created_by'] = Staff::getCreatedByToSaveDb();
            $savePost['status'] = BaseModel::STATUS_ACTIVE;

            if ($luyKe) {
                //Nếu lũy kế chỉ là 1 trường
                //khoi_luong_luy_ke
                //cách 1: lấy khối lượng lũy kế của bản ghi gần nhất theo dự án và loại vật liệu vào cộng lại => nhanh nhưng có thể có lỗi
                //cách 2: Tính tổng khối lượng của tất cả (theo dự án + loại vật liệu) rồi cộng lại cho thằng mới nhất => ít lỗi nhưng có thể chậm => chọn cách này
                //if (1 == 2) {
                //đây dành cho cơ chế cộng đồn
                //Ngày 11/08/2019, anh Tuân chốt là chỉ tính lũy kế cho bản ghi thêm mới
                foreach ($luyKe as $key => $value) {
                    $getSum = $setting['model']::raw(function ($collection) use ($value, $key) {
                        $field = '$' . $value['sum'];

                        return $collection->aggregate([
                            [
                                '$match' => $value['condition'],
                            ],
                            [
                                '$group' => [
                                    '_id' => 'total',
                                    'totalCount' => [
                                        '$sum' => $field
                                    ]
                                ]
                            ]
                        ]);
                    });
                    if (isset($getSum[0]->totalCount)) {
                        $savePost[$key] = $getSum[0]->totalCount + @$obj[$value['sum']];
                    } else {
                        $savePost[$key] = @$obj[$value['sum']];
                    }
                }
                // }
            }

            $id = $setting['model']::insertGetId($savePost);

            Logs::createLog([
                'type' => Logs::TYPE_CREATE,
                'object_id' => (string)$id,
                'note' => 'Thêm  ' . $setting['name'] . ' "' . $savePost['name'] . '" từ ip: ' . $_SERVER['REMOTE_ADDR']
            ], $setting['model']::table_name, [], $setting['model']::find($id)->toArray());

            $returnClient = [
                //'redirect' => admin_link('report/site'),
                'reload' => true
            ];

            return eView::getInstance()->getJsonSuccess("Thêm " . $setting['name'] . " thành công!", $returnClient);

        } else {
            $oldData = $curObjInDb->toArray();
            if ($luyKe && 1 == 2) {// thấy vieej tính lũy kế khi sửa bản ghi là không đúng
                //lũy kế khối lượng nhập cho tất cả các bản ghi khác
                foreach ($luyKe as $key => $value) {
                    $getSum = $setting['model']::raw(function ($collection) use ($value, $key) {
                        $field = '$' . $value['sum'];

                        return $collection->aggregate([
                            [
                                '$match' => $value['condition'],
                            ],
                            [
                                '$group' => [
                                    '_id' => 'total',
                                    'totalCount' => [
                                        '$sum' => $field
                                    ]
                                ]
                            ]
                        ]);
                    });
                    //Lưu vào trường hiện tại giá trị tổng
                    //
                    /*ebug(@$getSum[0]->totalCount);
                    ebug(@$obj[$value['sum']]);
                    ebug(@$obj);
                    ebug(@$value['sum']);*/
                    if (isset($getSum[0]->totalCount)) {
                        $savePost[$key] = $getSum[0]->totalCount + @$obj[$value['sum']];
                    } else {
                        $savePost[$key] = @$obj[$value['sum']];
                    }
                    if (@$value['save_all']) {
                        //lưu vào all record
                        $setting['model']::where($value['condition'])->update(
                            [$key => $savePost[$key]]
                        );
                    }
                }
            }
            $curObjInDb->update($savePost);

            Logs::createLog([
                'type' => Logs::TYPE_UPDATED,
                'object_id' => $id,
                'note' => 'Cập nhật  ' . $setting['name'] . ' "' . $savePost['name'] . '" từ ip: ' . $_SERVER['REMOTE_ADDR']
            ], $setting['model']::table_name, $oldData, $setting['model']::find($id)->toArray());

            $returnClient = [
                //'redirect' => admin_link('report/site'),
                'reload' => true
            ];


            return eView::getInstance()->getJsonSuccess("Cập nhật " . $setting['name'] . " thành công!", $returnClient);
        }
    }

    public static function deleteBasic($setting)
    {
        $id = request('id');
        $revert = request('revert');
        $token = request('token');
        if (!Helper::validateToken($token, $id)) {
            return eView::getInstance()->getJsonNotifError('Bạn không thể xóa bản ghi này!');

        }


        $objInDb = $setting['model']::find($id);
        if ($objInDb) {
            if (Role::isMyOwn($objInDb)) {
                $isAllow = Role::isAllowTo(Role::$ACTION_DELETE_OF_ME . $setting['object']);
            } elseif(Role::isInMyProject($objInDb)){
                $isAllow = Role::isAllowTo(Role::$ACTION_DELETE_OF_PROJECT. $setting['object']);
            }else {
                $isAllow = Role::isAllowTo(Role::$ACTION_DELETE . $setting['object']);
            }
            if (!$isAllow) {
                return eView::getInstance()->cannnotAccess(['msg' => 'Bạn không có quyền thực hiện hành động này']);
            }
        }
        if (!$objInDb) {
            return eView::getInstance()->getJsonNotifError('Bản ghi này không tồn tại hoặc đã bị xóa!');
        }
        $oldData = $objInDb->toArray();
        if ($revert) {
            if (@$objInDb['removed'] == BaseModel::REMOVED_NO) {
                $returnClient = [
                    'reload' => true
                ];

                return eView::getInstance()->getJsonSuccess("Khôi phục dữ liệu thành công!", $returnClient);
            }

            $objInDb->update(
                ['removed' => BaseModel::REMOVED_NO]
            );
            $msg = "Khôi phục dữ liệu";

            $logType = Logs::TYPE_REVERT;

        } else {

            $objInDb->update(
                ['removed' => BaseModel::REMOVED_YES]
            );
            $msg = "Xóa dữ liệu ";
            $logType = Logs::TYPE_DELETE;

        }


        Logs::createLog([
            'type' => $logType,
            'object_id' => $id,
            'note' => $msg . ' thông tin ' . $setting['name'] . '"' . $objInDb['name'] . '" từ ip: ' . $_SERVER['REMOTE_ADDR']
        ], $setting['model']::table_name, $oldData, $objInDb->toArray());


        $returnClient = [
            'reload' => true
        ];

        return eView::getInstance()->getJsonSuccess($msg . " thành công!", $returnClient);
    }
}
