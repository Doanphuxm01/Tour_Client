<?php
namespace App\Http\Controllers\_Dev;

use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\SearchHelper;
use App\Http\Controllers\Controller;
use App\Http\Models\Book;
use Illuminate\Http\Request;
use App\Http\Models\Cate;
use App\Elibs\Pager;

require_once app_path('Elibs/simple_html_dom.php');

class v1 extends Controller
{
    function createCate()
    {
        $step = 3;

        if ($step == 3) {

            $allMenuSubject = Cate::where([
                'type' => Cate::$cateTypeRegister['menu-subject']['key']
            ])->get();
            //Debug::show($allMenuSubject);
            foreach ($allMenuSubject as $cate) {
                $link = str_replace('../', 'http://vietjack.com/', $cate->link_cawl);
                $html = Helper::getUrlContent($link);
                if ($html) {
                    $html = str_get_html($html);
                    if (!$html) {
                        // Debug::show('KHONG LAY DC HTML');
                    } else {
                        if ($link == 'http://vietjack.com/series/soan-van.jsp' || $link == 'http://vietjack.com/series/van-mau.jsp') {
                            $listCateDom = $html->find('ul.list', 0);
                            $listCateDom = [$listCateDom];
                        } else {
                            $listCateDom = $html->find('ul.list');
                        }

                        if ($listCateDom) {
                            foreach ($listCateDom as $_item) {
                                foreach ($_item->find('li a') as $item) {
                                    if (strpos($item->href, 'http://vietjack.com') === false && $item->href!='https://goo.gl/y3kTwA') {
                                        $href = str_replace('../', './', $item->href);
                                        $name = $item->plaintext;
                                        $cateByLink = Cate::where(['link_cawl' => $href])->first();
                                        if ($cateByLink) {
                                            //da t???n t???i th?? update parent
                                            $cateParent = $cateByLink->parents;
                                            $canAdd = true;
                                            foreach ($cateParent as $key=>$val){
                                                if($val['alias']==$cate->alias){
                                                    $canAdd = false;
                                                    break;
                                                }
                                            }
                                            if($canAdd) {
                                                $cateParent[] = [
                                                    'name' => $cate->name,
                                                    'alias' => $cate->alias,
                                                    'type' => $cate->type,
                                                    'object' => $cate->object,
                                                ];
                                                $_saveToCate = [
                                                    'parents' => $cateParent
                                                ];
                                                Debug::show($_saveToCate, $href, 'green');
                                                Cate::where(['_id' => $cateByLink->_id])->update($_saveToCate);
                                            }else{
                                                Debug::show('No add',$href,'pink');
                                            }

                                        } else {
                                            //ch??a c?? th?? th??m m???i v?? c??i g?? thi???u th?? sau n??y update sau
                                            $cateParent = [];
                                            $cateParent = [
                                                'name' => $cate->name,
                                                'alias' => $cate->alias,
                                                'type' => $cate->type,
                                                'object' => $cate->object,
                                            ];
                                            $namex = str_replace('C#', 'C sharp', $name);
                                            $namex = str_replace('C++', 'C plus plus', $namex);
                                            $saveToCate = [
                                                'name' => $name,
                                                'alias' => Helper::convertToAlias($namex),
                                                'status' => Cate::STATUS_ACTIVE,
                                                'type' => Cate::$cateTypeRegister['cate']['key'],
                                                'object' => Cate::$cateObjectRegister['subject']['key'],
                                                'link_cawl' => $href,
                                                'parents' => [
                                                    $cateParent
                                                ],
                                            ];
                                            if (Cate::getCateByAlias($saveToCate['alias'])) {
                                                Debug::show('???? T???N T???I');
                                            } else {
                                                Debug::show($saveToCate);
                                                Cate::insert($saveToCate);
                                            }


                                        }
                                    }else{
                                        Debug::show('NO ADD TO DB',$item->href,'violet');
                                    }
                                }

                            }
                        }
                    }
                } else {
                    Debug::show('MEO CRALW DC CUA NO');
                }
            }
            die();
        }
        if ($step == 2) {


            $string = '<ul class="nav nav-list primary left-menu">
<li class="heading">M???c l???c b??i h???c theo m??n</li>
<li><a href="../series/soan-van.jsp" style="background-color: rgb(255, 165, 0);">So???n v??n</a></li>
<li><a href="../series/van-mau.jsp">V??n m???u</a></li>
<li><a href="../series/mon-toan.jsp">M??n To??n</a></li>
<li><a href="../series/mon-tieng-anh.jsp">M??n Ti???ng anh</a></li>
<li><a href="../series/mon-vat-li.jsp">M??n V???t L??</a></li>
<li><a href="../series/mon-hoa-hoc.jsp">M??n Ho?? h???c</a></li>
<li><a href="../series/mon-sinh-hoc.jsp">M??n Sinh h???c</a></li>
<li><a href="../series/mon-lich-su.jsp">M??n L???ch s???</a></li>
<li><a href="../series/mon-dia-li.jsp">M??n ?????a L??</a></li>
<li><a href="../series/mon-gdcd.jsp">M??n GDCD</a></li>
<li><a href="../series/mon-tin-hoc.jsp">M??n Tin h???c</a></li>
<li><a href="../series/mon-cong-nghe.jsp">M??n C??ng ngh???</a></li>
<li><a href="../series/it-lap-trinh.jsp">IT - L???p tr??nh</a></li>

dsfsfasdfasdfsdf
</ul>';
            $html = str_get_html($string);
            foreach ($html->find('a') as $item) {
                $name = $item->plaintext;
                $alias = Helper::convertToAlias($name);
                $saveToMenu = [
                    'name' => $name,
                    'alias' => $alias,
                    'status' => Cate::STATUS_ACTIVE,
                    'type' => Cate::$cateTypeRegister['menu-subject']['key'],
                    'object' => Cate::$cateObjectRegister['subject']['key'],
                    'link_cawl' => $item->href
                ];
                Cate::insert($saveToMenu);
            }

            die();
        }
        if ($step == 1) {
            $string = '<ul class="nav navbar-nav">
                                <li class="level-1">
                                   <a href="./series/lop-3.jsp" class="">L???p 3</a>
                                   <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./tieng-viet-lop-3/index.jsp">So???n Ti???ng Vi???t l???p 3</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-3/index.jsp">Gi???i To??n l???p 3</a> </li>
                                   </ul>
                                   </li>
                                <li class="level-1">
                                  <a href="./series/lop-4.jsp" class="">L???p 4</a>
                                  <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./tieng-viet-lop-4/index.jsp">So???n Ti???ng Vi???t l???p 4</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-4/index.jsp">Gi???i To??n l???p 4</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-4/index.jsp">????? ki???m tra To??n 4 (ph???n 1)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-lop-4/index.jsp">????? ki???m tra To??n 4 (ph???n 2)</a> </li>
                                   
                                   </ul>
                                   </li>
                                <li class="level-1">
                                   <a href="./series/lop-5.jsp" class="">L???p 5</a>
                                   <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./tieng-viet-lop-5/index.jsp">So???n Ti???ng Vi???t l???p 5</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-5/index.jsp">Gi???i To??n l???p 5</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-5/index.jsp">????? ki???m tra To??n 5</a> </li>
                                        
                                   </ul>
                                   </li>
                                <li class="level-1">
                                  <a href="./series/lop-6.jsp" class="">L???p 6</a>
                                  <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-6/index.jsp">So???n V??n 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-6/index.jsp">So???n V??n 6 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-6/index.jsp">V??n m???u l???p 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-6/index.jsp">Gi???i To??n 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-toan-6/index.jsp">Gi???i SBT To??n 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-6/index.jsp">????? ki???m tra To??n 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-6/index.jsp">Gi???i V???t L?? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-vat-li-6/index.jsp">Gi???i SBT V???t L?? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-vat-li-6/index.jsp">????? ki???m tra V???t L?? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-6/index.jsp">Gi???i Sinh 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-sinh-hoc-6/index.jsp">BT tr???c nghi???m Sinh 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-sinh-hoc-6/index.jsp">????? ki???m tra Sinh 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-6/index.jsp">Gi???i ?????a L?? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-6/index.jsp">T???p b???n ????? ?????a L?? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-6/index.jsp">Gi???i Ti???ng Anh 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-6-moi/index.jsp">Gi???i Ti???ng Anh 6 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-tieng-anh-6-moi/index.jsp">Gi???i SBT Ti???ng Anh 6 m???i</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-6/index.jsp">Gi???i L???ch s??? 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-6/index.jsp">Gi???i Tin h???c 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-6/index.jsp">Gi???i GDCD 6</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-cong-nghe-6/index.jsp">Gi???i C??ng ngh??? 6</a> </li>
                                        
                                    </ul>
                                    </li>
                                <li class="level-1">
                                    <a href="./series/lop-7.jsp" class="">L???p 7</a>
                                    <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-7/index.jsp">So???n V??n 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-7/index.jsp">So???n V??n 7 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-7/index.jsp">V??n m???u l???p 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-7/index.jsp">Gi???i To??n 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-toan-7/index.jsp">Gi???i SBT To??n 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-7/index.jsp">Gi???i V???t L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-vat-li-7/index.jsp">Gi???i SBT V???t L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-vat-li-7/index.jsp">BT tr???c nghi???m V???t L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-vat-li-7/index.jsp">????? ki???m tra V???t L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-7/index.jsp">Gi???i Sinh 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-sinh-hoc-7/index.jsp">????? ki???m tra Sinh 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-7/index.jsp">Gi???i ?????a L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-7/index.jsp">T???p b???n ????? ?????a L?? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-7/index.jsp">Gi???i Ti???ng Anh 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-tieng-anh-7/index.jsp">Gi???i SBT Ti???ng Anh 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-7-moi/index.jsp">Gi???i Ti???ng Anh 7 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-7/index.jsp">Gi???i L???ch s??? 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-7/index.jsp">Gi???i Tin h???c 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-7/index.jsp">Gi???i GDCD 7</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-cong-nghe-7/index.jsp">Gi???i C??ng ngh??? 7</a> </li>
                                        
                                    </ul>
                                    </li>
                                <li class="level-1">
                                    <a href="./series/lop-8.jsp" class="">L???p 8</a>
                                     <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-8/index.jsp">So???n V??n 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-8/index.jsp">So???n V??n 8 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-8/index.jsp">V??n m???u l???p 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-8/index.jsp">Gi???i To??n 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-toan-8/index.jsp">Gi???i SBT To??n 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-8/index.jsp">????? ki???m tra To??n 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-8/index.jsp">Gi???i V???t L?? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-vat-li-8/index.jsp">Gi???i SBT V???t L?? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-vat-li-8/index.jsp">????? ki???m tra V???t L?? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-hoa-lop-8/index.jsp">Gi???i H??a 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-hoa-8/index.jsp">Gi???i SBT H??a 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-hoa-hoc-8/index.jsp">????? ki???m tra H??a 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-8/index.jsp">Gi???i Sinh 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-sinh-hoc-8/index.jsp">BT tr???c nghi???m Sinh 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-8/index.jsp">Gi???i ?????a L?? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-8/index.jsp">T???p b???n ????? ?????a L?? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-8/index.jsp">Gi???i Ti???ng Anh 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-8-moi/index.jsp">Gi???i Ti???ng Anh 8 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-8/index.jsp">Gi???i L???ch s??? 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-8/index.jsp">Gi???i Tin h???c 8</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-8/index.jsp">Gi???i GDCD 8</a> </li>
                                    </ul></li>
                                <li class="level-1">
                                   <a href="./series/lop-9.jsp" class="">L???p 9</a>
                                   <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-9/index.jsp">So???n V??n 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-9/index.jsp">So???n V??n 9 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-9/index.jsp">V??n m???u l???p 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-9/index.jsp">Gi???i To??n 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-toan-9/index.jsp">Gi???i SBT To??n 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./chuyen-de-toan-9/index.jsp">Chuy??n ????? To??n 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-9/index.jsp">????? ki???m tra To??n 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-9/index.jsp">Gi???i V???t L?? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-vat-li-9/index.jsp">Gi???i SBT V???t L?? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-vat-li-9/index.jsp">????? ki???m tra V???t L?? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-hoa-lop-9/index.jsp">Gi???i H??a 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-hoa-hoc-9/index.jsp">????? ki???m tra H??a 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-9/index.jsp">Gi???i Sinh 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./chuyen-de-sinh-hoc-9/index.jsp">Chuy??n ????? Sinh 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-9/index.jsp">Gi???i ?????a L?? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-9/index.jsp">T???p b???n ????? ?????a L?? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-9/index.jsp">Gi???i Ti???ng Anh 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-sach-bai-tap-tieng-anh-9/index.jsp">Gi???i SBT Ti???ng Anh 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-9-moi/index.jsp">Gi???i Ti???ng Anh 9 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-9/index.jsp">Gi???i L???ch s??? 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-9/index.jsp">Gi???i Tin h???c 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-9/index.jsp">Gi???i GDCD 9</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-cong-nghe-9/index.jsp">Gi???i C??ng ngh??? 9</a> </li>
                                        
                                    </ul></li>
                                <li class="level-1">
                                    <a href="./series/lop-10.jsp" class="">L???p 10</a>
                                    <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-10/index.jsp">So???n V??n 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-10/index.jsp">So???n V??n 10 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-10/index.jsp">V??n m???u l???p 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-10/index.jsp">Gi???i To??n 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-10-nang-cao/index.jsp">Gi???i To??n 10 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-10/index.jsp">Gi???i V???t L?? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-ly-10-nang-cao/index.jsp">Gi???i V???t L?? 10 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-hoa-lop-10/index.jsp">Gi???i H??a 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-hoa-10-nang-cao/index.jsp">Gi???i H??a 10 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-10/index.jsp">Gi???i Sinh 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-10/index.jsp">Gi???i ?????a L?? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-10/index.jsp">Gi???i Ti???ng Anh 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-10-moi/index.jsp">Gi???i Ti???ng Anh 10 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-10/index.jsp">Gi???i L???ch s??? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-10/index.jsp">Gi???i Tin h???c 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-10/index.jsp">Gi???i GDCD 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-cong-nghe-10/index.jsp">Gi???i C??ng ngh??? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hinh-hoc-10/index.jsp">BT tr???c nghi???m H??nh 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-vat-li-10/index.jsp">BT tr???c nghi???m V???t L?? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hoa-10/index.jsp">BT tr???c nghi???m H??a 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-sinh-hoc-10/index.jsp">BT tr???c nghi???m Sinh 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-dia-li-10/index.jsp">BT tr???c nghi???m ?????a L?? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-dia-li-10/index.jsp">????? ki???m tra ?????a L?? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-lich-su-10/index.jsp">BT tr???c nghi???m L???ch S??? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-lich-su-10/index.jsp">????? ki???m tra L???ch S??? 10</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-gdcd-10/index.jsp">BT tr???c nghi???m GDCD 10</a> </li>
                                     
                                    </ul>
                                </li>
                                <li class="level-1">
                                <a href="./series/lop-11.jsp" class="">L???p 11</a>
                                <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-11/index.jsp">So???n V??n 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-11/index.jsp">So???n V??n 11 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-11/index.jsp">V??n m???u l???p 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-11/index.jsp">Gi???i To??n 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-11-nang-cao/index.jsp">Gi???i To??n 11 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-11/index.jsp">Gi???i V???t L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-ly-11-nang-cao/index.jsp">Gi???i V???t L?? 11 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-hoa-lop-11/index.jsp">Gi???i H??a 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-hoa-11-nang-cao/index.jsp">Gi???i H??a 11 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-11/index.jsp">Gi???i Sinh 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-11/index.jsp">Gi???i ?????a L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-11/index.jsp">T???p b???n ????? ?????a L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-11/index.jsp">Gi???i Ti???ng Anh 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-11-moi/index.jsp">Gi???i Ti???ng Anh 11 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-11/index.jsp">Gi???i L???ch s??? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-11/index.jsp">Gi???i Tin h???c 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-11/index.jsp">Gi???i GDCD 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-dai-so-va-giai-tich-11/index.jsp">BT tr???c nghi???m Gi???i t??ch 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hinh-hoc-11/index.jsp">BT tr???c nghi???m H??nh 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-toan-11/index.jsp">????? ki???m tra To??n l???p 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-vat-li-11/index.jsp">BT tr???c nghi???m V???t L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hoa-11/index.jsp">BT tr???c nghi???m H??a 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-sinh-hoc-11/index.jsp">BT tr???c nghi???m Sinh 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-dia-li-11/index.jsp">BT tr???c nghi???m ?????a L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./de-kiem-tra-dia-li-11/index.jsp">????? ki???m tra ?????a L?? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-lich-su-11/index.jsp">BT tr???c nghi???m L???ch S??? 11</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-gdcd-11/index.jsp">BT tr???c nghi???m GDCD 11</a> </li>
                                     
                                    </ul>
                                    </li>
                                <li class="level-1">
                                <a href="./series/lop-12.jsp" class="">L???p 12</a>
                                   <ul class="menu-2 row">
                                        <li class="level-2 col-xs-6"><a href="./soan-van-lop-12/index.jsp">So???n V??n 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./soan-van-12/index.jsp">So???n V??n 12 (ng???n nh???t)</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./van-mau-lop-12/index.jsp">V??n m???u l???p 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-lop-12/index.jsp">Gi???i To??n 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-toan-12-nang-cao/index.jsp">Gi???i To??n 12 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-vat-ly-12/index.jsp">Gi???i V???t L?? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-ly-12-nang-cao/index.jsp">Gi???i V???t L?? 12 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-hoa-lop-12/index.jsp">Gi???i H??a 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-hoa-12-nang-cao/index.jsp">Gi???i H??a 12 n??ng cao</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-sinh-hoc-12/index.jsp">Gi???i Sinh 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./chuyen-de-sinh-hoc-12/index.jsp">Chuy??n ????? Sinh 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-dia-li-12/index.jsp">Gi???i ?????a L?? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-tap-ban-do-va-bai-tap-thuc-hanh-dia-li-12/index.jsp">T???p b???n ????? ?????a L?? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-12/index.jsp">Gi???i Ti???ng Anh 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./tieng-anh-12-moi/index.jsp">Gi???i Ti???ng Anh 12 th?? ??i???m</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-lich-su-12/index.jsp">Gi???i L???ch s??? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-tin-hoc-12/index.jsp">Gi???i Tin h???c 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./giai-bai-tap-giao-duc-cong-dan-12/index.jsp">Gi???i GDCD 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-giai-tich-12/index.jsp">BT tr???c nghi???m Gi???i t??ch 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hinh-hoc-12/index.jsp">BT tr???c nghi???m H??nh 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-vat-li-12/index.jsp">BT tr???c nghi???m V???t L?? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-hoa-12/index.jsp">BT tr???c nghi???m H??a 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./chuyen-de-sinh-hoc-12/index.jsp">Chuy??n ????? Sinh 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-dia-li-12/index.jsp">BT tr???c nghi???m ?????a L?? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-lich-su-12/index.jsp">BT tr???c nghi???m L???ch S??? 12</a> </li>
                                        <li class="level-2 col-xs-6"><a href="./bai-tap-trac-nghiem-gdcd-12/index.jsp">BT tr???c nghi???m GDCD 12</a> </li>  
                                
                                    </ul>
                                    </li>
                                    
                                </li>
                            </ul>';

            $html = str_get_html($string);
            $number = 0;
            foreach ($html->find('li.level-1') as $item) {
                $number = 0;
                $saveParent = [];
                foreach ($item->find('a') as $x) {
                    $name = $x->plaintext;
                    $alias = Helper::convertToAlias($name);
                    $cateInDb = Cate::getCateByAlias($alias);
                    if ($cateInDb) {

                    } else {
                        if ($number == 0) {
                            $saveToMenu = [
                                'name' => $name,
                                'alias' => $alias,
                                'status' => Cate::STATUS_ACTIVE,
                                'type' => Cate::$cateTypeRegister['menu-class']['key'],
                                'object' => Cate::$cateObjectRegister['subject']['key'],
                                'link_cawl' => $x->href
                            ];
                            $saveParent = [
                                'name' => $name,
                                'alias' => $alias,
                                'type' => Cate::$cateTypeRegister['menu-class']['key'],
                                'object' => Cate::$cateObjectRegister['subject']['key'],
                            ];
                            Cate::insert($saveToMenu);
                        } else {
                            $saveToCate = [
                                'name' => $name,
                                'alias' => $alias,
                                'status' => Cate::STATUS_ACTIVE,
                                'type' => Cate::$cateTypeRegister['cate']['key'],
                                'object' => Cate::$cateObjectRegister['subject']['key'],
                                'link_cawl' => $x->href,
                                'parents' => [
                                    $saveParent
                                ],
                            ];
                            //Debug::show($saveToCate);
                            Cate::insert($saveToCate);
                            //$saveParent = [];
                        }
                    }
                    $number++;
                }
            }
        }


    }

    function getCate(){

    }
}
