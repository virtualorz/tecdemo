<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App;
use Config;

class LocaleComposer {

    public function __construct() {
        
    }

    public function compose(View $view) {
        $view->with('_appLocale', App::getLocale());
        $view->with('_configLocale', Config::get('locale', []));
    }

}
