<?php


namespace App\Http\Controllers\FrontEnd\FeBooking;


use App\Elibs\Cart;
use App\Elibs\EmailHelper;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Controllers\Controller;
use App\Http\Models\BaseModel;
use App\Http\Models\Booking;
use App\Http\Models\Location;
use App\Http\Models\Member;
use App\Http\Models\Payment;
use App\Http\Models\Product;
use App\Http\Models\Tour;
use App\Http\Models\TourKhoiHanh;
use Illuminate\Http\Request;

class FeBooking extends Controller
{
    function index($id) {
        $tpl = [];

        $tour = Tour::getBySku($id);
        if(!$tour) {
            return eView::getInstance()->setView404();
        }
        if(@$tour['tour_hang_tuan'] == Tour::TOURHANGTUAN || @$tour['tour_hang_tuan'] == Tour::TOURHANGNGAY) {

        }else {
            if(Helper::convertTimeToInt($tour['ngay_khoi_hanh']) < Helper::convertTimeToInt(Helper::getMongoDate())) {
                $lichKhoiHanh = TourKhoiHanh::getByParentId($tour['_id']);
                if($lichKhoiHanh) {
                    return redirect()->route('FeTour', ['alias' => $tour['alias'], '#sec-kh']);
                }else {
                    return redirect('/');
                }
            }

        }
        HtmlHelper::getInstance()->setTitle('Đặt tour '.$tour['name']);
        $tpl['tour'] = $tour;
        if(isset($tour['dia_diem_den'][0])) {
            $tpl['tour_lien_quan'] = $this->tour_lien_quan($tour['dia_diem_den'][0]['alias']);
        }
        $tpl['lsPay'] = Payment::getListPayment();
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'index', $tpl);
    }

    function tour_le($id) {
        $tpl = [];
        $tour = TourKhoiHanh::getBySku($id);
        if(!$tour) {
            return eView::getInstance()->setView404();
        }
        if(Helper::convertTimeToInt($tour['ngay_khoi_hanh']) < Helper::convertTimeToInt(Helper::getMongoDate())) {
            return redirect(public_link(''));
        }
        $parent = Tour::getById($tour['parent_id']);
        if(!$parent) {
            return eView::getInstance()->setView404();
        }
        HtmlHelper::getInstance()->setTitle('Đặt tour '.$parent['name']);

        $tpl['tour'] = $tour;
        $tpl['TOURLE'] = Tour::TOURLE;
        $tpl['parent'] = $parent;
        $tpl['lsPay'] = Payment::getListPayment();
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'index', $tpl);
    }

    function _save() {
        $member = request('member', []);

        if(!$member) {
            return eView::getInstance()->getJsonError('Thông tin cá nhân chưa cập nhật. Vui lòng kiểm tra lại.');
        }
        $nguoiLon = request('nguoiLon', []);
        if (!isset($nguoiLon['total']) || $nguoiLon['total'] < 1) {
            return eView::getInstance()->getJsonError('Số lượng người lớn không hợp lệ. Vui lòng kiểm tra lại.');
        }
        $treEm = request('treEm', []);
        if($treEm['total'] <= 0) {
            $treEm = [];
        }
        $treNho = request('treNho', []);
        if($treNho['total'] <= 0) {
            $treNho = [];
        }
        $tourSKU = request('tourSKU', 0);
        $tour = Tour::getBySku($tourSKU);
        if(!$tour) {
            return eView::getInstance()->getJsonError('Không tìm thấy thông tin tour có mã "'.$tourSKU.'"');
        }
        if(@$tour['tour_hang_tuan'] == Tour::TOURHANGTUAN || @$tour['tour_hang_tuan'] == Tour::TOURHANGNGAY) {

        }else {

            if(Helper::convertTimeToInt($tour['ngay_khoi_hanh']) < Helper::convertTimeToInt(Helper::getMongoDate())) {
                // tour kết thúc rồi
                $tourSKU = \request('tourKhoiHanhSKU');
                $tour = TourKhoiHanh::getBySku($tourSKU);
                if(!$tour) {
                    return eView::getInstance()->getJsonError('Không tìm thấy thông tin lịch khởi hành tour có mã "'.$tourSKU.'"');
                }
                if(Helper::convertTimeToInt($tour['ngay_khoi_hanh']) < Helper::convertTimeToInt(Helper::getMongoDate())) {
                    // tour kết thúc rồi
                    return eView::getInstance()->getJsonError('Tour đã khởi hành. Vui lòng lựa chọn tour khác.');
                }

            }
        }

        $pay = request('PAY');
        $lsPayment =Payment::getListPayment();
        if(!isset($lsPayment[$pay])) {
            return eView::getInstance()->getJsonError('Hình thức thanh toán không hợp lệ. Vui lòng kiểm tra lại.');
        }
        $totalMoney = $nguoiLon['total']*$tour['gia_nguoi_lon']+@$treEm['total']*$tour['gia_tre_em']+@$treNho['total']*$tour['gia_tre_nho'];
        $objToSave = [
            'code' => uniqid('BOOKING_'.preg_replace('/\s+/', '', $member['name'])),
            'info' => $member,
            'nguoi_lon' => $nguoiLon,
            'tre_em' => $treEm,
            'tre_nho' => $treNho,
            'chi_tiet_tour' => $tour,
            'payment' => $pay,
            'total_money' => $totalMoney,
            'status' => Booking::STATUS_PENDING,
            'created_by' => Member::getCreatedByToSaveDb(),
            'created_at' => Helper::getMongoDate()
        ];
        $id = Booking::insertGetId($objToSave);
        /*Sendmail*/
        if (!empty($member['email']) && $member['send_email'] == 'yes') {
            $tpl['obj'] = $objToSave;
            $tpl['name'] = 'Đặt tour thành công';
            $tpl['tokenString'] = Helper::buildTokenString($id);
            $tpl['url'] = route('FeBookingSearch', ['id' => $objToSave['code']]);
            $tpl['subject'] = '[Hệ thống quản lý Vietrantour] Đặt tour thành công';
            $tpl['template'] = "mail.order";
            EmailHelper::sendMail($member['email'], $tpl);
        }
        return eView::getInstance()->getJsonSuccess('Cập nhật thông tin đặt tour thành công.', ['reload' => true, 'code' => $objToSave['code']]);
    }
    function _save_cart() {
        $member = request('member', []);
        $cart = request('cart', 0);

        if(!$member) {
            return eView::getInstance()->getJsonError('Thông tin cá nhân chưa cập nhật. Vui lòng kiểm tra lại.');
        }
        $lsObj = request('lsObj', 0);
        if($lsObj['number'] <= 0) {
            return eView::getInstance()->getJsonError('Giỏ hàng của bạn hiện tại đang trống.');
        }
        if($lsObj['details']) {
            foreach ($lsObj['details'] as $tour) {
                $tong_so_luong = $tour['info']['nguoi_lon']['total']+$tour['info']['tre_em']['total']+$tour['info']['tre_nho']['total'];
                if ($tour['so_luong_khach_toi_da'] < $tong_so_luong) {
                    return eView::getInstance()->getJsonError('Số chỗ còn nhận cho tour '.$tour['name'].' là '.$tour['so_luong_khach_toi_da'].'. Quý khách cần chọn lại số lượng khách.');
                }
                $tour = Tour::getBySku($tour['sku']);
                if(!$tour) {
                    return eView::getInstance()->getJsonError('Không tìm thấy tour có mã "'.$tour['sku'].'"');
                }
                if($tour['status'] != Tour::STATUS_ACTIVE) {
                    return eView::getInstance()->getJsonError('Mã tour "'.$tour['sku'].'" đã kết thúc. Vui lòng lựa chọn tour khác.');
                }
                if(@$tour['tour_hang_tuan'] == Tour::TOURHANGTUAN) {

                }else {
                    if(Helper::convertTimeToInt($tour['ngay_khoi_hanh']) < Helper::convertTimeToInt(Helper::getMongoDate())) {
                        // tour kết thúc rồi
                        return eView::getInstance()->getJsonError('Mã tour "'.$tour['sku'].'" đã khởi hành. Vui lòng lựa chọn tour khác.');
                    }
                }
            }
        }


        $pay = request('PAY');
        $lsPayment =Payment::getListPayment();
        if(!isset($lsPayment[$pay])) {
            return eView::getInstance()->getJsonError('Hình thức thanh toán không hợp lệ. Vui lòng kiểm tra lại.');
        }
        $totalMoney = @$lsObj['grandTotal'];
        $objToSave = [
            'code' => uniqid('BOOKING_'.preg_replace('/\s+/', '', $member['name'])),
            'info' => $member,
            'chi_tiet_gio_hang' => $lsObj['details'],
            'payment' => $pay,
            'total_money' => $totalMoney,
            'number' => $lsObj['number'],
            'status' => Booking::STATUS_PENDING,
            'created_by' => Member::getCreatedByToSaveDb(),
            'created_at' => Helper::getMongoDate()
        ];
        $id = Booking::insertGetId($objToSave);
        /*Sendmail*/
        if (!empty($member['email'])) {
            $tpl['obj'] = $objToSave;
            $tpl['name'] = 'Đặt tour thành công';
            $tpl['tokenString'] = Helper::buildTokenString($id);
            $tpl['url'] = route('FeBookingSearch', ['id' => $objToSave['code']]);
            $tpl['subject'] = '[Hệ thống quản lý Vietrantour] Đặt tour thành công';
            $tpl['template'] = "mail.order";
            EmailHelper::sendMail($member['email'], $tpl);
        }
        Cart::getInstance()->destroyCart();
        return eView::getInstance()->getJsonSuccess('Cập nhật thông tin đặt tour thành công.', ['reload' => true, 'code' => $objToSave['code']]);
    }

    function detail($id) {
        $tpl = [];

        if ($id) {
            $tpl['id'] = $id;
        }
        $obj = Booking::where('code', $id)->first();
        if ($obj) {
            $tpl['obj'] = $obj;
        }
        HtmlHelper::getInstance()->setTitle('Thông tin mã booking '.$obj['code']);
        $currentMember = Member::getCurent();
        $tpl['currentMember'] = $currentMember;
        return eView::getInstance()->setViewBackEnd(__DIR__, 'include.detail-booking', $tpl);
    }

    function tour_lien_quan($qdiaDiemDen)
    {
        $diaDiemDen = Location::getByAlias($qdiaDiemDen);
        $curPage = (int)request('page', 1);
        $itemPerPage = Request::capture()->input('row', 24);
        $lsObj = [];
        if($diaDiemDen) {
            $lsChild = Location::where('parent_id', $diaDiemDen['_id'])->get()->pluck('alias')->toArray();
            if($lsChild) {
                $lsChild[] = $qdiaDiemDen;
                $where['dia_diem_den.alias'] = ['$in' => $lsChild];
            }else {
                $where['dia_diem_den.alias'] = $qdiaDiemDen;
            }
            $lsObj = Tour::where($where)->select(Tour::$basicFiledsForList);
            $lsObj = Pager::getInstance()->getPager($lsObj, $itemPerPage, $curPage);
        }
        return $lsObj;
    }
}