<?php


namespace App\Http\Controllers;


use App\Http\Models\BaseModel;

class BackEndController extends Controller
{

    function __construct() {
        $tpl['lsStatusRegister'] = BaseModel::getListStatus(@$obj['status']);
    }

    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->list();
        }
    }

    function list() {

    }

    function input() {

    }

    function _save() {

    }
}