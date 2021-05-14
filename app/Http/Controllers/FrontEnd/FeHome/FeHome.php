<?php

namespace App\Http\Controllers\FrontEnd\FeHome;

use App\Elibs\Pager;
use App\Http\Controllers\FrontEndController;
use App\Http\Models\Cate;
use App\Http\Models\Combo;
use App\Http\Models\ConfigWebsite;
use App\Http\Models\Location;
use App\Http\Models\TourCategory;
use App\Http\Models\TourKhoiHanh;
use App\Http\Models\Videos;
use App\Http\Models\Post;
use App\Http\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Elibs\eView;
use App\Elibs\HtmlHelper;
use App\Elibs\Helper;
use App\Http\Models\Product;
use App\Http\Models\FeedBack;
use App\Http\Models\BaseModel;
use Jenssegers\Agent\Agent;

use SEOMeta;
use OpenGraph;
use Twitter;
use function Clue\StreamFilter\fun;

class FeHome extends FrontEndController
{
    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->home();
        }
    }

    public function home()
    {
        HtmlHelper::getInstance()->setTitle('Sự lựa chọn tin cậy | Vietrantour');
        $tpl = [];
        $groupIdTourNhieuLichKhoiHanh = [];
        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');
        $timeEnd = $tomorrow->addDays(Tour::RANGE_TIME)->format('d/m/Y');
        $where = [
            'status' => BaseModel::STATUS_ACTIVE,
            'ngay_khoi_hanh' => [
                '$gte' => Helper::getMongoDate($timeStart, '/', true),
                '$lt' => Helper::getMongoDate($timeEnd, '/', false),
            ],
        ];

        $select = Tour::$basicFiledsForList;

        // tìm kiếm sản phẩm
        $q = trim(Request::capture()->input('q'));
        $tpl['q'] = $q;
        // @todo tạm thời đóng tour giờ chót
        /*$lsToursGioChot = Tour::getAll($where, $select, '_id', 8);
        foreach ($lsToursGioChot as $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = $t['_id'];
            }
        }
        $tpl['lsToursGioChot'] = $lsToursGioChot;*/

        // @todo tạm thời đóng tour khuyến mãi
        /*unset($where['$or'][0]['ngay_khoi_hanh']['$lt']);
        $where['$or'][] = [
            'tour_hang_tuan' => [ '$in' => [Tour::TOURHANGNGAY, Tour::TOURHANGTUAN]],
        ];
        $where['$expr'] = [
            '$gt' => ['$gia_niem_yet', '$gia_nguoi_lon']
        ];
        $lsToursKhuyenMai = Tour::getAll($where, $select, '_id', 8);
        $tpl['lsToursKhuyenMai'] = $lsToursKhuyenMai;

        foreach ($lsToursKhuyenMai as $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = $t['_id'];
            }
        }*/
        $lsVideos = Videos::getAll([], Videos::$basicFiledsForList, '_id', 8);
        if($lsVideos) {
            $tpl['lsVideos'] = $lsVideos;
        }
        $agent = new Agent();
        $aliasCateSieuThiTienIch = 'sieu-thi-tien-ich';
        $tpl['aliasCateSieuThiTienIch'] = $aliasCateSieuThiTienIch;
        $lsSieuThiTienIch = Cate::getByParentId($aliasCateSieuThiTienIch, 5);
        if(!empty($lsSieuThiTienIch)) {
            $tpl['lsSieuThiTienIch'] = $lsSieuThiTienIch;
        }
        $tintucvtt = Post::getPostByCate('tin-tuc-vietrantour', 6);
        if(!$tintucvtt->isEmpty()) {
            $tpl['tintucvtt'] = $tintucvtt;
        }
        $camnangdulich = Post::getPostByCate('cam-nang-du-lich', 6);
        if(!$camnangdulich->isEmpty()) {
            $tpl['camnangdulich'] = $camnangdulich;
        }
        //desktop
            if(!empty($lsSieuThiTienIch)) {
                $arrayLsSieuThiTienIch = array_column($lsSieuThiTienIch, '_id');
                foreach ($arrayLsSieuThiTienIch as $key  => $item) {
                    $arrayLsSieuThiTienIch[$key] = Helper::getMongoId($item);
                }
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                    '_id' => [
                        '$nin' => $arrayLsSieuThiTienIch
                    ]
                ];
            }else {
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                ];
            }
            if(!$tintucvtt->isEmpty()) {
                $arraytintucvtt = array_column($tintucvtt->toArray(), '_id');
                foreach ($arraytintucvtt as $key  => $item) {
                    $arraytintucvtt[$key] = Helper::getMongoId($item);
                }
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                    '_id' => [
                        '$nin' => $arraytintucvtt
                    ]
                ];
            }else {
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                ];
            }
            if(!$camnangdulich->isEmpty()) {
                $arraycamnangdulich = array_column($camnangdulich->toArray(), '_id');
                foreach ($arraycamnangdulich as $key  => $item) {
                    $arraycamnangdulich[$key] = Helper::getMongoId($item);
                }
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                    '_id' => [
                        '$nin' => $arraycamnangdulich
                    ]
                ];
            }else {
                $whereNews = [
                    'status' => Post::STATUS_ACTIVE,
                ];
            }
            $whereRelated = [];
            $whereRelated['sort'] = [
                '$exists' => 'sort'
            ];
            $lsRelated = Post::where([
                'status' => Post::STATUS_ACTIVE,
            ])->where($whereRelated)->where('sort', '!=', null)->get()->toArray();
            usort($lsRelated, function($a, $b){return $a['sort'] > $b['sort'];});
            $tpl['lsNewsFeed'] = $lsRelated;
        $configHome = ConfigWebsite::where('type', ConfigWebsite::HOMEPAGE)->first();
        //mobile
            if(isset($configHome['danh_muc_tour']) && !empty($configHome['danh_muc_tour'])) {
                unset($where['ngay_khoi_hanh']);
                unset($where['$expr']);
                $danhMucHienThiTrangChu = array_column($configHome['danh_muc_tour'], 'alias'); // Long lơ xuất hiện ở trang chủ
                $where['tuyen_tour.alias'] = ['$in' => $danhMucHienThiTrangChu];
                $Model = BaseModel::table(Tour::table_name);
                //dd($lsSkuDanhMucTour, $where);
                $arrayPush = [];
                foreach (Tour::$basicFiledsForList as $item) {
                    $arrayPush[$item] = '$'.$item;
                }
                $groupAndCountByTuyenTour = [
                    [
                        '$unwind' => '$tuyen_tour',
                    ],
                    [
                        '$sort' => ['sort' => -1]
                    ],
                    [
                        '$group' => [
                            '_id' => '$tuyen_tour.alias',
                            'value' => ['$push' => $arrayPush],
                            'count' => ['$sum' => 1]
                        ]
                    ],
                    [
                        '$project' => [
                            'value' => ['$slice' => ['$value', 8]],
                            'preview' => 1,
                            'submitted' => 1
                        ]
                    ]
                ];
    
                /*$where['$or'] = [
                    [
                        'tour_hang_tuan' => ['$in' => [Tour::TOURHANGNGAY, Tour::TOURHANGTUAN]]
                    ],
                    [
                        'tour_hang_tuan' => Tour::TOURLE,
                        'ngay_khoi_hanh' => [
                            '$gte' => Helper::getMongoDate($timeStart, '/', true),
                            '$lt' => Helper::getMongoDate($timeEnd, '/', false),
                        ],
                    ]
                ];*/
                $aggregate = [
    
                    [
                        '$match' => $where
                    ],
                    [
                        '$lookup' => [
                            'from' => TourCategory::table_name,
                            'localField' => 'parent_id',
                            'foreignField' => '_id',
                            'as' => 'group_ngay_khoi_hanh'
                        ]
                    ],
                    [
                        '$facet' => [
                            'groupAndCountByTuyenTour' => $groupAndCountByTuyenTour,
                        ]
                    ],
    
                ];
    
    
    
                $lsTourByDanhMuc = $Model->raw(function ($collection) use ($aggregate) {
                    return $collection->aggregate($aggregate);
                })->toArray();
                if($lsTourByDanhMuc) {
                    $re = $lsTourByDanhMuc;
                    $lsTourGroupByDanhMuc = [];
                    $re = Helper::BsonDocumentToArray($re);
                    //dd($danhMucHienThiTrangChu, $re[0]['groupAndCountByTuyenTour']);
                    $lsKeySort = [];
                    foreach ($re[0]['groupAndCountByTuyenTour'] as $key => $val) {
                        if(in_array($val['_id'], $danhMucHienThiTrangChu)) {
                            $lsKeySort[] = $val['_id'];
                            $re[0]['groupAndCountByTuyenTour'][$val['_id']] = $val;
                            unset($re[0]['groupAndCountByTuyenTour'][$key]);
                        }
                    }
    
                    $lsTourCategoryByKeySort = TourCategory::whereIn('alias', $lsKeySort)->select('alias', 'status', '_id', 'sort')
                        ->orderBy('sort', 'DESC')->get()->keyBy('alias')->toArray();
                    //dd($lsTourCategoryByKeySort, $lsKeySort);
                    foreach ($lsTourCategoryByKeySort as $key => $val) {
                        $lsTourGroupByDanhMuc[$key] = $re[0]['groupAndCountByTuyenTour'][$key]['value'];
                    }
                    foreach ($lsTourGroupByDanhMuc as $_alias => $danhmuc) {
                        if ($danhmuc) {
                            foreach ($danhmuc as $_id => $t) {
                                $lsTourGroupByDanhMuc[$_alias][$_id]['_id'] = (string)$t['_id'];
                                if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                                    $groupIdTourNhieuLichKhoiHanh[] = (string)$t['_id'];
                                    // dd($groupIdTourNhieuLichKhoiHanh);
                                }
                            }
                        }
                    }
                    $tpl['lsTourByDanhMuc'] = $lsTourGroupByDanhMuc;
    
                }
                if(!empty($groupIdTourNhieuLichKhoiHanh)) {
                    $groupTourNhieuLichKhoiHanh = TourKhoiHanh::whereStatus(TourKhoiHanh::STATUS_ACTIVE)
                        ->whereIn('parent_id', $groupIdTourNhieuLichKhoiHanh)
                        ->where([
                            'ngay_khoi_hanh' => [
                                '$gte' => Helper::getMongoDate($timeStart, '/', true),
                                '$lt' => Helper::getMongoDate($timeEnd, '/', false),
                            ],
                        ])
                        ->orderBy('ngay_khoi_hanh', 'ASC')
                        ->select('ngay_khoi_hanh', 'parent_id', 'status')->get()
                        ->groupBy('parent_id')->toArray();
                    foreach ($lsTourGroupByDanhMuc as $_alias => $danhmuc) {
                        if ($danhmuc) {
                            foreach ($danhmuc as $key => $t) {
                                $t['_id'] = (string)$t['_id'];
                                if(@$t['tour_hang_tuan'] == Tour::TOURLE && !isset($groupTourNhieuLichKhoiHanh[$t['_id']])) {
                                    //dump($lsTourGroupByDanhMuc[$_alias][$key]);
                                    unset($lsTourGroupByDanhMuc[$_alias][$key]);
                                }
                            }
                        }
                    }
                    //dd($groupTourNhieuLichKhoiHanh);
                    $tpl['groupTourNhieuLichKhoiHanh'] = $groupTourNhieuLichKhoiHanh;
                }
            }
        $tpl['configHome'] = $configHome;
        $lsFeedback = FeedBack::getAll([], ['_id', 'name', 'chuc_danh', 'status', 'avatar', 'content']);
        $tpl['lsFeedback'] = $lsFeedback;
        $tpl['lsCombo'] = Combo::getAll();

        return eView::getInstance()->setViewFrontEnd(__DIR__, 'home', $tpl);
    }

    public function search(){
        HtmlHelper::getInstance()->setTitle('Kết quả tìm kiếm | Vietrantour');
        $tpl = [];
        $q = Request::capture()->input('q', []);
        $dia_diem = Request::capture()->input('diaDiemDen', '');
        $thoi_gian = Request::capture()->input('thoiGian', '');
        $data_dia_diem = Location::where('alias',$dia_diem)->first();
        $tpl['q'] = $q;
        $tomorrow = Carbon::tomorrow();
        $timeStart = $tomorrow->format('d/m/Y');
        $where = [
            'status' => BaseModel::STATUS_ACTIVE,
        ];
        if (!empty($dia_diem)) {
            $where['dia_diem_den.alias'] = $data_dia_diem['alias'];
        }
        if (!empty($thoi_gian)) {
            $data_thoi_gian = explode ('-',$thoi_gian);
            $date_to = Helper::getMongoDate(@$data_thoi_gian[0],'-', true);
            $date_form = Helper::getMongoDate(@$data_thoi_gian[1],'-', false);
            $where['ngay_khoi_hanh'] = [
                '$gte' => $date_to,
                '$lte' => $date_form,
            ];
        }
        $select = Tour::$basicFiledsForList;
        $listObj = Tour::select($select)->where($where)->orderBy('_id', 'DESC');
        if ($q) {
            $listObj = $listObj->where('name', 'LIKE', '%' .$q. '%');
        }
        $itemPerPage = Request::capture()->input('row', 24);
        $listObj = Pager::getInstance()->getPager($listObj, $itemPerPage, 'all');
        foreach ($listObj as $_id => $t) {
            if(@$t['tour_hang_tuan'] == Tour::TOURLE) {
                $groupIdTourNhieuLichKhoiHanh[] = (string)$t['_id'];
                // dd($groupIdTourNhieuLichKhoiHanh);
            }
        }
        if(!empty($groupIdTourNhieuLichKhoiHanh)) {
            $groupTourNhieuLichKhoiHanh = TourKhoiHanh::whereStatus(TourKhoiHanh::STATUS_ACTIVE)
                ->whereIn('parent_id', $groupIdTourNhieuLichKhoiHanh)
                ->where([
                    'ngay_khoi_hanh' => [
                        '$gte' => Helper::getMongoDate($timeStart, '/', true),
                    ],
                ])
                ->orderBy('ngay_khoi_hanh', 'ASC')
                ->select('ngay_khoi_hanh', 'parent_id', 'status')->get()
                ->groupBy('parent_id')->toArray();
            //dd($groupIdTourNhieuLichKhoiHanh, $groupTourNhieuLichKhoiHanh);

            foreach ($listObj as $_id => $t) {
                $t['_id'] = (string)$t['_id'];
                if(@$t['tour_hang_tuan'] == Tour::TOURLE && !isset($groupTourNhieuLichKhoiHanh[$t['_id']])) {
                    unset($listObj[$_id]);
                }
            }
            //dd($groupTourNhieuLichKhoiHanh);
            $tpl['groupTourNhieuLichKhoiHanh'] = $groupTourNhieuLichKhoiHanh;
        }

        $tpl['lsObj'] = $listObj;
        $q_khuyenmai = [
            'status' => BaseModel::STATUS_ACTIVE,
            '$expr' => [
                '$gt' => ['$gia_niem_yet', '$gia_nguoi_lon'],
            ]
        ];

        $lsToursKhuyenMai = Tour::getAll($q_khuyenmai, $select, '_id', 8);
        $tpl['lsToursKhuyenMai'] = $lsToursKhuyenMai;
        return eView::getInstance()->setViewFrontEnd(__DIR__, 'search', $tpl);
    }
}
