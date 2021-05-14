<?php


namespace App\Http\Controllers\FrontEnd\FeContent;


use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEndController;
use App\Http\Models\BaseModel;
use App\Http\Models\Cate;
use App\Http\Models\Post;
use App\Http\Models\StaticPage;
use Illuminate\Http\Request;

class FeNews extends FrontEndController
{
    function index($alias) {
        // đây là danh mục

        $tpl = [];
        $q = Request::capture()->input('q', '');
        $tpl['q'] = $q;
        if ($q) {
            $obj = [];
            $obj['name'] = 'Kết quả tìm kiếm cho từ khoá "' .$q .'"';
        }else {
            $obj = Cate::getByAlias($alias);
            if (!isset($obj['status']) || $obj['status'] !== Cate::STATUS_ACTIVE) {
                return redirect('/');
            }
        }

        HtmlHelper::getInstance()->setTitle($obj['name']);
        $itemPerPage = Request::capture()->input('row', 24);
        $curPage = (int)request('page', 1);


        $lsObj = Post::where(['status' => Post::STATUS_ACTIVE]);
        if ($q) {
            $lsObj = $lsObj->where('name', 'LIKE', '%' . trim($q) . '%');
        }else {
            $lsObj = $lsObj->where('categories', 'elemMatch', ['alias' => $alias]);
        }
        $lsObj = $lsObj->select(Post::$basicFiledsForList)->orderBy('actived_at', 'DESC');
        $lsObj = Pager::getInstance()->getPager($lsObj, $itemPerPage, $curPage);
        $tpl['obj'] = $obj;
        $tpl['lsObj'] = $lsObj;
        $newone = Post::getNewsLastest(6)->toArray();
        $tpl['related'] = $newone;
        $this->seo($obj);

        return eView::getInstance()->setViewFrontEnd(__DIR__, 'news.list', $tpl);
    }

    function input($cate, $alias) {
        // đây là detail
        $tpl = [];
        if($cate == 'trang-tinh') {
            $obj = StaticPage::getByAlias($alias);
            $class = StaticPage::class;
            $tpl['nocate'] = StaticPage::NOCATE;
        }else {
            $currentCate = Cate::getByAlias($cate);
            $obj = Post::getByAlias($alias);
            $tpl['currentCate'] = $currentCate;
            $class = Post::class;
        }

        if (!isset($obj['status']) || $obj['status'] !== Post::STATUS_ACTIVE) {
            return redirect('/');
        }
        if(!isset($obj['views'])) {
            $obj['views'] = 0;
        }
        $class::whereAlias($alias)->update(['views' => $obj['views']+1]);
        $obj['name'] = Helper::showContent($obj['name']);
        $tpl['obj'] = $obj;
        HtmlHelper::getInstance()->setTitle($obj['name']);
        $this->seo($obj);
        // lay cac bai viet lien quan
        $newone = Post::getNewsLastest(6)->toArray();
        $tpl['related'] = $newone;
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'news.input', $tpl);
    }
}