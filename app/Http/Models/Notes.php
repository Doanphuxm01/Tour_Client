<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;

/***
 * Class Notes
 * @package App\Http\Models
 * Ghi chú
 */
/*
 * description: Nội dung ghi chú
 * files:[], danh sách các file đính kèm
 * members:[], thành phần liên quan được tag vào nếu có
 * created_by:{id,name}: Nhân viên thay đổi
 * created_at: Thời gian thay đổi
 * removed:'no', mặc định,
 * parent_id:0, mặc định là 0 sau này có thể cân nhắc vụ trả lời ghi chú....
 * object:{id,table} => đối tượng liên quan
 */

class Notes extends BaseModel
{
    public $timestamps = FALSE;
    const table_name = 'be_notes';
    protected $table     = self::table_name;
    static    $unguarded = TRUE;
}

