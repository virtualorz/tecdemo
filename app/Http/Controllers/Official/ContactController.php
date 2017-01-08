<?php

namespace App\Http\Controllers\Official;

//
use User;
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Validator;
use Log;
use Sitemap;
use SitemapAccess;

class ContactController extends Controller {

    public function index() {
        
        $listResult = DB::table('system_tc_data')
                            ->select('name','content')
                            ->where('enable','1')
                            ->orderBy('id','desc')
                            ->get();
        foreach($listResult as $k=>$v)
        {
            $listResult[$k]['content'] = json_decode($listResult[$k]['content'], true);
        }

        $this->view->with('listResult', $listResult);

        return $this->view;
    }
}
