<?php

namespace App\Http\Middleware;

use Closure;
use Sitemap;
use SitemapAccess;
use Request;
use Route;
use User;
use Config;

class Permission {

    /**
     * 執行請求過濾器。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $node = Sitemap::node();
        $permissionNode = $node->getPermissionNode();
        $permission = $permissionNode->prop('permission', SitemapAccess::LOGIN_NOT_REQUIRED);
        $nodePath = $permissionNode->getPath();
        $root = $node->getRoot();

        if ($permission >= SitemapAccess::LOGIN_REQUIRED) { // check login
            if (User::isLogin() === false) {
                if (!$request->ajax()) {                    
                    $urlBasePath = trim(Request::getBasePath(), '/') . '_' . $root->getKey();
                    $routeNameCurr = Route::currentRouteName();
                    $urlCurr = (Sitemap::isPathHasParam($routeNameCurr) ?
                                    $node->getUrlHasParam(Route::current()->parameters(), false) :
                                    $node->getUrl(Route::current()->parameters(), false)) . rtrim("?" . Request::getQueryString(), "?");
                    setcookie($urlBasePath . '_urlLast', $urlCurr, 0, '/');
                    setcookie($urlBasePath . '_pageNotLogin', '1', 0, '/');

                    return redirect()->to(Sitemap::getUrl(Config::get('login.group.' . User::group() . '.login')));
                } else {
                    return [
                        'result' => 'login',
                        'msg' => trans('message.error.not_login'),
                        'detail' => [],
                        'data' => [],
                    ];
                }
            }
        }
        if ($permission >= SitemapAccess::ACCESS_REQUIRED) { // check access
            if (User::isAccess($nodePath) === false) {
                if (!$request->ajax()) {
                    $url = Sitemap::getUrl(Config::get('login.group.' . User::group() . '.login'));
                    if (!$root->isEmpty()) {
                        $url = $root->getUrl();
                    }
                    
                    return redirect()->to($url);
                } else {
                    return [
                        'result' => 'access',
                        'msg' => trans('message.error.not_access'),
                        'detail' => [],
                        'data' => [],
                    ];
                }
            }
        }
        return $next($request);
    }

}
