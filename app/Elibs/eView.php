<?php
namespace App\Elibs;

use App\Http\Models\Booking;
use App\Http\Models\Location;
use App\Http\Models\ConfigWebsite;
use App\Http\Models\Member;
use App\Http\Models\Menu;
use App\Http\Models\TourCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class eView
{
    static private $instance = false;
    static public $viewVar = [];

    public function __construct()
    {
        self::$instance = &$this;
    }

    public static function &getInstance()
    {
        if (!self::$instance) {
            new self();
        }
        return self::$instance;
    }

    public function setVar($var, $value)
    {
        self::$viewVar[$var] = $value;
    }

    /***
     * @param       $dir
     * @param       $template
     * @param array $var
     *
     * @return View
     */
    public function setView($dir, $template, $var = [], $render = false)
    {
        if ($dir) {
            View::addLocation($dir);
            $localtion = '/views/';
        } else {
            $localtion = '';
        }
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }

        $var['HtmlHelper']['Seo'] = HtmlHelper::getInstance()->getSeoMeta();
        if (!isset($var['THEME_EXTEND'])) {
            $var['THEME_EXTEND'] = 'backend';
        }

        if (!isset($var['THEME_FE_EXTEND'])) {
            $var['THEME_FE_EXTEND'] = 'frontend';
        }
        //Debug::show($localtion . $template);
        //Member::getCurent();
        $var['_MEMBER'] = Member::getCurent();
        $var['agent'] = new Agent();
        $var['welcome'] = Helper::welcome(date('H'));
        $view = view($localtion . $template, $var);
        if ($render) {
            return $view->render();
        }
        return $view;
    }

    public function setViewFrontEnd($dir, $template, $var = [], $render = true)
    {
        $configWebsite = Configwebsite::where('type', ConfigWebsite::SOCIAL)->first();
        if ($configWebsite) {
            $var['IO_CONFIG_WEBSITE'] = $configWebsite->toArray();
        }
        $var['IO_TOURCATE'] = TourCategory::getAll([], TourCategory::$basicFiledsForList, 'alias');
        $var['IO_LOCATION'] = Location::getAll();
        return self::setView($dir, $template, $var, $render);
    }

    public static function setView404($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }
        if (!$template) {
            $template = 'errors.404';
        }
        return response()->view($template, $var, 404);
    }

    public function cannnotAccess($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }
        if (!$template) {
            $template = 'errors.cannot-access';
        }
        return response()->view($template, $var, 403);
    }

    public function popupError($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }
        if (!$template) {
            $template = 'errors.error-popup';
        }
        return response()->view($template, $var, 401);
    }

    public static function setViewBackEnd($dir, $template, $var = [], $render = false)
    {
        $var['MAIN_MENU_BACKEND'] = Menu::getMainMenuBackEnd();
        $var['MEMBER'] = Member::getCurent();
        $where['created_by.id'] = Member::getCurentId();
        $var['NUMBOOKING'] = Booking::where($where)->count();

        return eView::getInstance()->setView($dir, $template, $var, $render);
    }

    public function getMainMenuBackEnd()
    {
        return Menu::getMainMenuBackEnd();
    }

    /***
     * @param string $selected
     *
     * @return mixed
     */

    public function getJsonAlert($msg)
    {
        $json['status'] = 'alert';
        $json['msg'] = $msg;

        return $this->showJson($json);
    }

    public function getJsonNotifError($msg)
    {
        $json['status'] = 'notif-error';
        $json['msg'] = $msg;

        return $this->showJson($json);
    }

    public function getJsonNotifSuccess($msg)
    {
        $json['status'] = 'notif-success';
        $json['msg'] = $msg;

        return $this->showJson($json);
    }

    public function getJsonNotifInfo($msg)
    {
        $json['status'] = 'notif-info';
        $json['msg'] = $msg;

        return $this->showJson($json);
    }
    /***
     * @param $json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showJson($json)
    {
        if (config('app.debug')) {
            $json['debug']['sql'] = DB::getQueryLog();
            $json['debug']['post'] = $_POST;
            $json['debug']['get'] = $_GET;
            $json['debug']['cookie'] = $_COOKIE;
            $json['debug']['raw'] = file_get_contents('php://input');
        }
        app('debugbar')->disable();
        //tracking end over here
        header('Content-Type: application/json');

        die(json_encode($json));
        // return response()->json($json);
    }

    /***
     * @param $msg
     * @param string $keyerror
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJsonError($msg, $keyerror = '')
    {
        $json['status'] = 0;
        $json['msg'] = $msg;
        $json['key'] = $keyerror;

        return $this->showJson($json);
    }

    /***
     * @param $msg
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJsonSuccess($msg, $data = [])
    {
        $json['status'] = 1;
        $json['msg'] = $msg;
        $json['data'] = $data;


        return $this->showJson($json);
    }

    private function _setMsg($content, $type = 'info')
    {
        $msg = '<div class="alert alert-' . $type . ' alert-styled-left alert-bordered">
                        ' . $content . '
                    </div>';
        self::$viewVar['_MSG'] = $msg;
    }

    public function setMsgError($content, $type = 'danger', $session = false)
    {
        $this->_setMsg($content, $type);
    }

    public function setMsgInfo($content, $type = 'info')
    {
        $this->_setMsg($content, $type);
    }

    public function setMsgWarning($content, $type = 'warning')
    {
        $this->_setMsg($content, $type);
    }


    const OUTPUT_JSON = 'json';
    const OUTPUT_STRING = 'string';
    const OUTPUT_BY_REDIRECT = 'redirect';
}