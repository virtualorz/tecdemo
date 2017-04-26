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

class ActivityController extends Controller {

    public function index() {
        
        $keyword = Request::input('keyword', '');
        $searchResult = array();
        $liest_aResult = array();
        $liest_unaResult = array();
        $pagination = array();
        if($keyword != "")
        {
            //找相關平台名稱
            $plateform = array();
            $tmp_plateform = DB::table('instrument_type')
                        ->select('id')
                        ->where('name','like','%'.$keyword.'%')
                        ->get();
            foreach($tmp_plateform as $k=>$v)
            {
                array_push($plateform,$v['id']);
            }
            //找相關儀器
            $instrument = array();
             if (mb_strlen($keyword, mb_detect_encoding($keyword)) == strlen($keyword))
            {//無中文
                $tmp_instrument = DB::table('activity_instrument')
                            ->select('activity_instrument.activity_id')
                            ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                            ->orWhere('instrument_data.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.instrument_id','like','%'.$keyword.'%')
                            ->get();
            }
            else
            {//有中文
                $tmp_instrument = DB::table('activity_instrument')
                            ->select('activity_instrument.activity_id')
                            ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                            ->where('instrument_data.name','like','%'.$keyword.'%')
                            ->get();
            }
            
            
            foreach($tmp_instrument as $k=>$v)
            {
                array_push($instrument,$v['activity_id']);
            }
            $para[0] = $instrument;
            $para[1] = $keyword;
            $searchResult_tmp = DB::table('activity_data')
                            ->orWhere(function ($query)use($para) {
                                $query->orWhereIn('activity_data.id',$para[0]);
                                $query->orWhere('activity_data.activity_name','like','%'.$para[1].'%');
                            })
                            ->where('activity_data.enable',1)
                            ->select('activity_data.uid',
                                            'activity_data.salt',
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y-%m-%d") as end_dt_org'),
                                            'activity_data.activity_id',
                                            'activity_type.name as type_name',
                                            'activity_data.activity_name',
                                            'activity_data.time',
                                            'activity_data.score',
                                            'activity_data.relative_plateform',
                                            DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                            ->leftJoin('activity_reservation_data', function ($join) {
                                $join->on('activity_reservation_data.activity_id', '=', 'activity_data.id')->where('activity_reservation_data.reservation_status', '=', 1);
                            })
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('start_dt','desc')
                            ->get();
            $searchResult = array();
            
            foreach($searchResult_tmp as $k=>$v)
            {
                $v['relative_plateform'] = json_decode($v['relative_plateform'],true);
                foreach($v['relative_plateform'] as $k1=>$v1)
                {
                    if(in_array($v1,$plateform))
                    {
                        array_push($searchResult,$v);
                        break;
                    }
                }
            }
        }
        else
        {
            $liest_aResult = DB::table('activity_data')
                            ->where('activity_data.enable',1)
                            ->where(function($query){
                                $query->whereNull('end_dt');
                                $query->orWhere('end_dt', '>=', date('Y-m-d'));
                            })
                            
                            ->select('activity_data.uid',
                                                'activity_data.salt',
                                                DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y-%m-%d") as end_dt_org'),
                                                'activity_data.activity_id',
                                                'activity_type.name as type_name',
                                                'activity_data.activity_name',
                                                'activity_data.time',
                                                'activity_data.score',
                                                'activity_data.relative_plateform',
                                                DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                            ->leftJoin('activity_reservation_data', function ($join) {
                                $join->on('activity_reservation_data.activity_id', '=', 'activity_data.id')->where('activity_reservation_data.reservation_status', '=', 1);
                            })
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('start_dt','desc')
                            ->get();
            $liest_unaResult = DB::table('activity_data')
                            ->where('activity_data.enable',1)
                            ->whereDate('end_dt','<',date('Y-m-d'))
                            ->select('activity_data.uid',
                                                'activity_data.salt',
                                                DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y-%m-%d") as end_dt_org'),
                                                'activity_data.activity_id',
                                                'activity_type.name as type_name',
                                                'activity_data.activity_name',
                                                'activity_data.time',
                                                'activity_data.score',
                                                'activity_data.relative_plateform',
                                                DB::raw('count(activity_reservation_data.member_id) as reservation_count'),
                                                DB::raw('GROUP_CONCAT( 
                                                                        DISTINCT CONCAT(instrument_data.name,"(",activity_instrument.permission_id,")")
                                                                        SEPARATOR ",") as instrument_name'))
                            ->leftJoin('activity_reservation_data', function ($join) {
                                $join->on('activity_reservation_data.activity_id', '=', 'activity_data.id')->where('activity_reservation_data.reservation_status', '=', 1);
                            })
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->leftJoin('activity_instrument','activity_instrument.activity_id','=','activity_data.id')
                            ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('start_dt','desc')
                            ->paginate(Config::get('pagination.items'));
            $pagination = $this->getPagination(json_decode($liest_unaResult->toJson(),true)['total']);
        }
        $instrument_type = array();
        $instrument_type_tmp = DB::table('instrument_type')
                        ->select('id','name')
                        ->get();
        foreach($instrument_type_tmp as $k=>$v)
        {
            $instrument_type[$v['id']] = $v['name'];
        }

        $this->view->with('searchResult', $searchResult);
        $this->view->with('liest_aResult', $liest_aResult);
        $this->view->with('liest_unaResult', $liest_unaResult);
        $this->view->with('instrument_type', $instrument_type);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('pagination', $pagination);

        return $this->view;
    }

    public function reservation() {

        $id = explode('-',Route::input('id', '0-0'));
        $dataResult = DB::table('activity_data')
                    ->where('uid',$id[0])
                    ->where('salt',$id[1])
                    ->select('activity_data.id',
                                        'activity_data.uid',
                                        'activity_data.salt',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y-%m-%d") as end_dt_org'),
                                        'activity_data.activity_id',
                                        'activity_type.name as type_name',
                                        'activity_data.activity_name',
                                        'activity_data.relative_plateform',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_data.score',
                                        'activity_data.content',
                                        DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                    ->leftJoin('activity_reservation_data','activity_reservation_data.activity_id','=','activity_data.id')
                    ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                    ->groupBy('activity_data.id')
                    ->orderBy('end_dt','desc')
                    ->get();
        if (count($dataResult) > 0)
        {
            //內容
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
            //相關平台
            $tmp = json_decode($dataResult[0]['relative_plateform'],true);
            $instrument_type = array();
            foreach($tmp as $k1=>$v1)
            {
                $instrument_typeResult = DB::table('instrument_type')
                    ->select('name')
                    ->where('id',$v1)
                    ->get();
                if(isset($instrument_typeResult[0]['name']))
                {
                    array_push($instrument_type,$instrument_typeResult[0]['name']);
                }
            }
            $dataResult[0]['instrument_type'] = $instrument_type;
            //開通儀器
            $instrumentResult = DB::table('activity_instrument')
                    ->select('instrument_data.name')
                    ->leftJoin('instrument_data','activity_instrument.instrument_id','=','instrument_data.id')
                    ->where('activity_instrument.activity_id',$dataResult[0]['id'])
                    ->groupBy('activity_instrument.instrument_id')
                    ->get();
            $instrument = array();
            foreach($instrumentResult as $k1=>$v1)
            {
                array_push($instrument,$v1['name']);
            }
            $dataResult[0]['instrument'] = $instrument;
            //使用者預約狀況
            $dataResult[0]['is_reservation'] = 0;
            $dataResult[0]['can_cancel'] = 0;
            if(User::Id() != null)
            {
                $reservationResult = DB::table('activity_reservation_data')
                        ->select('reservation_status','attend_status')
                        ->where('activity_id',$dataResult[0]['id'])
                        ->where('member_id',User::Id())
                        ->orderBy('created_at','desc')
                        ->get();
                if(count($reservationResult) !=0)
                {
                    if($reservationResult[0]['reservation_status'] == 1 && $reservationResult[0]['attend_status'] == 0)
                    {
                        $dataResult[0]['is_reservation'] = 1;
                        $dataResult[0]['can_cancel'] = 1;
                    }
                    else if($reservationResult[0]['reservation_status'] != 0)
                    {
                        $dataResult[0]['is_reservation'] = 1;
                    }
                }
            }
        }
        
        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('level', Config::get('data.level'));

        return $this->view;
    }

    ##

    public function ajax_reservation() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'reservation' => 'integer|required',
                    'activity_id' => 'integer|required',
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
        //檢查登入狀況
        if(User::Id() == null)
        {
            $this->view['result'] = 'login';
            $this->view['msg'] = trans('message.error.not_login');
            $this->view['detail'] = array(trans('message.error.not_login_info'));
            return $this->view;
        }
        //檢查是否已經預約過
        if(Request::input('reservation') == 1)
        {//點預約
            $member_count = DB::table('activity_reservation_data')
                        ->where('activity_id',Request::input('activity_id'))
                        ->where('member_id',User::Id())
                        ->where('reservation_status',1)
                        ->orderBy('created_at','desc')
                        ->count();
            if($member_count == 0)
            {
                try {
                    DB::transaction(function(){
                        $id = DB::table('activity_reservation_data')
                                ->insertGetId(
                                    array('activity_id'=>Request::input('activity_id'),
                                            'member_id'=>User::Id(),
                                            'created_at'=>date('Y-m-d H:i:s'),
                                            'reservation_status'=>1,
                                            'attend_status'=>0,
                                            'pass_status'=>0
                                    )
                                );
                    });

                } catch (DBProcedureException $e) {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.database');
                    $this->view['detail'][] = $e->getMessage();

                    return $this->view;
                }
                $this->view['msg'] = trans('message.success.reservation');
            }
            else
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array('已在預約名單內無法再次預約');
                return $this->view;
            }
        }
        else
        {//點取消
            $member_count = DB::table('activity_reservation_data')
                        ->where('activity_id',Request::input('activity_id'))
                        ->where('member_id',User::Id())
                        ->where('reservation_status',1)
                        ->where('attend_status',0)
                        ->orderBy('created_at','desc')
                        ->count();
            if($member_count != 0)
            {
                try {
                    DB::transaction(function(){
                        DB::table('activity_reservation_data')
                                ->where('activity_id',Request::input('activity_id'))
                                ->where('member_id',User::Id())
                                ->where('reservation_status',1)
                                ->where('attend_status',0)
                                ->update(
                                    array('reservation_status'=>0
                                    )
                        );
                    });

                } catch (DBProcedureException $e) {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.database');
                    $this->view['detail'][] = $e->getMessage();

                    return $this->view;
                }
                $this->view['msg'] = trans('message.success.cancel');
            }
            else
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array('已出席活動無法取消預約');
                return $this->view;
            }

        }
        
        return $this->view;
    }

    public function ajax_get_department() {

        $id = Request::input('id');
        $listResult = DB::table('system_department');
        $listResult = $listResult->select('id','name')
                                    ->where('organize_id',$id)
                                    ->get();
        
        return $listResult;
    }

    public function ajax_get_pi() {

        $id = Request::input('id');
        $listResult = DB::table('system_pi_list');
        $listResult = $listResult->select('id','name')
                                    ->where('department_id',$id)
                                    ->get();
        
        return $listResult;
    }

}
