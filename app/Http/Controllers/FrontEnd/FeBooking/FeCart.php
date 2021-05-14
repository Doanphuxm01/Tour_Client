<?php


namespace App\Http\Controllers\FrontEnd\FeBooking;


use App\Elibs\Cart;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Controllers\FrontEndController;
use App\Http\Models\Payment;
use App\Http\Models\Tour;
use Illuminate\Http\Request;

class FeCart extends FrontEndController
{

    protected $GUEST_CART_KEY = 'GuestCart';
    protected $AUTH_CART_KEY = 'AuthCart';
    public function index($action =  '') {

        $action = str_replace('-', '_', $action);
        if(method_exists($this, $action)){
            return $this->$action();
        }
        return $this->cart();
    }

    function cart() {
        $tpl = [];
        $lsObj = Cart::getInstance()->content();
        if(!$lsObj) {
            return eView::getInstance()->setView404();
        }

        HtmlHelper::getInstance()->setTitle('Thanh toán giỏ hàng của bạn');
        $tpl['lsObj'] = $lsObj;
        $tpl['lsPay'] = Payment::getListPayment();
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'index', $tpl);
    }

    function addToCart() {
        $id= Request::capture()->input('id');
        $sku= Request::capture()->input('sku');
        return $this->_checkAndAddToCart($id, $sku);
    }

    private function _checkAndAddToCart($id, $sku) {
        $obj = Tour::whereSku($sku)->whereStatus(Tour::STATUS_ACTIVE)->select(Tour::$basicFiledsForBooking)->first();
        if(!empty($obj)) {
            $obj = $obj->toArray();
            $data = [
                'id' => $obj['_id'],
                'name' => $obj['name'],
                'alias' => $obj['alias'],
                'link' => link_detail($obj),
                'sku' => $obj['sku'],

                'tuyen_tour' => $obj['tuyen_tour'],
                'dia_diem_khoi_hanh' => $obj['dia_diem_khoi_hanh'],
                'so_luong_khach_toi_da' => $obj['so_luong_khach_toi_da'],
                'gia_nguoi_lon' => $obj['gia_nguoi_lon'],
                'gia_tre_em' => $obj['gia_tre_em'],
                'gia_tre_nho' => $obj['gia_tre_nho'],
                'don_vi_tien_te' => $obj['don_vi_tien_te'],
                'avatar' => $obj['avatar'],
                'info' => [
                    'nguoi_lon' => [
                        'total' => 1,
                        'list' => []
                    ],
                    'tre_em' => [
                        'total' => 0,
                        'list' => []
                    ],
                    'tre_nho' => [
                        'total' => 0,
                        'list' => []
                    ],
                ]
            ];
            if(@$obj['tour_hang_tuan'] == Tour::TOURHANGTUAN) {
                $data['tour_hang_tuan'] = Tour::TOURHANGTUAN;
                $data['thoi_gian_khoi_hanh_hang_tuan'] = $obj['thoi_gian_khoi_hanh_hang_tuan'];
            }else if(@$obj['tour_hang_tuan'] == Tour::TOURHANGNGAY) {
                $data['tour_hang_tuan'] = Tour::TOURHANGNGAY;
            }else {
                $data['ngay_khoi_hanh'] = Helper::showMongoDate($obj['ngay_khoi_hanh']);
                $data['ngay_ket_thuc'] = Helper::showMongoDate($obj['ngay_ket_thuc']);
            }
            $result = Cart::getInstance()->add($data);
            if($result === true) {
                return eView::getInstance()->getJsonSuccess('Thêm giỏ hàng thành công!');
            }
        }
        return eView::getInstance()->getJsonError("Sản phẩm của bạn đã bị xóa");
    }

    function load() {
        $lsObj = Cart::getInstance()->content();
        return eView::getInstance()->getJsonSuccess('Thông tin giỏ hàng', $lsObj);
    }

    public function cartRemove(){
        $id = Request::capture()->input('id', 0);
        if($id) {
            Cart::getInstance()->remove($id);
            return eView::getInstance()->getJsonSuccess('Xoá sản phẩm ra khỏi giỏ hàng thành công!');
        }
        return eView::getInstance()->getJsonSuccess('Không tìm thấy sản phẩm trong giỏ hàng!');
    }

    function destroyCart() {
        Helper::delSession('ShopCart');
        return redirect(public_link('/'));
    }
}