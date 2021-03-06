<?php
namespace App\Elibs;
use App\Http\Models\Product;
use Illuminate\Support\Facades\Session;
class Cart
{
    static private $instance = false;
    static public $viewVar = [];
    protected $key = 'ShopCart';
    const TYPE_ORDER = 'order';
    const TYPE_PRODUCT = 'product';
    public function __construct()
    {
        self::$instance = &$this;
        $this->restore();
    }

    public static function &getInstance()
    {

        if (!self::$instance) {
            new self();
        }
        return self::$instance;
    }

    /*
     * Kịch bản:
     * 1. Thêm vào giỏ hàng
     *  1.1: Kiểm tra sản phẩm đó đã tồn tại hay chưa
     *    1.1.1: Nếu có thì update số lượng
     *    1.1.2: Nếu không thì sang 2.
     *  1.2: không có thì thêm
     * */

    public function get($key = '', $product = true){
        if($product) {
            return $this->cart->get('details')->get($key);
        }
        return $this->cart->get($key);
    }

    public function add($data) {
        $details = $this->get('details', false);
        $currentObj = $this->get($data['sku'], true);
        if(isset($currentObj)) {

            $details->put($data['sku'], $data);
        }else {
            $details->put($data['sku'], $data);
        }
        $resTotal = $this->calcTotalCart();
        $resGrandTotal = $this->calcGrandTotalCart();
        $resAmount = $this->calcAmount();

        if($resTotal && $resAmount && $resGrandTotal) {
            $this->store();
        }else {
            return false;
        }
        return true;
    }

    /*
     * Cộng từng tổng tiền của từng đơn hàng, chưa có coupon
     * là tk tạm tính
     * */
    private function calcTotalCart() {
        $details = $this->get('details', false);
        if ($details) {
            $total = 0;
            foreach ($details as $detail) {
                $total += $detail['gia_nguoi_lon']*$detail['info']['nguoi_lon']['total'];
                $total += $detail['gia_tre_em']*$detail['info']['tre_em']['total'];
                $total += $detail['gia_tre_nho']*$detail['info']['tre_nho']['total'];
            }
            $this->cart->put('total', $total);
            return true;
        }
        return false;
    }

    /*
     * Cộng tiền của từng đơn hàng + cả coupon
     * là tk thành tiền
     * @todo @kayn Chưa tính coupon, mới cộng tiền của tất cả đơn hàng
     * */
    private function calcGrandTotalCart() {
        $details = $this->get('details', false);
        if ($details) {
            $grandTotal = 0;
            foreach ($details as $detail) {
                $grandTotal += $detail['gia_nguoi_lon']*$detail['info']['nguoi_lon']['total'];
                $grandTotal += $detail['gia_tre_em']*$detail['info']['tre_em']['total'];
                $grandTotal += $detail['gia_tre_nho']*$detail['info']['tre_nho']['total'];
            }
            $this->cart->put('grandTotal', $grandTotal);
            return true;
        }
        return false;
    }

    private function calcAmount() {
        $details = $this->get('details', false);
        if ($details) {
            $number = 0;
            foreach ($details as $detail) {
                $number += $detail['info']['nguoi_lon']['total'];
                $number += $detail['info']['tre_em']['total'];
                $number += $detail['info']['tre_nho']['total'];
            }
            $this->cart->put('number', $number);
            return true;
        }
        return false;
    }

    public function getTotal() {
        return $this->get('total', false);
    }

    private function updateDetail($currentObj) {
        $details = $this->get('details', false);
        if($details) {
            $details->put($currentObj['sku'], $currentObj);
            return true;
        }
        return false;
    }

    private function store() {
        $cart = $this->cart->toArray();
        Helper::setSession($this->key, json_encode($cart));
    }

    private function restore() {
        $cart = [];
        $tmp = Helper::getSession($this->key);
        if(!empty($tmp) && $tmp != '%5B%5D') {
            $tmp = json_decode($tmp,1);
            if(!empty($tmp)) {
                $cart = $this->dataCart($tmp['details'], $tmp['total'], $tmp['number'], @$tmp['grandTotal'], @$tmp['totalSiLe']);
            }
        }
        if(empty($cart)){
            $cart = $this->dataCart();
        }
        $this->cart = $cart;
    }

    public function content() {
        $data = $this->cart->toArray();
        return $data;
    }

    protected function checkingExisted($product_id) {
        $details = $this->cart->get('details');
        foreach($details as $idx => $itm) {
            if($itm['sku'] == $product_id){
                return $idx;
            }
        }
        return false;
    }

    public function remove($product_id = 0){

        $existed = $this->checkingExisted($product_id);
        if($existed !== false) {
            $this->cart->get('details')->forget($existed);
            $this->refresh();

            $this->store();
        }
    }

    protected function refresh(){
        $details = $this->cart->get('details');
        $this->cart->put('details',collect($details->toArray()));
        $this->calcTotalCart();
        $this->calcGrandTotalCart();
        $this->calcAmount();
    }

    protected function removeCookie(){
    //        Cookie::queue($this->key, '', 60*24*365);
    //        Cookie::forget($this->key);
        session()->forget($this->key);
    }

    /*
     * Dữ liệu khởi tạo gồm:
     * 1. Danh sách sản phẩm
     * 2. Tổng tiền cả giỏ hàng
     * 3. Số lượng sản phẩm có trong giỏ
     * */

    private function dataCart($details = [], $total = 0, $number = 0, $grandTotal = 0, $totalSile = 0) {
        if(empty($details)){
            $details = [];      // chi tiết giỏ hàng
        }
        return collect(['details' =>
            collect($details),
            'total' => $total,              // tổng tiền
            'grandTotal' => $grandTotal,        //tổng tiền cả giỏ đã bao gồm voucher
            'number' => $number,        // số lượng
        ]);
    }

    public function destroyCart() {
        Helper::delSession('ShopCart');
        return redirect(public_link('/'));
    }
}