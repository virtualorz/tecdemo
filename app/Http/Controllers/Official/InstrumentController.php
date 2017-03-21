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
use Mail;

class InstrumentController extends Controller {

    public function index() {
        
        $keyword = Request::input('keyword', '');
        $searchResult = array();
        $listResult = array();
        if($keyword != "")
        {
            $searchResult = DB::table('instrument_data');
            if (mb_strlen($keyword, mb_detect_encoding($keyword)) == strlen($keyword))
            {//無中文
                $searchResult = $searchResult
                            ->orWhere('instrument_type.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.instrument_id','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.function','like','%'.$keyword.'%');
            }
            else
            {//有中文
                $searchResult = $searchResult
                            ->orWhere('instrument_type.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.function','like','%'.$keyword.'%');
            }
            $searchResult = $searchResult
                            ->select('instrument_data.uid',
                                            'instrument_data.salt',
                                            'instrument_type.name as type_name',
                                            'instrument_data.instrument_id',
                                            'instrument_data.name',
                                            'instrument_data.function',
                                            'instrument_site.name as site_name',
                                            'instrument_rate.start_dt',
                                            'instrument_admin.name as admin_name')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->leftJoin('instrument_rate',function($join){
                                $join->on('instrument_rate.instrument_data_id','=','instrument_data.id')
                                    ->where('instrument_rate.start_dt','<=',date('Y-m-d'));
                            })
                            ->leftJoin('instrument_admin','instrument_admin.instrument_data_id','=','instrument_data.id')
                            ->groupBy('instrument_data.id')
                            ->orderBy('type_name','asc')
                            ->orderBy('name','asc')
                            ->get();
        }
        else
        {
            $instrumentResult = DB::table('instrument_data')
                            ->select('instrument_data.uid',
                                            'instrument_data.salt',
                                            'instrument_type.name as type_name',
                                            'instrument_data.instrument_id',
                                            'instrument_data.name',
                                            'instrument_data.function',
                                            'instrument_site.name as site_name',
                                            'instrument_data.instrument_type_id',
                                            'instrument_rate.start_dt',
                                            'instrument_admin.name as admin_name')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->leftJoin('instrument_rate',function($join){
                                $join->on('instrument_rate.instrument_data_id','=','instrument_data.id')
                                    ->where('instrument_rate.start_dt','<=',date('Y-m-d'));
                            })
                            ->leftJoin('instrument_admin','instrument_admin.instrument_data_id','=','instrument_data.id')
                            ->groupBy('instrument_data.id')
                            ->orderBy('type_name','asc')
                            ->orderBy('instrument_id','asc')
                            ->get();
            foreach($instrumentResult as $k=>$v)
            {
                if(!isset($listResult[$v['instrument_type_id']]))
                {
                    $listResult[$v['instrument_type_id']] = array();
                }
                array_push($listResult[$v['instrument_type_id']],$v);
            }
        }

        $this->view->with('searchResult', $searchResult);
        $this->view->with('listResult', $listResult);

        return $this->view;
    }

