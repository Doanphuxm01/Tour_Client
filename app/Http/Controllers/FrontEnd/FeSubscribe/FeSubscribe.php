<?php

namespace App\Http\Controllers\FrontEnd\FeSubscribe;

use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Models\BaseModel;
use App\Http\Models\Logs;
use App\Http\Models\Member;
use App\Http\Models\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormSubscribe;
class FeSubscribe extends Controller
{
    public function _save(FormSubscribe $request){
        $email = Request::capture()->input('email');
        $tpl = [];
        $tpl['email'] = $email;
        $unique = Subscribe::where('email',$email)->first();
        $insertCateToDb = [
            'email' => $email,
            'ip' => \request()->ip(),
            'status' => BaseModel::STATUS_ACTIVE,
            'created_at' => Helper::getMongoDateTime(),
        ];
        $id = Subscribe::insertGetId($insertCateToDb);
        Logs::createLog(
            [
                'type' => Logs::TYPE_CREATE,
                'data_object' => $insertCateToDb,
                'note' => "Subscribe Email " . $email . ' được thêm',
            ], Logs::OBJECT_SUBSCRIBE
        );
        $return = [
            'redirect' => route('FeSubscribe'),
        ];
        return eView::getInstance()->getJsonSuccess('Cảm Ơn Bạn đã nhận tin tức của Chúng Tôi',true);

    }
}
