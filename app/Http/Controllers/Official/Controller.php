<?php

namespace App\Http\Controllers\Official;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
//
use App;
use Request;
use Route;
use Config;
use Sitemap;
use User;
use DB;
use DBOperator;
use DBProcedure;
use App\Classes\Pagination\Pagination;
use Session;

abstract class Controller extends BaseController {

    use DispatchesJobs,
        ValidatesRequests,
        Pagination;

    /**
     * array, for ajax json.
     * null, view file not found
     *  
     * @var array|\Illuminate\View\View|null
     */
    protected $view;
    protected $view_folder = '';

    public function __construct() {
        $currAction = Route::currentRouteAction();
        $preStr = 'App\\Http\\Controllers\\';
        $pos = strpos($currAction, $preStr);
        if ($pos !== false) {
            $currAction = substr($currAction, strpos($currAction, '\\Contollers') + strlen($preStr));
        }
        $viewPath = str_replace(['\\', 'Controller@'], '.', $currAction);
        $tmpPos = strrpos($viewPath, '.');
        if ($tmpPos !== false) {
            $this->view_folder = substr($viewPath, 0, $tmpPos);
        }

        if (Request::ajax()) {
            $this->view = [
                'result' => 'ok',
                'msg' => '',
                'detail' => [],
                'data' => []
            ];
        } else {
            if (view()->exists($viewPath)) {
                $this->view = view($viewPath);

                //取得未讀訊息數量
                if(User::id() != null)
                {
                    $message_count = DB::table('member_notice_log')
                            ->where('member_data_id',User::id())
                            ->where('is_read','0')
                            ->count();
                    $this->view->with('message_count', $message_count);
                }
                else
                {
                    $this->view->with('message_count', '0');
                }

                if(!Session::has('bts_template'))
                {
                    $bts_template = array();
                }
                $this->setViewDefault();
            }
        }
    }

    public function writeDatabaseLog($data) {
        $ip = Request::ip();
        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }

        $dataDefault = [
            'created_at' => date('Y/m/d H:i:s'),
            'member_group' => User::group(),
            'member_id' => User::id(),
            'ip' => $ip,
            'page' => Sitemap::node()->getPath(),
            'operator' => DBOperator::OP_UNDEFINED,
            'table' => null,
            'data_id' => null,
            'data_before' => null,
            'data_after' => null,
        ];
        $data = array_merge($dataDefault, $data);
        if (!is_string($data['data_before'])) {
            $data['data_before'] = json_encode($data['data_before']);
        }
        if (!is_string($data['data_after'])) {
            $data['data_after'] = json_encode($data['data_after']);
        }

        try {
            $lastId = DB::table('syslog')->insertGetId($data);
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage(), $data);
        }
    }

    public function setViewDefault() {
        $locale = App::getLocale();
        $routeNameCurr = Route::currentRouteName();
        $nodeCurr = Sitemap::node();
        $root = $nodeCurr->getRoot();

        $urlBase = rtrim(url('/'), '/');
        $urlBasePath = trim(Request::getBasePath(), '/') . '_' . $root->getKey();
        $routeName = str_replace(".", "_", $nodeCurr->getPath());
        $urlCurr = (Sitemap::isPathHasParam($routeNameCurr) ?
                        $nodeCurr->getUrlHasParam(Route::current()->parameters(), false) :
                        $nodeCurr->getUrl(Route::current()->parameters(), false)) . rtrim("?" . Request::getQueryString(), "?");
        $urlLast = Sitemap::getUrl('official');
        $urlBack = $urlLast;
        $pageTitle = '';


        if (isset($_COOKIE[$urlBasePath . '_urlLast'])) {
            $urlLast = $urlBase . '/' . ltrim($_COOKIE[$urlBasePath . '_urlLast'], '/');
        }
        if (isset($_COOKIE[$urlBasePath . '_urlBack_' . $routeName])) {
            $urlBack = $urlBase . '/' . ltrim($_COOKIE[$urlBasePath . '_urlBack_' . $routeName], '/');
        } else {
            if (isset($_COOKIE[$urlBasePath . '_urlBackRouteName'])) {
                if (isset($_COOKIE[$urlBasePath . '_urlBack_' . $_COOKIE[$urlBasePath . '_urlBackRouteName']])) {
                    setcookie($urlBasePath . '_urlBack_' . $_COOKIE[$urlBasePath . '_urlBackRouteName'], '', time() - 3600, '/');
                }
                setcookie($urlBasePath . '_urlBackRouteName', '', time() - 3600, '/');
            }

            $nodeParentUrl = $nodeCurr->getParents(null, ['route' => function($k) {
                    return !is_null($k);
                }]);
            if (count($nodeParentUrl) > 0) {
                $urlBack = last($nodeParentUrl)->getUrl();
            }
        }

        $nodeAllPath = $nodeCurr->getPathNodes();
        $nodeAllPathRev = array_reverse($nodeAllPath);

        //title
        foreach ($nodeAllPathRev as $k => $v) {
            if ($v->getName() != $v->getLocalePath()) {
                $pageTitle = $v->getName();
                break;
            }
        }

        $this->view->with('_urlBase', $urlBase);
        $this->view->with('_urlBasePath', $urlBasePath);
        $this->view->with('_urlCurr', $urlCurr);
        $this->view->with('_urlLast', $urlLast);
        $this->view->with('_urlBack', $urlBack);
        $this->view->with('_routeName', $routeName);
        $this->view->with('_pageTitle', $pageTitle);
    }

}
