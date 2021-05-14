<?php

/**
 * Created by PhpStorm.
 * User: Sakura
 * Date: 5/16/2016
 * Time: 12:24 PM
 */

namespace App\Http\Controllers\AdminMember;

use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Models\Logs;
use App\Http\Models\Member;

use App\Http\Models\Menu;
use App\Http\Models\SocialFacebookAccount;
use App\Http\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;

class MemberGate extends Controller
{
    public function index($action = '')
    {
       // Debug::show(Staff::getPartmentOfStaff("5afa8f4a5675a42c45339889"));
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->login();
        }
    }

    public function login()
    {
        HtmlHelper::getInstance()->setTitle('Đăng nhập hệ thống');
        // echo Member::encodePassword('miniprivate');
        #region xử lý đăng nhập
        $obj = Request::capture()->input('obj', []);
        if(isset(Helper::getSession('clgt_session')['_id'])) {
            return redirect('/');
        }
        if (!empty($_POST)) {
            if (!isset($obj['account']) || !$obj['account']) {
                return eView::getInstance()->getJsonError('Bạn vui lòng nhập tài khoản đăng nhập');
            } else {
                if (!isset($obj['password']) || !$obj['password']) {
                    return eView::getInstance()->getJsonError('Bạn vui lòng nhập mật khẩu đăng nhập');
                } else {
                    //Check tài khoản
                    if (Helper::isEmail($obj['account'])) {
                        $member = Member::getMemberByEmail($obj['account']);
                    } elseif (Helper::isPhoneNumber($obj['account'])) {
                        $member = Member::getMemberByPhone($obj['account']);
                    } else {
                        $member = Member::getMemberByAccount($obj['account']);
                    }
                    if (!$member) {
                        return eView::getInstance()->getJsonError('Không tìm thấy tài khoản "' . $obj['account'] . '" trong hệ thống');
                    } else {
                        if($member['status'] != Member::STATUS_ACTIVE) {
                            return eView::getInstance()->getJsonError('Tài khoản "' . $obj['account'] . '" chưa được kích hoạt hoặc bị khoá.');
                        }
                        $obj['password'] = Member::genPassSave($obj['password']);
                        if ($obj['password'] == $member['password']) {
                            Member::setLogin($member);
                            Member::getCurent();
                            Logs::createLog([
                                'type' => Logs::TYPE_LOGIN,
                                'data_object' => $member,
                                'object_id' => $member['_id'],
                                'note' => "Khách hàng " . $member['name'] . ' tài khoản ["' . @$member['account'] . '"] đăng nhập hệ thống'
                            ], Logs::OBJECT_STAFF);
                            $refBeforeLogin = Request::capture()->input('href','');
                            if($refBeforeLogin){
                                return eView::getInstance()->getJsonSuccess('Đăng nhập thành công', ['redirect' => admin_link($refBeforeLogin)]);
                            }else{
                                return eView::getInstance()->getJsonSuccess('Đăng nhập thành công', ['reload' => true]);
                            }

                        } else {
                            //die(__FILE__.__LINE__);
                            return eView::getInstance()->getJsonError('Mật khẩu không đúng. Vui lòng kiểm tra lại');
                            //return Redirect('auth/login');
                        }

                    }
                }
            }
        }
        $tpl['obj'] = $obj;

        #endregion xuwrlys đăng nhập
        return eView::getInstance()->setView(__DIR__, 'member_gate/login', $tpl);
    }

    public function register()
    {
        HtmlHelper::getInstance()->setTitle('Đăng ký hệ thống');
        // echo Member::encodePassword('miniprivate');
        #region xử lý đăng nhập
        $obj = Request::capture()->input('obj', []);
        if(isset(Helper::getSession('clgt_session')['_id'])) {
            return redirect('/');
        }
        if (!empty($_POST)) {
            if (!isset($obj['name']) || !$obj['name']) {
                return eView::getInstance()->getJsonError('Bạn vui lòng nhập họ tên');
            } else {
                if (!isset($obj['email']) || !$obj['email']) {
                    return eView::getInstance()->getJsonError('Bạn vui lòng nhập địa chỉ email');
                }
                if (!isset($obj['account']) || !$obj['account']) {
                    return eView::getInstance()->getJsonError('Bạn vui lòng nhập tài khoản đăng nhập');
                }
                if (!Helper::isAccount($obj['account'])) {
                    return eView::getInstance()->getJsonError('Tài khoản đăng nhập không hợp lệ');
                }
                if (!isset($obj['password']) || !$obj['password']) {
                    return eView::getInstance()->getJsonError('Bạn vui lòng nhập mật khẩu đăng nhập');
                } else {
                    //Check tài khoản
                    if (Helper::isEmail($obj['account'])) {
                        $member = Member::getMemberByEmail($obj['account']);
                    } elseif (Helper::isPhoneNumber($obj['account'])) {
                        $member = Member::getMemberByPhone($obj['account']);
                    } else {
                        $member = Member::getMemberByAccount($obj['account']);
                    }
                    if ($member) {
                        return eView::getInstance()->getJsonError('Tài khoản "' . $obj['account'] . '" đã tồn tại trong hệ thống');
                    } else {
                        $code = rand(100000, 999999);
                        $objToSaveMember = [
                            'account' => $obj['account'],
                            'name' => $obj['name'],
                            'email' => $obj['email'],
                            'status' => Member::STATUS_ACTIVE,
                            'code' => $code,
                            'created_at' => Helper::getMongoDate(),
                            'verified' => [
                                'phone' => Member::VERIFIED_NO,
                                'email' => Member::VERIFIED_NO,
                            ],
                            'password' => Member::genPassSave($obj['password']),
                        ];
                        $customer = Member::createMember($objToSaveMember);
                    }
                }
            }
        }
        $tpl['obj'] = $obj;

        #endregion xuwrlys đăng nhập
        return eView::getInstance()->getJsonSuccess('Đăng ký tài khoản "'.$obj['account'].'" thành công. Vui lòng đăng nhập lại để sử dụng tài khoản.', []);
    }

    public function _createMemberRoot()
    {
        $member = Member::getMemberByAccount('Khoa');
        if (!$member) {
            $initRootMember = [
                'account' => 'Khoa',
                'name'  => 'Nguyễn Văn Khoa',
                'email' => 'khoait109@gmail.com',
                'phone' => '0886509919',
                'created_at' => Helper::getMongoDate(),
                'updated_at' => Helper::getMongoDate(),
                'status'     => Member::STATUS_ACTIVE,
                'password'   => Member::genPassSave('jekayn.com'),
                'access_token' => [
                    'facebook' => '',
                    'google'   => '',
                ],
            ];
            Member::insert($initRootMember);
            die('Ok boy!');
        } else {
            die('RootInit Done!');
        }
    }

    public function logout()
    {
        Member::setLogOut();
        return Redirect('auth/login');
    }

    public function forgot()
    {
        return eView::getInstance()->setView(__DIR__, 'member_gate/forgotpass', []);
    }

    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        try {
            $fb_user = \Socialite::driver('facebook')->stateless()->user();
        } catch(Exception $e){
            return redirect('/');
        }
        $customer = $this->createOrGetUser($fb_user);
        if ($customer) {
            if ($customer['status'] != Member::STATUS_ACTIVE) {
                return redirect()->route('FeHome')->withErrors(['fb_login_fail' => 'Tài khoản kết nối đang tạm khóa hoặc bị vô hiệu hóa']);
            }
            Member::setLogin($customer);
            Member::getCurent();
            Logs::createLog([
                'type' => Logs::TYPE_LOGIN,
                'data_object' => $customer,
                'object_id' => $customer['_id'],
                'note' => "Khách hàng " . $customer['name'] . ' tài khoản ["' . @$customer['account'] . '"] đăng nhập hệ thống'
            ], Logs::OBJECT_STAFF);
            $refBeforeLogin = Request::capture()->input('href','');
            if($refBeforeLogin){
                return redirect(public_link($refBeforeLogin));//đúng thì cho vào
            }else{
                return \Redirect::to(\URL::previous());
            }
        }
        return redirect()->route('FeHome')->withErrors(['fb_login_fail' => 'Bạn chưa chia sẻ Email trên Facebook']);
    }

    function createOrGetUser(ProviderUser $providerUser) {
        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->toArray();
        }
        $email = $providerUser->getEmail();

        if (!empty($email)) {
            $customer = Member::whereEmail($email)->first();
            if (!$customer) {
                $objToSave = [
                    'account' => $email,
                    'email' => $email,
                    'name' => $providerUser->getName(),
                    'password' => Member::genPassSave('facebook@'.$email),
                    'status' => Member::STATUS_ACTIVE,
                    'provider_user_id' => $providerUser->getId(),
                    'provider' => 'facebook',
                    'created_at'  => Helper::getMongoDate(),
                ];
                $id = Member::insertGetId($objToSave);
                $customer = Member::find($id);
            } else {
                if ($customer->status == Member::STATUS_ACTIVE) {
                    $customer->name = $providerUser->getName();
                    $customer->reg_ip = \Request::ip();
                }
                $customer->save();
            }

            return $customer;
        }

        return false;
    }
}