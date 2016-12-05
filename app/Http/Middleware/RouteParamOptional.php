<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App;
use Config;

class RouteParamOptional {

    /**
     * 執行請求過濾器。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $optionalParam = Route::input('optional');
        if (!is_null($optionalParam)) {
            $paramSeg = array_filter(explode('/', $optionalParam));
            $tmpArr = [];
            foreach ($paramSeg as $k => $v) {
                $pair = explode('-', $v, 2);
                if (count($pair) == 2) {
                    $tmpArr[$pair[0]] = $pair[1];
                }
            }
            Route::current()->setParameter('optional', $tmpArr);
        }

        return $next($request);
    }

}
