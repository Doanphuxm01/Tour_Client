<?php


namespace App\Http\Controllers\API;


use App\Elibs\Cart;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Http\Models\Tour;

class tour_api extends AppApi
{
    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {

        }
    }

    public function check_member_valid()
    {
        $sku = request('tourSKU', 0);
        $cart = request('cart', 0);
        if ($cart) {
            $lsObj = Cart::getInstance()->content();
            if($lsObj['number'] <= 0) {
                return $this->outputError('Giỏ hàng của bạn hiện tại đang trống.');
            }
            if($lsObj['details']) {
                foreach ($lsObj['details'] as $tour) {
                    $tour = Tour::getBySku($tour['sku']);
                    if(!$tour) {
                        return $this->outputError('Không tìm thấy tour có mã "'.$tour['sku'].'"');
                    }
                    if($tour['status'] != Tour::STATUS_ACTIVE) {
                        return $this->outputError('Mã tour "'.$tour['sku'].'" đã kết thúc. Vui lòng lựa chọn tour khác.');
                    }
                }
            }
        }else {
            $tour = Tour::getBySku($sku);
            if(!$tour) {
                return $this->outputError('Không tìm thấy tour có mã "'.$sku.'"');
            }
            if($tour['status'] != Tour::STATUS_ACTIVE) {
                return $this->outputError('Mã tour "'.$sku.'" đã kết thúc. Vui lòng lựa chọn tour khác.');
            }
        }

        $member = request('member', []);
        $tab = request('tab');
        if($tab == 'tab_info') {
            if(!$member) {
                return $this->outputError('Thông tin cá nhân chưa cập nhật. Vui lòng kiểm tra lại.');
            }
            if(!@$member['name']) {
                return $this->outputError('Họ tên không được bỏ trống. Vui lòng kiểm tra lại.');
            }
            $member['name'] = strip_tags($member['name']);
            if(!@$member['phone']) {
                return $this->outputError('Di động không được bỏ trống. Vui lòng kiểm tra lại.');
            }
            if(!Helper::isPhoneNumber($member['phone'])) {
                return $this->outputError('Số di động không hợp lệ. Vui lòng kiểm tra lại.');
            }
            // if(!@$member['email']) {
            //     return $this->outputError('Email không được bỏ trống. Vui lòng kiểm tra lại.');
            // }
            // if(!Helper::isEmail($member['email'])) {
            //     return $this->outputError('Email không hợp lệ. Vui lòng kiểm tra lại.');
            // }
            if(!@$member['check']) {
                return $this->outputError('Để tiếp tục, quý khách có đồng ý với Điều khoản và Điều kiện của chúng tôi');
            }
        }

        if($tab == 'tab_list_member') {
            if(!$cart) {
                $nguoiLon = request('nguoiLon', []);
                $treEm = request('treEm', []);
                $treNho = request('treNho', []);

                $tong_so_luong = $nguoiLon['total']+$treEm['total']+$treNho['total'];
                $so_luong_con_lai = $tour['so_luong_khach_toi_da']-@$tour['so_luong_khach_treo_gio'];
                if ($so_luong_con_lai < $tong_so_luong) {
                    return $this->outputError('Số chỗ còn nhận cho tour này là '.$so_luong_con_lai.'. Quý khách cần chọn lại số lượng khách.');
                }
                if (!isset($nguoiLon['total']) || $nguoiLon['total'] < 1) {
                    return $this->outputError('Số lượng người lớn không hợp lệ. Vui lòng kiểm tra lại.');
                }
                // foreach ($nguoiLon['list'] as $k => $item) {
                //     if($k == 0) {
                //         if(!$item['name']  || !$item['phone'] || !$item['email']) {
                //             return $this->outputError('Thông tin cá nhân các thành viên chưa cập nhật. Vui lòng kiểm tra lại.');
                //             break;
                //         }
                //     }
                // }
            }else {
                $lsObj = request('lsObj', 0);
                if($lsObj['details']) {
                    foreach ($lsObj['details'] as $tour) {
                        $tong_so_luong = $tour['info']['nguoi_lon']['total']+$tour['info']['tre_em']['total']+$tour['info']['tre_nho']['total'];
                        if ($tour['so_luong_khach_toi_da'] < $tong_so_luong) {
                            return $this->outputError('Số chỗ còn nhận cho tour '.$tour['name'].' là '.$tour['so_luong_khach_toi_da'].'. Quý khách cần chọn lại số lượng khách.');
                        }
                    }
                }

            }

            /*if (isset($treEm['total']) && $treEm['total'] > 0) {
                foreach ($treEm['list'] as $item) {
                    if(!$item['name']  || !$item['age'] || !$item['birthday']) {
                        return $this->outputError('Thông tin cá nhân các trẻ em chưa cập nhật. Vui lòng kiểm tra lại.');
                        break;
                    }
                }
            }

            if (isset($treNho['total']) && $treNho['total'] > 0) {
                foreach ($treNho['list'] as $item) {
                    if(!$item['name']  || !$item['age'] || !$item['birthday']) {
                        return $this->outputError('Thông tin cá nhân các trẻ nhỏ chưa cập nhật. Vui lòng kiểm tra lại.');
                        break;
                    }
                }
            }*/


        }

        return $this->outputDone([], 'Lấy dữ liệu thành công');
    }
}
