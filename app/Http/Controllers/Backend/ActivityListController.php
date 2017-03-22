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

class ActivityListController extends Controller {

    public function index() {

        $date = Request::input('date', '');
        $name = Request::input('name', '');
        $instrument = Request::input('instrument', '');

        $listResult = DB::table('activity_data');
        if($date != "")
        {
            $listResult->where('activity_data.start_dt','<=',$date);
            $listResult->where('activity_data.end_dt','<=',$date);
        }
        if($name != "")
        {
            $listResult->where('activity_data.activity_name','like','%'.$name.'%');
        }
        if($instrument != "")
        {
            $listResult->where('activity_instrument.activity_instrument_id','=',$instrument);
        }

        $listResult = $listResult->select('activity_data.id',
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.created_at, "%Y/%m/%d") as created_at'),
                                            'activity_data.activity_name',
                                            'activity_data.time',
                                            'activity_data.enable',
                                            DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                                    ->leftJoin('activity_reservation_data', function ($join) {
                                        $join->on('activity_reservation_data.activity_id', '=', 'activity_data.id')->where('activity_reservation_data.reservation_status', '=', 1);
                                    })
                                    ->leftJoin('activity_instrument','activity_instrument.activity_id','=','activity_data.id')
                                    ->groupBy('activity_data.id')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $instrumentResult = DB::table('instrument_data')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('instrumentResult', $instrumentResult);
        return $this->view;
    }

