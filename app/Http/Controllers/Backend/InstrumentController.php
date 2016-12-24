<?php

namespace App\Http\Controllers\Backend;

//
use DB;
use DBOperator;
use DBProcedure;
use User;
use Request;
use Route;
use Validator;
use Config;
use Sitemap;
use SitemapAccess;
use Log;
use FileUpload;
use Mail;

class InstrumentController extends Controller {

    public function index() {

        $type = Request::input('type', '');
        $site = Request::input('site', '');
        $name = Request::input('name', '');
        $section1 = Request::input('section1', '');
        $section2 = Request::input('section2', '');

        $listResult = DB::table('instrument_data');
        if($type != "")
        {
            $listResult->where('instrument_data.instrument_type_id','=',$type);
        }
        if($site != "")
        {
            $listResult->where('instrument_data.instrument_site_id','=',$site);
        }
        if($name != "")
        {
            $listResult->where('instrument_data.name','like','%'.$name.'%');
        }
        if($section1 != "" && $section2 == "")
        {
            $listResult->where('instrument_data.open_section','like','1_%');
        }
        else if($section1 == "" && $section2 != "")
        {
            $listResult->where('instrument_data.open_section','like','%_2');
        }
        else if($section1 != "" && $section2 != "")
        {
            $listResult->where('instrument_data.open_section','like','%_%');
        }

        $listResult = $listResult->select('instrument_data.id',
                                            DB::raw('DATE_FORMAT(instrument_data.created_at, "%Y-%m-%d") as created_at'),
                                            'instrument_type.name as type_name',
                                            'instrument_data.name',
                                            'instrument_data.open_section',
                                            'member_admin.name as created_admin_name')
                                    ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                                    ->leftJoin('member_admin','instrument_data.create_admin_id','=','member_admin.id')
                                    ->groupBy('instrument_data.id')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $siteResult = DB::table('instrument_site')
                                    ->select('id','name')
                                    ->where('enable',1)
                                    ->orderBy('id','desc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('typeResult', $typeResult);
        $this->view->with('siteResult', $siteResult);
        return $this->view;
    }

    public function add() {
        $typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $siteResult = DB::table('instrument_site')
                                    ->select('id','name')
                                    ->where('enable',1)
                                    ->orderBy('id','desc')
                                    ->get();
        $sectionResultTmp = DB::table('instrument_section')
                                    ->select('id','section_type','start_time','end_time')
                                    ->where('enable',1)
                                    ->orderBy('section_type','asc')
                                    ->get();
        $sectionResult = array();
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 1)
            {
                $tmp = array('1'=>$v,'2'=>'');
                array_push($sectionResult,$tmp);
            }
        }
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 2)
            {
                $isset = false;
                foreach($sectionResult as $k1=>$v1)
                {
                    if($v1['2']== '')
                    {
                        $isset = true;
                        $sectionResult[$k1]['2'] = $v;
                        break;
                    }
                }
                if(!$isset)
                {
                    $tmp = array('1'=>'','2'=>$v);
                    array_push($sectionResult,$tmp);
                }
            }
        }

        $this->view->with('typeResult', $typeResult);
        $this->view->with('siteResult', $siteResult);
        $this->view->with('sectionResult', $sectionResult);
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_data')
                            ->select('instrument_data.*','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_data.create_admin_id','=','member_admin.id')
                            ->where('instrument_data.id',$id)
                            ->get();
        //管理員名單
        $adminResult = DB::table('instrument_admin')
                            ->select('name','email')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_admin_id','desc')
                            ->get();
        //使用時段
        $sectionSetResult = array();
        $sectionSetResultTmp = DB::table('instrument_section_set')
                            ->select('instrument_section_id')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_section_set_id','desc')
                            ->get();
        foreach($sectionSetResultTmp as $k=>$v)
        {
            array_push($sectionSetResult,$v['instrument_section_id']);
        }
        
        $typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $siteResult = DB::table('instrument_site')
                                    ->select('id','name')
                                    ->where('enable',1)
                                    ->orderBy('id','desc')
                                    ->get();
        $sectionResultTmp = DB::table('instrument_section')
                                    ->select('id','section_type','start_time','end_time')
                                    ->where('enable',1)
                                    ->orderBy('section_type','asc')
                                    ->get();
        $sectionResult = array();
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 1)
            {
                $tmp = array('1'=>$v,'2'=>'');
                array_push($sectionResult,$tmp);
            }
        }
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 2)
            {
                $isset = false;
                foreach($sectionResult as $k1=>$v1)
                {
                    if($v1['2']== '')
                    {
                        $isset = true;
                        $sectionResult[$k1]['2'] = $v;
                        break;
                    }
                }
                if(!$isset)
                {
                    $tmp = array('1'=>'','2'=>$v);
                    array_push($sectionResult,$tmp);
                }
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('adminResult', $adminResult);
        $this->view->with('sectionSetResult', $sectionSetResult);
        $this->view->with('typeResult', $typeResult);
        $this->view->with('siteResult', $siteResult);
        $this->view->with('sectionResult', $sectionResult);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_data')
                            ->select('instrument_data.*',
                                        'instrument_type.name as type_name',
                                        'instrument_site.name as site_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_data.create_admin_id','=','member_admin.id')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->where('instrument_data.id',$id)
                            ->get();
        //管理員名單
        $adminResult = DB::table('instrument_admin')
                            ->select('name','email')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_admin_id','desc')
                            ->get();
        //使用時段
        $sectionSetResult = array();
        $sectionSetResultTmp = DB::table('instrument_section_set')
                            ->select('instrument_section_id')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_section_set_id','desc')
                            ->get();
        foreach($sectionSetResultTmp as $k=>$v)
        {
            array_push($sectionSetResult,$v['instrument_section_id']);
        }

        $sectionResultTmp = DB::table('instrument_section')
                                    ->select('id','section_type','start_time','end_time')
                                    ->where('enable',1)
                                    ->orderBy('section_type','asc')
                                    ->get();
        $sectionResult = array();
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 1)
            {
                $tmp = array('1'=>$v,'2'=>'');
                array_push($sectionResult,$tmp);
            }
        }
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 2)
            {
                $isset = false;
                foreach($sectionResult as $k1=>$v1)
                {
                    if($v1['2']== '')
                    {
                        $isset = true;
                        $sectionResult[$k1]['2'] = $v;
                        break;
                    }
                }
                if(!$isset)
                {
                    $tmp = array('1'=>'','2'=>$v);
                    array_push($sectionResult,$tmp);
                }
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('adminResult', $adminResult);
        $this->view->with('sectionSetResult', $sectionSetResult);
        $this->view->with('sectionResult', $sectionResult);

        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'instrument_type_id' => 'integer|required',
                    'instrument_site_id' => 'integer|required',
                    'instrument_id' => 'string|required|max:12',
                    'name' => 'string|required|max:64',
                    'function' => 'string|required',
                    'admin_name' => 'array|required',
                    'admin_email' => 'array|required',
                    'open_section' => 'array|required',
                    'reservation_limit' => 'integer|required',
                    'notice' => 'integer|required',
                    'cancel_limit' => 'integer|required',
                    'cancel_notice' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $invalid[] = $validator->errors();
        }
        if (count($invalid) > 0) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $invalid;
            return $this->view;
        }

        //處理可預約時段
        $open_section_string = "";
        $open_section_1 = 0;
        $open_section_2 = 0;
        $open_section = Request::input('open_section');
        foreach($open_section as $k=>$v)
        {
            if(explode("_",$v)[1] == "1")
            {
                $open_section_1 = 1;
            }
            if(explode("_",$v)[1] == "2")
            {
                $open_section_2 = 1;
            }
        }
        if($open_section_1 == 1 && $open_section_2 == 0)
        {
            $open_section_string = "1_";
        }
        else if($open_section_1 == 0 && $open_section_2 == 1)
        {
            $open_section_string = "_2";
        }
        else if($open_section_1 == 1 && $open_section_2 == 1)
        {
            $open_section_string = "1_2";
        }

        try {
            DB::transaction(function()use($open_section_string){
                $id = DB::table('instrument_data')
                        ->insertGetId(
                            array('uid'=>'-',
                                    'salt'=>'-',
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'instrument_type_id'=>Request::input('instrument_type_id'),
                                    'instrument_site_id'=>Request::input('instrument_site_id'),
                                    'instrument_id'=>Request::input('instrument_id'),
                                    'name'=>Request::input('name'),
                                    'open_section'=>$open_section_string,
                                    'function'=>Request::input('function'),
                                    'reservation_limit'=>Request::input('reservation_limit'),
                                    'notice'=>Request::input('notice'),
                                    'cancel_limit'=>Request::input('cancel_limit'),
                                    'cancel_notice'=>Request::input('cancel_notice'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                //製作uid以及salt
                $date = date('Y-m-d H:i:s').$id;
                $salt = substr(md5($date),5,5);
                $uid = md5($salt.$date);
                
                DB::table('instrument_data')
                    ->where('id',$id)
                    ->update(['uid'=>$uid,
                                'salt'=>$salt
                    ]);
                $result_after = DB::table('instrument_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                //管理員名單
                $admin_name = Request::input('admin_name');
                $admin_email = Request::input('admin_email');
                foreach($admin_name as $k=>$v)
                {
                    $instrument_admin_id = DB::table('instrument_admin')
                        ->select('instrument_admin_id')
                        ->where('instrument_data_id',$id)
                        ->orderBy('instrument_admin_id','desc')
                        ->limit(1)
                        ->get();
                    if(!isset($instrument_admin_id[0]['instrument_admin_id']))
                    {
                        $instrument_admin_id = 0;
                    }
                    else
                    {
                        $instrument_admin_id = $instrument_admin_id[0]['instrument_admin_id'];
                    }
                    $instrument_admin_id = intval($instrument_admin_id)+1;
                    DB::table('instrument_admin')
                        ->insert(array(
                            'instrument_admin_id'=>$instrument_admin_id,
                            'instrument_data_id'=>$id,
                            'name'=>$v,
                            'email'=>$admin_email[$k]
                        ));
                }
                //使用時段
                $open_section = Request::input('open_section');
                foreach($open_section as $k=>$v)
                {
                    $instrument_section_set_id = DB::table('instrument_section_set')
                        ->select('instrument_section_set_id')
                        ->where('instrument_data_id',$id)
                        ->orderBy('instrument_section_set_id','desc')
                        ->limit(1)
                        ->get();
                    if(!isset($instrument_section_set_id[0]['instrument_section_set_id']))
                    {
                        $instrument_section_set_id = 0;
                    }
                    else
                    {
                        $instrument_section_set_id = $instrument_section_set_id[0]['instrument_section_set_id'];
                    }
                    $instrument_section_set_id = intval($instrument_section_set_id)+1;
                    DB::table('instrument_section_set')
                        ->insert(array(
                            'instrument_section_set_id'=>$instrument_section_set_id,
                            'instrument_data_id'=>$id,
                            'instrument_section_id'=>explode('_',$v)[0]
                        ));
                }

            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        return $this->view;
    }

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'instrument_type_id' => 'integer|required',
                    'instrument_site_id' => 'integer|required',
                    'instrument_id' => 'string|required|max:12',
                    'name' => 'string|required|max:64',
                    'function' => 'string|required',
                    'admin_name' => 'array|required',
                    'admin_email' => 'array|required',
                    'open_section' => 'array|required',
                    'reservation_limit' => 'integer|required',
                    'notice' => 'integer|required',
                    'cancel_limit' => 'integer|required',
                    'cancel_notice' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //處理可預約時段
        $open_section_string = "";
        $open_section_1 = 0;
        $open_section_2 = 0;
        $open_section = Request::input('open_section');
        foreach($open_section as $k=>$v)
        {
            if(explode("_",$v)[1] == "1")
            {
                $open_section_1 = 1;
            }
            if(explode("_",$v)[1] == "2")
            {
                $open_section_2 = 1;
            }
        }
        if($open_section_1 == 1 && $open_section_2 == 0)
        {
            $open_section_string = "1_";
        }
        else if($open_section_1 == 0 && $open_section_2 == 1)
        {
            $open_section_string = "_2";
        }
        else if($open_section_1 == 1 && $open_section_2 == 1)
        {
            $open_section_string = "1_2";
        }
        
        try {
            DB::transaction(function()use($open_section_string){
                $result_before = DB::table('instrument_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('instrument_data')
                    ->where('id',Request::input('id'))
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'instrument_type_id'=>Request::input('instrument_type_id'),
                                'instrument_site_id'=>Request::input('instrument_site_id'),
                                'instrument_id'=>Request::input('instrument_id'),
                                'name'=>Request::input('name'),
                                'open_section'=>$open_section_string,
                                'function'=>Request::input('function'),
                                'reservation_limit'=>Request::input('reservation_limit'),
                                'notice'=>Request::input('notice'),
                                'cancel_limit'=>Request::input('cancel_limit'),
                                'cancel_notice'=>Request::input('cancel_notice'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('instrument_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);

                //管理員名單
                $admin_name = Request::input('admin_name');
                $admin_email = Request::input('admin_email');
                DB::table('instrument_admin')
                    ->where('instrument_data_id',Request::input('id'))
                    ->delete();
                foreach($admin_name as $k=>$v)
                {
                    $instrument_admin_id = DB::table('instrument_admin')
                        ->select('instrument_admin_id')
                        ->where('instrument_data_id',Request::input('id'))
                        ->orderBy('instrument_admin_id','desc')
                        ->limit(1)
                        ->get();
                    if(!isset($instrument_admin_id[0]['instrument_admin_id']))
                    {
                        $instrument_admin_id = 0;
                    }
                    else
                    {
                        $instrument_admin_id = $instrument_admin_id[0]['instrument_admin_id'];
                    }
                    $instrument_admin_id = intval($instrument_admin_id)+1;
                    DB::table('instrument_admin')
                        ->insert(array(
                            'instrument_admin_id'=>$instrument_admin_id,
                            'instrument_data_id'=>Request::input('id'),
                            'name'=>$v,
                            'email'=>$admin_email[$k]
                        ));
                }
                //使用時段
                $open_section = Request::input('open_section');
                DB::table('instrument_section_set')
                    ->where('instrument_data_id',Request::input('id'))
                    ->delete();
                foreach($open_section as $k=>$v)
                {
                    $instrument_section_set_id = DB::table('instrument_section_set')
                        ->select('instrument_section_set_id')
                        ->where('instrument_data_id',Request::input('id'))
                        ->orderBy('instrument_section_set_id','desc')
                        ->limit(1)
                        ->get();
                    if(!isset($instrument_section_set_id[0]['instrument_section_set_id']))
                    {
                        $instrument_section_set_id = 0;
                    }
                    else
                    {
                        $instrument_section_set_id = $instrument_section_set_id[0]['instrument_section_set_id'];
                    }
                    $instrument_section_set_id = intval($instrument_section_set_id)+1;
                    DB::table('instrument_section_set')
                        ->insert(array(
                            'instrument_section_set_id'=>$instrument_section_set_id,
                            'instrument_data_id'=>Request::input('id'),
                            'instrument_section_id'=>explode('_',$v)[0]
                        ));
                }

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

    public function ajax_delete() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'array|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $ids = Request::input('id', []);
        try {
            foreach ($ids as $k => $v) {
                $id = $v;

                $result_before = DB::table('instrument_data')
                                    ->where('id',$id)
                                    ->get();
                DB::table('instrument_data')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_data',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                
                //管理員名單刪除
                DB::table('instrument_admin')
                    ->where('instrument_data_id',$id)
                    ->delete();
                //使用時段刪除
                DB::table('instrument_section_set')
                    ->where('instrument_data_id',$id)
                    ->delete();
            }
        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        } catch (\Exception $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = $ex->getMessage();
            return $this->view;
        }

        
        $this->view['msg'] = trans('message.success.delete');
        return $this->view;
    }

    public function ajax_get_instrument() {

        $id = Request::input('id');
        $listResult = DB::table('instrument_data');
        $listResult = $listResult->select('id','instrument_platform_id','name')
                                    ->whereIn('instrument_platform_id',$id)
                                    ->get();
        
        return $listResult;
    }
}
