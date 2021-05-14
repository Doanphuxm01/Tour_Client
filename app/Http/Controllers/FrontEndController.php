<?php


namespace App\Http\Controllers;
use App\Elibs\HtmlHelper;
use App\Http\Models\Media;
use SEOMeta;
use OpenGraph;
use Twitter;

class FrontEndController extends Controller
{
    public function seo($obj)
    {
        $SEO_INPUT_SETTING = HtmlHelper::getInstance()->setSeoSetting(isset($obj['seo']) ? $obj['seo'] : []);
        $seo_title = isset($SEO_INPUT_SETTING['TITLE']) ? $SEO_INPUT_SETTING['TITLE'] : $obj['name'];
        $seo_des = isset($SEO_INPUT_SETTING['DES']) ? $SEO_INPUT_SETTING['DES'] : @$obj['mo_ta_ngan'];
        $seo_keyword = isset($SEO_INPUT_SETTING['KEYWORD']) ? [$SEO_INPUT_SETTING['KEYWORD']] : '';
        $seo_image = isset($SEO_INPUT_SETTING['IMAGE']) ? Media::getImageSrc($SEO_INPUT_SETTING['IMAGE']) : '';
        SEOMeta::setTitle($seo_title);
        SEOMeta::setDescription($seo_des);
        SEOMeta::setKeywords($seo_keyword);

        OpenGraph::setTitle($seo_title);
        OpenGraph::setDescription($seo_des);
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'object');
        OpenGraph::setSiteName(config('app.brand_name'));
        OpenGraph::addImage(@$seo_image, ['height' => 300, 'width' => 300]);
        Twitter::setTitle($seo_title); // title of twitter card tag
        Twitter::setSite(config('app.brand_name')); // site of twitter card tag
        Twitter::setDescription($seo_des); // description of twitter card tag
        Twitter::setUrl(url()->current()); // url of twitter card tag
    }
}