    public function add() {
        $instrument_typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $activity_typeResult = DB::table('activity_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $this->view->with('instrument_typeResult', $instrument_typeResult);
        $this->view->with('activity_typeResult', $activity_typeResult);
        $this->view->with('level', Config::get('data.level'));
        $this->view->with('pass_type', Config::get('data.pass_type'));
        $this->view->with('permission', Config::get('data.permission'));
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('activity_data')
                            ->select('activity_data.*',
                                    DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                    DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                    DB::raw('DATE_FORMAT(activity_data.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','activity_data.create_admin_id','=','member_admin.id')
                            ->where('activity_data.id',$id)
                            ->get();
        $dataResult[0]['relative_plateform'] = json_decode($dataResult[0]['relative_plateform'],true);
        $relative_plateformResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->wherein('id',$dataResult[0]['relative_plateform'])
                                    ->get();
        $activity_instrumentResult = DB::table('activity_instrument')
                            ->select('activity_instrument.*','instrument_data.name as instrument_name','instrument_data.instrument_type_id')
                            ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                            ->where('activity_instrument.activity_id',$id)
                            ->get();
        $instrumentResult = DB::table('instrument_data')
                                    ->select('id','name','instrument_type_id')
                                    ->orderBy('id','desc')
                                    ->wherein('instrument_type_id',$dataResult[0]['relative_plateform'])
                                    ->get();

        $instrument_typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $activity_typeResult = DB::table('activity_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('relative_plateformResult', $relative_plateformResult);
        $this->view->with('activity_instrumentResult', $activity_instrumentResult);
        $this->view->with('instrumentResult', $instrumentResult);
        $this->view->with('instrument_typeResult', $instrument_typeResult);
        $this->view->with('activity_typeResult', $activity_typeResult);
        $this->view->with('level', Config::get('data.level'));
        $this->view->with('pass_type', Config::get('data.pass_type'));
        $this->view->with('permission', Config::get('data.permission'));

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('activity_data')
                            ->select('activity_data.*',
                                    DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                    DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                    DB::raw('DATE_FORMAT(activity_data.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'member_admin.name as created_admin_name','activity_type.name as activity_type_name')
                            ->leftJoin('member_admin','activity_data.create_admin_id','=','member_admin.id')
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->where('activity_data.id',$id)
                            ->get();
        if (count($dataResult[0]) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }

        $dataResult[0]['relative_plateform'] = json_decode($dataResult[0]['relative_plateform'],true);
        $relative_plateformResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->wherein('id',$dataResult[0]['relative_plateform'])
                                    ->get();
        $activity_instrumentResult = DB::table('activity_instrument')
                            ->select('activity_instrument.*','instrument_data.name as instrument_name','instrument_data.instrument_type_id')
                            ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                            ->where('activity_instrument.activity_id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('relative_plateformResult', $relative_plateformResult);
        $this->view->with('activity_instrumentResult', $activity_instrumentResult);
        $this->view->with('level', Config::get('data.level'));
        $this->view->with('pass_type', Config::get('data.pass_type'));
        $this->view->with('permission', Config::get('data.permission'));

        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        if(Request::input('end_dt') != "")
        {
            $validator = Validator::make(Request::all(), [
                        'activity_id' => 'string|required|max:64',
                        'start_dt' => 'date|required|before:end_dt',
                        'activity_name' => 'string|required|max:32',
                        'activity_type_id' => 'integer|required',
                        'relative_plateform' => 'array|required',
                        'level' => 'integer|required',
                        'time' => 'integer|required',
                        'score' => 'integer|required',
                        'pass_type' => 'integer|required',
                        'pass_condition' => 'string|required|max:64',
                        'content' => 'string|required',
                        'enable' => 'integer|required',
            ]);
        }
        else
        {
            $validator = Validator::make(Request::all(), [
                        'activity_id' => 'string|required|max:64',
                        'start_dt' => 'date|required',
                        'activity_name' => 'string|required|max:32',
                        'activity_type_id' => 'integer|required',
                        'relative_plateform' => 'array|required',
                        'level' => 'integer|required',
                        'time' => 'integer|required',
                        'score' => 'integer|required',
                        'pass_type' => 'integer|required',
                        'pass_condition' => 'string|required|max:64',
                        'content' => 'string|required',
                        'enable' => 'integer|required',
            ]);
        }

        if ($validator->fails()) {
            $invalid[] = $validator->errors();
        }
        if (count($invalid) > 0) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $invalid;
            return $this->view;
        }

        $content = FileUpload::moveEditor(Request::input('content'));

        try {
            DB::transaction(function()use($content){
                if(Request::input('end_dt') != "")
                {
                    $end_dt = Request::input('end_dt');
                }
                else
                {
                    $end_dt = NULL;
                }
                $id = DB::table('activity_data')
                        ->insertGetId(
                            array('uid'=>'-',
                                    'salt'=>'-',
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'activity_id'=>Request::input('activity_id'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'end_dt'=>$end_dt,
                                    'activity_name'=>Request::input('activity_name'),
                                    'activity_type_id'=>Request::input('activity_type_id'),
                                    'relative_plateform'=>json_encode(Request::input('relative_plateform')),
                                    'level'=>Request::input('level'),
                                    'time'=>Request::input('time'),
                                    'score'=>Request::input('score'),
                                    'pass_type'=>Request::input('pass_type'),
                                    'pass_condition'=>Request::input('pass_condition'),
                                    'content'=>$content,
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                //製作uid以及salt
                $date = date('Y-m-d H:i:s').$id;
                $salt = substr(md5($date),5,5);
                $uid = md5($salt.$date);
                
                DB::table('activity_data')
                    ->where('id',$id)
                    ->update(['uid'=>$uid,
                                'salt'=>$salt
                    ]);
                $result_after = DB::table('activity_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                $instrument = Request::input('instrument');
                $instrument_permission = Request::input('instrument_permission');
                foreach($instrument as $k=>$v)
                {
                    $activity_instrument = DB::table('activity_instrument')
                            ->select('activity_instrument_id')
                            ->where('activity_id',$id)
                            ->orderBy('activity_instrument_id','desc')
                            ->first();
                    if(!isset($activity_instrument['activity_instrument_id']))
                    {
                        $activity_instrument = 0;
                    }
                    else
                    {
                        $activity_instrument = $activity_instrument['activity_instrument_id'];
                    }
                    $activity_instrument = intval($activity_instrument) +1;
                    DB::table('activity_instrument')
                            ->insert(
                                array('activity_id'=>$id,
                                        'activity_instrument_id'=>$activity_instrument,
                                        'instrument_id'=>$v,
                                        'permission_id'=>$instrument_permission[$k]
                                )
                            );
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
        if(Request::input('end_dt') != "")
        {
            $validator = Validator::make(Request::all(), [
                        'activity_id' => 'string|required|max:64',
                        'start_dt' => 'date|required|before:end_dt',
                        'activity_name' => 'string|required|max:32',
                        'activity_type_id' => 'integer|required',
                        'relative_plateform' => 'array|required',
                        'level' => 'integer|required',
                        'time' => 'integer|required',
                        'score' => 'integer|required',
                        'pass_type' => 'integer|required',
                        'pass_condition' => 'string|required|max:64',
                        'content' => 'string|required',
                        'enable' => 'integer|required',
            ]);
        }
        else
        {
            $validator = Validator::make(Request::all(), [
                        'activity_id' => 'string|required|max:64',
                        'start_dt' => 'date|required',
                        'activity_name' => 'string|required|max:32',
                        'activity_type_id' => 'integer|required',
                        'relative_plateform' => 'array|required',
                        'level' => 'integer|required',
                        'time' => 'integer|required',
                        'score' => 'integer|required',
                        'pass_type' => 'integer|required',
                        'pass_condition' => 'string|required|max:64',
                        'content' => 'string|required',
                        'enable' => 'integer|required',
            ]);
        }

        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $content = FileUpload::moveEditor(Request::input('content'));
        try {
            DB::transaction(function()use($content){
                $result_before = DB::table('activity_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                if(Request::input('end_dt') != "")
                {
                    $end_dt = Request::input('end_dt');
                }
                else
                {
                    $end_dt = NULL;
                }
                DB::table('activity_data')
                    ->where('id',Request::input('id'))
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'activity_id'=>Request::input('activity_id'),
                                'start_dt'=>Request::input('start_dt'),
                                'end_dt'=>$end_dt,
                                'activity_name'=>Request::input('activity_name'),
                                'activity_type_id'=>Request::input('activity_type_id'),
                                'relative_plateform'=>json_encode(Request::input('relative_plateform')),
                                'level'=>Request::input('level'),
                                'time'=>Request::input('time'),
                                'score'=>Request::input('score'),
                                'pass_type'=>Request::input('pass_type'),
                                'pass_condition'=>Request::input('pass_condition'),
                                'content'=>$content,
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('activity_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                FileUpload::deleteEditor($result_before[0]['content'],$result_after[0]['content']);
                //儀器表處理
                DB::table('activity_instrument')
                    ->where('activity_id',Request::input('id'))
                    ->delete();
                $instrument = Request::input('instrument');
                $instrument_permission = Request::input('instrument_permission');
                foreach($instrument as $k=>$v)
                {
                    $activity_instrument = DB::table('activity_instrument')
                            ->select('activity_instrument_id')
                            ->where('activity_id',Request::input('id'))
                            ->orderBy('activity_instrument_id','desc')
                            ->first();
                    if(!isset($activity_instrument['activity_instrument_id']))
                    {
                        $activity_instrument = 0;
                    }
                    else
                    {
                        $activity_instrument = $activity_instrument['activity_instrument_id'];
                    }
                    $activity_instrument = intval($activity_instrument) +1;
                    DB::table('activity_instrument')
                            ->insert(
                                array('activity_id'=>Request::input('id'),
                                        'activity_instrument_id'=>$activity_instrument,
                                        'instrument_id'=>$v,
                                        'permission_id'=>$instrument_permission[$k]
                                )
                            );
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
        $un_del_id = array();
        try {
            foreach ($ids as $k => $v) {
                $id = $v;

                $passcount = DB::table('activity_reservation_data')
                        ->where('activity_id',$id)
                        ->where('attend_status',1)
                        ->count();
                if($passcount ==0)
                {
                    $result_before = DB::table('activity_data')
                                        ->where('id',$id)
                                        ->get();
                    DB::table('activity_data')
                        ->where('id',$id)
                        ->delete();
                    DBProcedure::writeLog([
                        'table' => 'activity_data',
                        'operator' => DBOperator::OP_DELETE,
                        'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                        'admin_id' => User::id()
                    ]);
                    FileUpload::deleteEditor($result_before[0]['content']);
                }
                else
                {
                    array_push($un_del_id,$id);
                }
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

        if(count($un_del_id) !=0)
        {
            $text = "id: ";
            foreach($un_del_id as $k=>$v)
            {
                $text .= $v.", ";
            }
            $text .= "已經有使用者報到無法刪除！請使用隱藏功能";
            $this->view['result'] = 'ok';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'] = array($text);

            return $this->view;
        }

        
        $this->view['msg'] = trans('message.success.delete');
        return $this->view;
    }

    public function ajax_get_instrument() {

        $id = Request::input('id');
        $listResult = DB::table('instrument_data');
        $listResult = $listResult->select('id','instrument_type_id','name')
                                    ->whereIn('instrument_type_id',$id)
                                    ->get();
        
        return $listResult;
    }
}
