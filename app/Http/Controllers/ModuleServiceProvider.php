<?php


namespace App\Http\Controllers;


use App\Http\Models\MenuWebsite;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/FrontEnd', 'FE');
        $lsObj = MenuWebsite::where('status', MenuWebsite::STATUS_ACTIVE)->get()->groupBY('loai_menu.id')->toArray();
        $html = '';
        $i = 1;
        $tpl = [];
        foreach ($lsObj as $key => $obj) {
            $tpl[$key] = MenuWebsite::buildTree($lsObj[$key], 0);
            if($key == MenuWebsite::HEADER) {
                $html .= '<nav>
                        <ul>
                            '.$this->buildMenu($tpl[$key]).'
                        </ul>
                     </nav>';
                $i++;
            }
            if($key == MenuWebsite::FOOTER) {
                $html .= $this->buildMenuFooter($tpl[$key]);
                $i++;
            }
            $tpl[$key] = $html;
            $html = '';
        }
        return view()->share('VAR', $tpl);
    }

    public function register()
    {
        //
    }

    function buildMenu($menu_data)
    {
        $html = "";
        if (isset($menu_data)) {
            foreach ($menu_data as $item) {
                $id = (string)$item['_id'];
                if (empty($item['children'])) {
                    $html .= '<li><a href="'. public_link(@$item['relative_link']) .'"><img class="img-category" src="'. \App\Http\Models\Media::getImageSrc(@$item['avatar']['relative_link']).'" ><span class="span-category">'. $item['name'] .'</span></a></li>';
                }
                if (!empty($item['children'])) {
                    $html .= '<li>
							<a href="'. public_link(@$item['relative_link']) .'"><img class="img-category" src="'. \App\Http\Models\Media::getImageSrc(@$item['avatar']['relative_link']).'" ><span class="span-category">'. $item['name'] .'<span> <i
								class="fas fa-caret-down"></i></a> <!--second level -->
							<ul>';
                            $html .= $this->buildMenu($item['children']);
                    $html .= '</ul></li>';

                }
            }

        }
        return $html;
    }
    function buildMenuFooter($menu_data)
    {
        $html = "";
        if (isset($menu_data)) {
            foreach ($menu_data as $item) {
                $id = (string)$item['_id'];
                if (empty($item['children'])) {
                    $html .= '<li><span><i class="fas fa-tasks"></i></span><a href="'. public_link(@$item['relative_link']) .'">'. $item['name'] .'</a></li>';
                }
                if (!empty($item['children'])) {
                    $html .= '<div class="col-md col-12"><div class="footer-widget fl-wrap">
                  <h3 href="'. public_link(@$item['relative_link']) .'">'. $item['name'] .'</h3>
                  <div class="footer-contacts-widget fl-wrap">
                      <ul class="footer-contacts fl-wrap">';
                                        $html .= $this->buildMenuFooter($item['children']);
                                        $html .= '</ul>
                                </div>
                            </div></div>';

                }
            }

        }
        return $html;
    }}