    public function reservation() {

        //整體可查看的兩個月
        $total_start_dt = date('Y/m/d',strtotime('-2 months',strtotime(date('Y-m-d'))));
        $total_end_dt = date('Y/m/d',strtotime('+2 months',strtotime(date('Y-m-d'))));
        $total_start_dt_org = date('Y-m-d',strtotime('-2 months',strtotime(date('Y-m-d'))));
        $total_end_dt_org = date('Y-m-d',strtotime('+2 months',strtotime(date('Y-m-d'))));

        //確認時間區間
        $start_dt = Request::input('start_dt', '');
        $end_dt = Request::input('end_dt', '');
        $search_date = Request::input('search_date', '');
        $start_dt_next = null;
        $end_dt_next = null;
        $start_dt_prev = null;
        $end_dt_prev = null;

        if($search_date == '')
        {
            if($start_dt == '' || $end_dt == '')
            {
                $week = date('w',strtotime(date('Y-m-d')));
                $start_dt = date('Y/m/d',strtotime('-'.$week.' days',strtotime(date('Y-m-d'))));
                $start_dt_org = date('Y-m-d',strtotime('-'.$week.' days',strtotime(date('Y-m-d'))));
                $end_dt = date('Y/m/d',strtotime('+6 days',strtotime($start_dt_org)));
                if(strtotime('+7 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org) && strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
                {
                    $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                    $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
                }
                if(strtotime('-1 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org) && strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
                {
                    $start_dt_prev = date('Y-m-d',strtotime('-7 days',strtotime($start_dt_org)));
                    $end_dt_prev = date('Y-m-d',strtotime('-1 days',strtotime($start_dt_org)));
                }
            }
            else
            {
                $start_dt_org = date('Y-m-d',strtotime($start_dt));
                $start_dt = date('Y/m/d',strtotime($start_dt));
                $end_dt = date('Y/m/d',strtotime('+6 days',strtotime($start_dt_org)));
                if(strtotime('+7 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org) && strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
                {
                    $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                    $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
                }
                if(strtotime('-1 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org) && strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
                {
                    $start_dt_prev = date('Y-m-d',strtotime('-7 days',strtotime($start_dt_org)));
                    $end_dt_prev = date('Y-m-d',strtotime('-1 days',strtotime($start_dt_org)));
                }
            }
        }
        else
        {
            $week = date('w',strtotime($search_date));
            $start_dt = date('Y/m/d',strtotime('-'.$week.' days',strtotime($search_date)));
            $start_dt_org = date('Y-m-d',strtotime('-'.$week.' days',strtotime($search_date)));
            $end_dt = date('Y/m/d',strtotime('+6 days',strtotime($start_dt_org)));
            if(strtotime('+7 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org) && strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
            {
                $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
            }
            if(strtotime('-1 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org) && strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
            {
                $start_dt_prev = date('Y-m-d',strtotime('-7 days',strtotime($start_dt_org)));
                $end_dt_prev = date('Y-m-d',strtotime('-1 days',strtotime($start_dt_org)));
            }
        }
        
        

        //儀器資料
        $id = explode('-',Route::input('id', '0-0'));
        $dataResult = DB::table('instrument_data')
                    ->where('uid',$id[0])
                    ->where('salt',$id[1])
                    ->select('instrument_data.id',
                                        'instrument_data.uid',
                                        'instrument_data.salt',
                                        'instrument_data.instrument_id',
                                        'instrument_data.name',
                                        'instrument_data.reservation_limit',
                                        'instrument_data.cancel_limit',
                                        'instrument_data.cancel_notice',
                                        'instrument_data.function')
                    ->get();
        $sectionResult = array();
        $reservationResult = array();
        if (count($dataResult) > 0)
        {
            //開放時段
            $instrumentPermission = User::get('instrumentPermission');
            $sectionResult = DB::table('instrument_section_set')
                        ->select('instrument_section.section_type',
                                    'instrument_section.id',
                                    DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                    DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                        ->leftJoin('instrument_section','instrument_section_set.instrument_section_id','=','instrument_section.id')
                        ->where('instrument_section_set.instrument_data_id',$dataResult[0]['id'])
                        ->orderBy('start_time','asc')
                        ->orderBy('end_time','asc')
                        ->get();
            if(User::id() != null)
            {//已登入使用者
                
                //取得使用者通過的假日權限
                $permissionResult = array();
                $permissionResultTmp = DB::table('activity_reservation_data')
                    ->select('activity_instrument.permission_id')
                    ->leftJoin('activity_instrument','activity_reservation_data.activity_id','=','activity_instrument.activity_id')
                    ->where('activity_reservation_data.member_id',User::Id())
                    ->where('activity_instrument.instrument_id',$dataResult[0]['id'])
                    ->where('activity_reservation_data.pass_status','1')
                    ->get();
                foreach($permissionResultTmp as $k=>$v)
                {
                    array_push($permissionResult,$v['permission_id']);
                }
                //註記使用者沒有權限的時段
                foreach($sectionResult as $k=>$v)
                {
                    if(in_array($v['section_type'],$instrumentPermission) && in_array($v['section_type'],$permissionResult))
                    {//此段只檢查白天夜間是否有權限
                        $sectionResult[$k]['can_use'] = 1;
                    }
                }
            }
            else
            {//未登入使用者
                //取得使用者通過的假日權限
                $permissionResult = array_keys(Config::get('data.permission'));
                $instrumentPermission = array_keys(Config::get('data.permission'));
                //註記使用者沒有權限的時段
                foreach($sectionResult as $k=>$v)
                {
                    $sectionResult[$k]['can_use'] = 1;
                }
            }

            //預約資料
            $reservationResult = DB::table('instrument_reservation_data')
                        ->select('member_data.name as member_name',
                                    'instrument_reservation_data.member_id',
                                    'instrument_reservation_data.reservation_dt',
                                    'instrument_reservation_data.reservation_section_id',
                                    'instrument_reservation_data.reservation_status',
                                    'instrument_reservation_data.attend_status'
                                )
                        ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                        ->where('instrument_reservation_data.instrument_id',$dataResult[0]['id'])
                        ->where('instrument_reservation_data.reservation_status','!=','2')
                        ->whereDate('instrument_reservation_data.reservation_dt','>=',$start_dt)
                        ->whereDate('instrument_reservation_data.reservation_dt','<=',$end_dt)
                        ->get();
            //計算所有本儀器所有預約次數
            $reservation_count = DB::table('instrument_reservation_data')
                ->where('instrument_id',$dataResult[0]['id'])
                ->where('reservation_status',1)
                ->whereNull('attend_status')
                ->where('member_id',User::id())
                ->count();

            //排休使用權限設定
            $vacationResult = array();
            $vacationResultTmp = DB::table('instrument_vacation')
                        ->select('vacation_type','vacation_dt')
                        ->whereDate('vacation_dt','>=',$start_dt)
                        ->whereDate('vacation_dt','<=',$end_dt)
                        ->where(function($query)use($dataResult){
                            $query->orWhere('instrument_id',$dataResult[0]['id']);
                            $query->orWhere('instrument_id','0');
                        })
                        ->get();
                
            foreach($vacationResultTmp as $k=>$v)
            {
                if($v['vacation_type'] == "1" || $v['vacation_type'] == "2")
                {//國定假日或臨時休假
                    if(!in_array("3",$permissionResult) || !in_array("3",$instrumentPermission))
                    {//使用者沒有假日權限則本日無法預約
                        array_push($vacationResult,$v['vacation_dt']);
                    }
                }
                else if($v['vacation_type'] == "4")
                {//長假期
                    if(!in_array("4",$permissionResult)|| !in_array("4",$instrumentPermission))
                    {//使用者沒有長假期權限限則本日無法預約
                        array_push($vacationResult,$v['vacation_dt']);
                    }
                }
                else if($v['vacation_type'] == "5")
                {//保養維修
                    array_push($vacationResult,$v['vacation_dt']);
                }
            }

            foreach($sectionResult as $k=>$v)
            {
                foreach($reservationResult as $k1=>$v1)
                {
                    if($v['id'] == $v1['reservation_section_id'])
                    {//比對各時段預約記錄
                        if(!isset($sectionResult[$k]['reservation_log']))
                        {//將各時段預約日期寫入時段中
                            $sectionResult[$k]['reservation_log'] = array();
                            $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                        }
                        else
                        {
                            if(!isset($sectionResult[$k]['reservation_log'][$v1['reservation_dt']]))
                            {
                                $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                            }
                            else if(isset($sectionResult[$k]['reservation_log'][$v1['reservation_dt']]) && $v1['member_id'] == User::Id())
                            {
                                $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                            }
                        }
                            
                    }
                }
            }
            
            //寫入預約取消最後期限
            $dataResult[0]['cancel_limit_dt'] = date('Y-m-d',strtotime("+".$dataResult[0]['cancel_limit']." days",strtotime(date('Y-m-d'))));
            /*
            //寫入預約取消最後期限
            $dataResult[0]['cancel_limit_dt'] = date('Y-m-d',strtotime("+".$dataResult[0]['cancel_limit']." days",strtotime(date('Y-m-d'))));
            //開放時段
            $instrumentPermission = User::get('instrumentPermission');
            $sectionResult = array();
            $sectionResultTmp = DB::table('instrument_section_set')
                        ->select('instrument_section.section_type',
                                    'instrument_section.id',
                                    DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                    DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                        ->leftJoin('instrument_section','instrument_section_set.instrument_section_id','=','instrument_section.id')
                        ->where('instrument_section_set.instrument_data_id',$dataResult[0]['id'])
                        ->get();
            //取得使用者通過的假日權限
            $permissionResult = array();
            $permissionResultTmp = DB::table('activity_reservation_data')
                ->select('activity_instrument.permission_id')
                ->leftJoin('activity_instrument','activity_reservation_data.activity_id','=','activity_instrument.activity_id')
                ->where('activity_reservation_data.member_id',User::Id())
                ->where('activity_instrument.instrument_id',$dataResult[0]['id'])
                ->where('activity_reservation_data.pass_status','1')
                ->get();
            foreach($permissionResultTmp as $k=>$v)
            {
                array_push($permissionResult,$v['permission_id']);
            }

            //非使用者有權限的時段則剔除
            foreach($sectionResultTmp as $k=>$v)
            {
                if(in_array($v['section_type'],$instrumentPermission) && in_array($v['section_type'],$permissionResult))
                {
                    array_push($sectionResult,$v);
                }
            }

            //預約資料
            $reservationResult = DB::table('instrument_reservation_data')
                        ->select('member_data.name as member_name',
                                    'instrument_reservation_data.member_id',
                                    'instrument_reservation_data.reservation_dt',
                                    'instrument_reservation_data.reservation_section_id',
                                    'instrument_reservation_data.reservation_status',
                                    'instrument_reservation_data.attend_status'
                                )
                        ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                        ->where('instrument_reservation_data.instrument_id',$dataResult[0]['id'])
                        ->where('instrument_reservation_data.reservation_status','!=','2')
                        ->whereDate('instrument_reservation_data.reservation_dt','>=',$start_dt)
                        ->whereDate('instrument_reservation_data.reservation_dt','<=',$end_dt)
                        ->get();
            //計算所有本儀器所有預約次數
            $reservation_count = DB::table('instrument_reservation_data')
                ->where('instrument_id',$dataResult[0]['id'])
                ->where('reservation_status',1)
                ->whereNull('attend_status')
                ->where('member_id',User::id())
                ->count();

            //排休使用權限設定
            $vacationResult = array();
            $vacationResultTmp = DB::table('instrument_vacation')
                        ->select('vacation_type','vacation_dt')
                        ->whereDate('vacation_dt','>=',$start_dt)
                        ->whereDate('vacation_dt','<=',$end_dt)
                        ->where(function($query)use($dataResult){
                            $query->orWhere('instrument_id',$dataResult[0]['id']);
                            $query->orWhere('instrument_id','0');
                        })
                        ->get();
            
            foreach($vacationResultTmp as $k=>$v)
            {
                if($v['vacation_type'] == "1" || $v['vacation_type'] == "2")
                {
                    if(!in_array("3",$permissionResult))
                    {
                        array_push($vacationResult,$v['vacation_dt']);
                    }
                }
                else if($v['vacation_type'] == "4")
                {
                    if(!in_array("4",$permissionResult))
                    {
                        array_push($vacationResult,$v['vacation_dt']);
                    }
                }
            }

            foreach($sectionResult as $k=>$v)
            {
                foreach($reservationResult as $k1=>$v1)
                {
                    if($v['id'] == $v1['reservation_section_id'])
                    {
                        if(!isset($sectionResult[$k]['reservation_log']))
                        {
                            $sectionResult[$k]['reservation_log'] = array();
                            $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                        }
                        else
                        {
                            if(!isset($sectionResult[$k]['reservation_log'][$v1['reservation_dt']]))
                            {
                                $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                            }
                            else if(isset($sectionResult[$k]['reservation_log'][$v1['reservation_dt']]) && $v1['member_id'] == User::Id())
                            {
                                $sectionResult[$k]['reservation_log'][$v1['reservation_dt']] = $v1;
                            }
                        }
                        
                    }
                }
            }*/
        }
        
        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('sectionResult', $sectionResult);
        $this->view->with('start_dt', $start_dt);
        $this->view->with('start_dt_org', $start_dt_org);
        $this->view->with('end_dt', $end_dt);
        $this->view->with('total_start_dt', $total_start_dt);
        $this->view->with('total_end_dt', $total_end_dt);
        $this->view->with('start_dt_next', $start_dt_next);
        $this->view->with('end_dt_next', $end_dt_next);
        $this->view->with('start_dt_prev', $start_dt_prev);
        $this->view->with('end_dt_prev', $end_dt_prev);
        $this->view->with('id', Route::input('id', '0-0'));
        $this->view->with('search_date', $search_date);
        $this->view->with('vacationResult', $vacationResult);
        $this->view->with('reservation_count', $reservation_count);

        return $this->view;
    }

    ##

    public function ajax_reservation() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    
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

        if(User::id() == null)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('請登入後再進行預約');
            $this->view['login'] = 0;
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = explode('_',Request::input('id'));
                if($id[0] == 1)
                {//預約
                    $count = DB::table('instrument_reservation_data')
                        ->select('instrument_reservation_data_id')
                        ->where('instrument_id',$id[3])
                        ->where('reservation_dt',$id[2])
                        ->where('reservation_section_id',$id[1])
                        ->count();
                    if($count == 0)
                    {//預約
                        $reservation_status = 1;
                    }
                    else
                    {//候補
                        $reservation_status = 0;
                    }
                    //檢查是否已經在預約中
                    $member_count = DB::table('instrument_reservation_data')
                        ->select('instrument_reservation_data_id')
                        ->where('instrument_id',$id[3])
                        ->where('reservation_dt',$id[2])
                        ->where('reservation_section_id',$id[1])
                        ->where('member_id',User::Id())
                        ->count();
                    if($member_count == 0)
                    {
                        $reservation_id = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data_id')
                            ->where('create_date',date('Y-m-d'))
                            ->orderBy('instrument_reservation_data_id','desc')
                            ->limit(1)
                            ->get();
                        if(!isset($reservation_id[0]['instrument_reservation_data_id']))
                        {
                            $reservation_id = 0;
                        }
                        else
                        {
                            $reservation_id = $reservation_id[0]['instrument_reservation_data_id'];
                        }
                        $reservation_id = intval($reservation_id)+1;

                        DB::table('instrument_reservation_data')
                            ->insert(array(
                                'instrument_reservation_data_id'=>$reservation_id,
                                'create_date'=>date('Y-m-d'),
                                'uid'=>'-',
                                'salt'=>'-',
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s'),
                                'instrument_id'=>$id[3],
                                'member_id'=>User::Id(),
                                'reservation_dt'=>$id[2],
                                'reservation_section_id'=>$id[1],
                                'reservation_status'=>$reservation_status,
                                'remark'=>''
                            ));
                        //製作uid以及salt
                        $date = date('Y-m-d H:i:s').$reservation_id.date('Y-m-d');
                        $salt = substr(md5($date),5,5);
                        $uid = md5($salt.$date);
                        
                        DB::table('instrument_reservation_data')
                            ->where('instrument_reservation_data_id',$reservation_id)
                            ->where('create_date',date('Y-m-d'))
                            ->update(['uid'=>$uid,
                                        'salt'=>$salt
                            ]);
                        $result_after = DB::table('instrument_reservation_data')
                                        ->where('instrument_reservation_data_id',$reservation_id)
                                        ->where('create_date',date('Y-m-d'))
                                        ->get();
                        DBProcedure::writeLog([
                            'table' => 'instrument_reservation_data',
                            'operator' => DBOperator::OP_INSERT,
                            'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                            'admin_id' => User::id()
                        ]);

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
                {//取消預約
                    DB::table('instrument_reservation_data')
                        ->where('instrument_id',$id[3])
                        ->where('reservation_dt',$id[2])
                        ->where('reservation_section_id',$id[1])
                        ->where('member_id',User::Id())
                        ->update(['reservation_status'=>'2'
                        ]);
                    //取得個人資料
                    $member = DB::table('member_data')
                        ->select('member_data.name','member_data.email','system_pi_list.name as pi_name')
                        ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                        ->where('member_data.id',User::Id())
                        ->first();
                    //取得取消時間區間
                    $section = DB::table('instrument_section')
                        ->select(DB::raw('DATE_FORMAT(start_time, "%H:%i") as start_time'),
                                    DB::raw('DATE_FORMAT(end_time, "%H:%i") as end_time'))
                        ->where('id',$id[1])
                        ->first();
                    //寄信通知管理員
                    if(count($member) != 0 && count($section) != 0)
                    {
                        $instrument_admin = DB::table('instrument_data')
                            ->select('instrument_admin.name','instrument_admin.email','instrument_data.name as instrument_name')
                            ->leftJoin('instrument_admin','instrument_admin.instrument_data_id','=','instrument_data.id')
                            ->where('instrument_data.cancel_notice','1')
                            ->where('instrument_data.id',$id[3])
                            ->get();
                        foreach($instrument_admin as $k=>$v)
                        {
                            $dataResult = array('user'=>$v['name'],'instrument'=>$v['instrument_name'],'cancel_member'=>$member,'cancel_date'=>$id[2],'cancel_section'=>$section);
                            Mail::send('emails.cancel_notice', [
                                        'dataResult' => $dataResult,
                                            ], function ($m)use($v) {
                                        $m->to($v['email'], '');
                                        $m->subject("預約取消通知訊息");
                            });
                        }
                    }

                    $this->view['msg'] = trans('message.success.cancel');
                }
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
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
