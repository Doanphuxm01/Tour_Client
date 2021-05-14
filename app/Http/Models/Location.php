<?php

namespace App\Http\Models;

class Location extends BaseModel
{
    //
    const table_name = 'io_locations';
    protected $table = self::table_name;
    protected $fillable = [];
    static $unguarded = TRUE;

    const TYPE_TINH = 'tinh';
    const TYPE_THANH_PHO = 'thanh-pho';
    const TYPE_DISTRICT = 'huyen';
    const TYPE_DISTRICT_2 = 'quan';
    const TYPE_TOWN = 'xa';
    const TYPE_TOWN_2 = 'phuong';

    static function getListStatus($selected = FALSE, $return = false)
    {
        $listStatus = [
            self::STATUS_ACTIVE => ['id' => self::STATUS_ACTIVE, 'style' => 'success', 'text' => 'Hiển thị', 'text-action' => 'Hiển thị'],
            self::STATUS_INACTIVE => ['id' => self::STATUS_INACTIVE, 'style' => 'secondary', 'text' => 'Không hiển thị', 'text-action' => 'Không hiển thị'],
        ];
        if ($selected && isset($listStatus[$selected])) {
            $listStatus[$selected]['checked'] = 'checked';
            if($return) {
                return $listStatus[$selected];
            }
        }

        return $listStatus;
    }

    static function getAllCity()
    {
        $where = [
            'parent_code' => '0',
        ];

        return self::where($where)->get();
    }


    static function getAllLocationByParent($city_id)
    {
        $where = [
            'parent_code' => $city_id,
        ];

        return self::where($where)->get();
    }

    static function getLocationById($id)
    {
        $where = [
            'slug' => $id,
        ];

        return self::where($where)->get();
    }

    static function getBySlug($slug, $type = '')
    {
        $where = [
            'slug' => $slug,
        ];
        /*if (!empty($type)) {
            if ($type == self::TYPE_TINH) {
                $where['type'] = ['$in' => [self::TYPE_TINH, self::TYPE_THANH_PHO]];
            } else if ($type == self::TYPE_DISTRICT) {
                $where['type'] = ['$in' => [self::TYPE_DISTRICT, self::TYPE_DISTRICT_2]];
            } else {
                $where['type'] = ['$in' => [self::TYPE_TOWN, self::TYPE_TOWN_2]];
            }
        }*/

        return self::where($where)->first();
    }

    static function getProvinceInput(&$obj, &$tpl)
    {
        if (isset($obj['locations']['province']['key']) && $obj['locations']['province']['key']) {
            $cityOfCustomer = Location::getBySlug($obj['locations']['province']['key']);
            $tpl['cityOfCustomer'] = $cityOfCustomer;
            if ($cityOfCustomer) {
                $districtOfCity = Location::getAllLocationByParent($cityOfCustomer->code);
                $tpl['districtOfCity'] = $districtOfCity;
                if (isset($obj['locations']['district']['key']) && $obj['locations']['district']['key']) {
                    $districtOfCustomer = Location::getBySlug($obj['locations']['district']['key']);
                    $tpl['districtOfCustomer'] = $districtOfCustomer;
                    if ($districtOfCustomer) {
                        $townOfDistrict = Location::getAllLocationByParent($districtOfCustomer->code);
                        $tpl['townOfDistrict'] = $townOfDistrict;
                    }
                }

            }
        }

    }

    /**
     * @param $obj là $_POST['obj'] gọi lúc save
     * $savePost['locations'] = (object)getLocationFromInput;//tỉnh thành
     */
    static function getLocationFromInput($obj)
    {
        $locations = [];
        if (isset($obj['locations'])) {
            if (isset($obj['locations']['city'])) {
                $_city = Location::getBySlug($obj['locations']['city']);
                $city = [
                    'key' => $_city['slug'],
                    'value' => $_city['name'],
                ];
                $locations['province'] = (object)$city;
            }
            if (isset($obj['locations']['district'])) {
                $_district = Location::getBySlug($obj['locations']['district']);
                $district = [
                    'key' => $_district['slug'],
                    'value' => $_district['name'],
                ];
                $locations['district'] = (object)$district;
            }
            if (isset($obj['locations']['town'])) {
                $_town = Location::getBySlug($obj['locations']['town']);
                $town = [
                    'key' => $_town['slug'],
                    'value' => $_town['name'],
                ];
                $locations['town'] = (object)$town;
            }

        }
        return $locations;
    }
}
