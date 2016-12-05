<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Sitemap;
use Cache;
use User;
use App;
use Request;
use Route;

class MasterBackendComposer {

    public function __construct() {
        
    }

    public function compose(View $view) {
        $locale = App::getLocale();
        $nodeCurr = Sitemap::node();
        $nodeAllSys = Sitemap::node('backend')->getChildren(null, ['menu' => true]);
        $nodeAllPath = $nodeCurr->getPathNodes();
        $nodeCurrSys = isset($nodeAllPath[1]) ? $nodeAllPath[1] : null;
        $permission = User::get('permission', []);

        //menu top
        $tmpCacheKey = $locale . '_menu_top';
        $pageMenuTop = User::cacheGet($tmpCacheKey);
        if (is_null($pageMenuTop)) {
            $tmpView = view('backend.elements._build_menu_top');
            $tmpView->with('nodes', $nodeAllSys)
                    ->with('permission', $permission);
            $pageMenuTop = $tmpView->render();

            User::cacheSet($tmpCacheKey, $pageMenuTop);
        }

        //menu left
        $pageMenuLeft = '';
        if (!is_null($nodeCurrSys)) {
            $tmpCacheKey = $locale . '_menu_left_' . $nodeCurrSys->getKey();
            $pageMenuLeft = User::cacheGet($tmpCacheKey);
            if (is_null($pageMenuLeft)) {
                $tmpView = view('backend.elements._build_menu_left');
                $tmpView->with('node', $nodeCurrSys)
                        ->with('pos', 'root')
                        ->with('permission', $permission);
                $pageMenuLeft = $tmpView->render();

                User::cacheSet($tmpCacheKey, $pageMenuLeft);
            }
        }

        //navi path        
        $tmpView = view('backend.elements._build_navi_path');
        $tmpView->with('nodeAllPath', $nodeAllPath)
                ->with('nodeCurr', $nodeCurr);
        $pageNaviPath = $tmpView->render();

        // current menu
        $currMenuClass = [];
        if (count($nodeAllPath) > 1) {
            $nodeAllPathTmp = array_slice(array_values($nodeAllPath), 1);
            foreach ($nodeAllPathTmp as $k => $v) {
                $currMenuClass[] = 'menuitem_' . str_replace('.', '_', $v->getPath());
            }
        }

        // is search
        $isSearch = false;
        $input = Request::all();
        if (isset($input['submit_search']) && $input['submit_search'] == '1') {
            foreach ($input as $k => $v) {
                if (starts_with($k, 'q_') && trim($v) != "") {
                    $isSearch = true;
                    break;
                }
            }
        }

        $view->with('_pageMenuTop', $pageMenuTop);
        $view->with('_pageMenuLeft', $pageMenuLeft);
        $view->with('_pageNaviPath', $pageNaviPath);
        $view->with('_currMenuClassJson', json_encode($currMenuClass));
        $view->with('_isSearch', $isSearch);
    }

}
