<?php


namespace App\Http\Controllers\FrontEnd\FeTours;


use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Elibs\Pager;
use App\Http\Controllers\FrontEndController;
use App\Http\Models\BaseModel;
use App\Http\Models\Combo;
use App\Http\Models\Location;
use App\Http\Models\Media;
use App\Http\Models\Tour;
use App\Http\Models\TourCategory;
use App\Http\Models\TourKhoiHanh;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeTour extends FrontEndController
{
    function index($alias) {
        $obj = Tour::getByAlias($alias);
        if ($obj) {
            return $this->detail($obj);
        }
        $obj = TourCategory::getByAlias($alias);
        if ($obj) {
            return $this->list($obj);
        }

        if($alias == 'tour-gio-chot') {
            return $this->list($alias);
        }elseif($alias == 'tour-khuyen-mai') {
            return $this->list($alias);
        }

        return eView::getInstance()->setView404();
    }

    function list($obj) {
        $tpl = [];
        if(is_array($obj)) {
            if (!isset($obj['status']) || $obj['status'] !== TourCategory::STATUS_ACTIVE) {
                return redirect('/');
            }
        }else {
            $alias = $obj;
            $obj = [];
            if($alias == 'tour-gio-chot') {
                $isGioChot = true;
                $tpl['countdown'] = $isGioChot;
                $obj['name'] = 'Tour giờ chót';
            }elseif($alias == 'tour-khuyen-mai') {
                $isSale = true;
                $obj['name'] = 'Tour khuyến mãi';
            }
        }

        HtmlHelper::getInstance()->setTitle($obj['name']);
        $curPage = (int)request('page', 1);
        $qtuyenTour = Request::capture()->input('tuyenTour');
        $qdiaDiemDen = Request::capture()->input('diaDiemDen');
        $qtimeBetween = Request::capture()->input('thoiGian');
        $qgia = Request::capture()->input('gia');
        $itemPerPage = Request::capture()->input('row', 24);
        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');
        $where = [
            'status' => Tour::STATUS_ACTIVE,
        ];
        if($qtuyenTour) {
            $where['tuyen_tour.alias'] = $qtuyenTour;
        }else {
            if(isset($isGioChot)) {
                $timeEnd = $tomorrow->addDays(Tour::RANGE_TIME)->format('d/m/Y');
                unset($where['$or']);
                $where['ngay_khoi_hanh'] = [
                    '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    '$lt' => Helper::getMongoDate($timeEnd, '/', false),
                ];
            }elseif(isset($isSale)) {
                $where['$expr'] = [
                    '$lt' => ['$gia_nguoi_lon','$gia_niem_yet']
                ];
            }else {
                $where['tuyen_tour.id'] = $obj['_id'];
            }
        }
        if($qdiaDiemDen) {
            $diaDiemDen = Location::getByAlias($qdiaDiemDen);
            if($diaDiemDen) {
                $lsChild = Location::where('parent_id', $diaDiemDen['_id'])->get()->pluck('alias')->toArray();
                if($lsChild) {
                    $lsChild[] = $qdiaDiemDen;
                    $where['dia_diem_den.alias'] = ['$in' => $lsChild];
                }else {
                    $where['dia_diem_den.alias'] = $qdiaDiemDen;
                }
            }
        }

        $lsObj = Tour::where($where)
            ->select(Tour::$basicFiledsForList)->orderBy('ngay_khoi_hanh', 'ASC');
        if($qgia) {
            $money = Helper::processRangeMoney($qgia);
            $tpl['qMoney'] = $money;
            $lsObj = $lsObj->where([
                '$or' => [
                    [
                        'gia_nguoi_lon' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_em' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_nho' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                ]
            ]);
        }
        if($qtimeBetween) {
            $time = Helper::processRangeDate($qtimeBetween);
            if (isset($time['time_start'])) {
                $lsObj = $lsObj->where([
                    '$or' => [
                        [
                            'ngay_khoi_hanh' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ],
                        [
                            'ngay_ket_thuc' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ]
                    ]
                ]);
            }
        }
        $lsObj = Pager::getInstance()->getPager($lsObj, $itemPerPage, $curPage);
        //dd($lsObj);
        $groupIdTourNhieuLichKhoiHanh = [];
        foreach ($lsObj as $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = $t['_id'];
            }
        }
        //$groupIdTourNhieuLichKhoiHanh = array_column($lsObj->items(), '_id');
        if(!empty($groupIdTourNhieuLichKhoiHanh)) {
            $groupTourNhieuLichKhoiHanh = TourKhoiHanh::whereStatus(TourKhoiHanh::STATUS_ACTIVE)
            ->where([
                'ngay_khoi_hanh' => [
                    '$gte' => Helper::getMongoDate($timeStart, '/', true),
                ],
            ])->
            whereIn('parent_id', $groupIdTourNhieuLichKhoiHanh)->select('ngay_khoi_hanh','gia_nguoi_lon', 'parent_id', 'status')->get()
                ->groupBy('parent_id')->toArray();
            // dd($groupIdTourNhieuLichKhoiHanh,$groupTourNhieuLichKhoiHanh);
            foreach($groupTourNhieuLichKhoiHanh as $key => $tour){
                $gia_min = $tour[0]['gia_nguoi_lon'];
                foreach($tour as $t) {
                    $gia_min = ($t['gia_nguoi_lon'] > $gia_min) ? $gia_min : $t['gia_nguoi_lon'];
                }
                $groupTourNhieuLichKhoiHanh[$key]['gia_nguoi_lon_min'] = $gia_min;
            }
            foreach ($lsObj as $_id => $t) {
                if(@$t['tour_hang_tuan'] == Tour::TOURLE && !isset($groupTourNhieuLichKhoiHanh[$t['_id']])) {
                    //dump($lsTourGroupByDanhMuc[$_alias][$key]);
                    unset($lsObj[$_id]);
                }
            }


            $tpl['groupTourNhieuLichKhoiHanh'] = $groupTourNhieuLichKhoiHanh;
        }
        $tpl['q'] = \request();
        $tpl['obj'] = $obj;
        $tpl['lsObj'] = $lsObj;
        $this->seo($obj);
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'list', $tpl);
    }

    function detail($obj) {
        $tpl = [];

        if (!isset($obj['status']) || $obj['status'] !== Tour::STATUS_ACTIVE) {
            return redirect('/');
        }
        HtmlHelper::getInstance()->setTitle($obj['name']);
        if(isset($obj['tour_hang_tuan']) && $obj['tour_hang_tuan'] == Tour::TOURLE) {
            $groupTourLe = TourKhoiHanh::getAllByParentId($obj['_id']);
            $tpl['groupTourLe'] = $groupTourLe;
            if(!empty($groupTourLe)) {
                $gia_min = @$groupTourLe[0]['gia_nguoi_lon'];
                foreach($groupTourLe as $key => $tour){
                    $gia_min = ($tour['gia_nguoi_lon'] > $gia_min) ? $gia_min : $tour['gia_nguoi_lon'];
                }
                $obj['gia_nguoi_lon'] = $gia_min;

            }

        }
        $tpl['obj'] = $obj;
        $tpl['dynamicPath'] = [];

        $this->seo($obj);
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'input', $tpl);
    }

    function place($alias) {
        $tpl = [];
        $obj = Location::getByAlias($alias);
        if (!$obj) {
            return eView::getInstance()->setView404();
        }
        if (!isset($obj['status']) || $obj['status'] !== TourCategory::STATUS_ACTIVE) {
            return redirect('/');
        }

        HtmlHelper::getInstance()->setTitle($obj['name']);
        $curPage = (int)request('page', 1);
        $qtuyenTour = Request::capture()->input('tuyenTour');
        $qdiaDiemDen = Request::capture()->input('diaDiemDen');
        $qtimeBetween = Request::capture()->input('thoiGian');
        $qgia = Request::capture()->input('gia');
        $itemPerPage = Request::capture()->input('row', 24);

        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');

        $where = [
            'status' => Tour::STATUS_ACTIVE,
        ];
        if($qtuyenTour) {
            $where['tuyen_tour.alias'] = $qtuyenTour;
        }
        else {
            if(isset($isGioChot)) {
                $tomorrow = Carbon::tomorrow();
                $timeStart = $tomorrow->format('d/m/Y');
                $timeEnd = $tomorrow->addDays(Tour::RANGE_TIME)->format('d/m/Y');
                $where['ngay_khoi_hanh'] = [
                    '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    '$lt' => Helper::getMongoDate($timeEnd, '/', false),
                ];
            }elseif(isset($isSale)) {
                $where['$expr'] = [
                    '$lt' => ['$gia_nguoi_lon','$gia_niem_yet']
                ];
            }else {
                $lsChild = Location::where('parent_id', $obj['_id'])->get()->pluck('alias')->toArray();
                if($lsChild) {
                    $lsChild[] = $obj['alias'];
                    $where['dia_diem_den.alias'] = ['$in' => $lsChild];
                }else {
                    $where['dia_diem_den.alias'] = $obj['alias'];
                }
            }
        }
        if($qdiaDiemDen) {
            $diaDiemDen = Location::getByAlias($qdiaDiemDen);
            if($diaDiemDen) {
                $lsChild = Location::where('parent_id', $diaDiemDen['_id'])->get()->pluck('alias')->toArray();
                if($lsChild) {
                    $lsChild[] = $qdiaDiemDen;
                    $where['dia_diem_den.alias'] = ['$in' => $lsChild];
                }else {
                    $where['dia_diem_den.alias'] = $qdiaDiemDen;
                }
            }
        }


        $lsObj = Tour::where($where)
            ->select(Tour::$basicFiledsForList);
        if($qgia) {
            $money = Helper::processRangeMoney($qgia);
            $tpl['qMoney'] = $money;
            $lsObj = $lsObj->where([
                '$or' => [
                    [
                        'gia_nguoi_lon' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_em' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_nho' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                ]
            ]);
        }
        if($qtimeBetween) {
            $time = Helper::processRangeDate($qtimeBetween);
            if (isset($time['time_start'])) {
                $lsObj = $lsObj->where([
                    '$or' => [
                        [
                            'ngay_khoi_hanh' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ],
                        [
                            'ngay_ket_thuc' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ]
                    ]
                ]);
            }
        }
        $lsObj = Pager::getInstance()->getPager($lsObj, $itemPerPage, $curPage);
        $groupIdTourNhieuLichKhoiHanh = [];
        foreach ($lsObj as $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = $t['_id'];
            }
        }
        //$groupIdTourNhieuLichKhoiHanh = array_column($lsObj->items(), '_id');
        if(!empty($groupIdTourNhieuLichKhoiHanh)) {
            $groupTourNhieuLichKhoiHanh = TourKhoiHanh::whereStatus(TourKhoiHanh::STATUS_ACTIVE)
                ->where([
                    'ngay_khoi_hanh' => [
                        '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    ],
                ])->
                whereIn('parent_id', $groupIdTourNhieuLichKhoiHanh)->select('ngay_khoi_hanh','gia_nguoi_lon', 'parent_id', 'status')->get()
                ->groupBy('parent_id')->toArray();
            // dd($groupIdTourNhieuLichKhoiHanh,$groupTourNhieuLichKhoiHanh);
            foreach($groupTourNhieuLichKhoiHanh as $key => $tour){
                $gia_min = $tour[0]['gia_nguoi_lon'];
                foreach($tour as $t) {
                    $gia_min = ($t['gia_nguoi_lon'] > $gia_min) ? $gia_min : $t['gia_nguoi_lon'];
                }
                $groupTourNhieuLichKhoiHanh[$key]['gia_nguoi_lon_min'] = $gia_min;
            }
            foreach ($lsObj as $_id => $t) {
                if(@$t['tour_hang_tuan'] == Tour::TOURLE && !isset($groupTourNhieuLichKhoiHanh[$t['_id']])) {
                    //dump($lsTourGroupByDanhMuc[$_alias][$key]);
                    unset($lsObj[$_id]);
                }
            }


            $tpl['groupTourNhieuLichKhoiHanh'] = $groupTourNhieuLichKhoiHanh;
        }
        $tpl['q'] = \request();
        $tpl['obj'] = $obj;
        $tpl['lsObj'] = $lsObj;
        $this->seo($obj);
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'place', $tpl);
    }

    function combo($alias) {
        $tpl = [];
        $obj = Combo::getByAlias($alias);
        if (!$obj) {
            return eView::getInstance()->setView404();
        }
        if (!isset($obj['status']) || $obj['status'] !== Combo::STATUS_ACTIVE) {
            return redirect('/');
        }

        HtmlHelper::getInstance()->setTitle($obj['name']);
        $curPage = (int)request('page', 1);
        $qtuyenTour = Request::capture()->input('tuyenTour');
        $qdiaDiemDen = Request::capture()->input('diaDiemDen');
        $qtimeBetween = Request::capture()->input('thoiGian');
        $qgia = Request::capture()->input('gia');
        $itemPerPage = Request::capture()->input('row', 24);

        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');

        $where = [
            'status' => Tour::STATUS_ACTIVE,
        ];
        if($qtuyenTour) {
            $where['tuyen_tour.alias'] = $qtuyenTour;
        }else {
            if(isset($isGioChot)) {
                $tomorrow = Carbon::tomorrow();
                $timeStart = $tomorrow->format('d/m/Y');
                $timeEnd = $tomorrow->addDays(Tour::RANGE_TIME)->format('d/m/Y');
                $where['ngay_khoi_hanh'] = [
                    '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    '$lt' => Helper::getMongoDate($timeEnd, '/', false),
                ];
            }elseif(isset($isSale)) {
                $where['$expr'] = [
                    '$lt' => ['$gia_nguoi_lon','$gia_niem_yet']
                ];
            }else {
                $where['combo'] = ['$in' => [$obj['_id']]];
            }
        }

        if($qdiaDiemDen) {
            $diaDiemDen = Location::getByAlias($qdiaDiemDen);
            if($diaDiemDen) {
                $lsChild = Location::where('parent_id', $diaDiemDen['_id'])->get()->pluck('alias')->toArray();
                if($lsChild) {
                    $lsChild[] = $qdiaDiemDen;
                    $where['dia_diem_den.alias'] = ['$in' => $lsChild];
                }else {
                    $where['dia_diem_den.alias'] = $qdiaDiemDen;
                }
            }
        }


        $lsObj = Tour::where($where)
            ->select(Tour::$basicFiledsForList);
        if($qgia) {
            $money = Helper::processRangeMoney($qgia);
            $tpl['qMoney'] = $money;
            $lsObj = $lsObj->where([
                '$or' => [
                    [
                        'gia_nguoi_lon' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_em' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                    [
                        'gia_tre_nho' => [
                            '$gte' => $money['money_start'],
                            '$lt' => $money['money_end'],
                        ],
                    ],
                ]
            ]);
        }
        if($qtimeBetween) {
            $time = Helper::processRangeDate($qtimeBetween);
            if (isset($time['time_start'])) {
                $lsObj = $lsObj->where([
                    '$or' => [
                        [
                            'ngay_khoi_hanh' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ],
                        [
                            'ngay_ket_thuc' => [
                                '$gte' => $time['time_start'],
                                '$lt' => $time['time_end'],
                            ],
                        ]
                    ]
                ]);
            }
        }
        $lsObj = Pager::getInstance()->getPager($lsObj, $itemPerPage, $curPage);
        $groupIdTourNhieuLichKhoiHanh = [];
        foreach ($lsObj as $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = $t['_id'];
            }
        }
        //$groupIdTourNhieuLichKhoiHanh = array_column($lsObj->items(), '_id');
        if(!empty($groupIdTourNhieuLichKhoiHanh)) {
            $groupTourNhieuLichKhoiHanh = TourKhoiHanh::whereStatus(TourKhoiHanh::STATUS_ACTIVE)
                ->where([
                    'ngay_khoi_hanh' => [
                        '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    ],
                ])->
                whereIn('parent_id', $groupIdTourNhieuLichKhoiHanh)->select('ngay_khoi_hanh','gia_nguoi_lon', 'parent_id', 'status')->get()
                ->groupBy('parent_id')->toArray();
            // dd($groupIdTourNhieuLichKhoiHanh,$groupTourNhieuLichKhoiHanh);
            foreach($groupTourNhieuLichKhoiHanh as $key => $tour){
                $gia_min = $tour[0]['gia_nguoi_lon'];
                foreach($tour as $t) {
                    $gia_min = ($t['gia_nguoi_lon'] > $gia_min) ? $gia_min : $t['gia_nguoi_lon'];
                }
                $groupTourNhieuLichKhoiHanh[$key]['gia_nguoi_lon_min'] = $gia_min;
            }
            foreach ($lsObj as $_id => $t) {
                if(@$t['tour_hang_tuan'] == Tour::TOURLE && !isset($groupTourNhieuLichKhoiHanh[$t['_id']])) {
                    //dump($lsTourGroupByDanhMuc[$_alias][$key]);
                    unset($lsObj[$_id]);
                }
            }


            $tpl['groupTourNhieuLichKhoiHanh'] = $groupTourNhieuLichKhoiHanh;
        }

        $tpl['q'] = \request();
        $tpl['obj'] = $obj;
        $tpl['lsObj'] = $lsObj;
        $this->seo($obj);
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'place', $tpl);
    }
}