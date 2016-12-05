<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App;
use Config;

class Locale {

    /**
     * 執行請求過濾器。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $defaultLang = Config::get('app.locale');
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $defaultLang = str_replace('-', '_', strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ','));
        }
        
        $locale = strtolower(Route::input('locale', $defaultLang));
        $configLocale = Config::get('locale', []);
        if(!isset($configLocale[$locale])){
            $locale = 'en';
        }
        App::setLocale($locale);

        return $next($request);
    }

}
