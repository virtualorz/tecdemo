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

class InstrumentController extends Controller {

    public function index() {
        
        $keyword = Request::input('keyword', '');
        $searchResult = array();
        $listResult = array();
        if($keyword != "")
        {
            $searchResult = DB::table('instrument_data')
                            ->orWhere('instrument_type.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.instrument_id','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.name','like','%'.$keyword.'%')
                            ->orWhere('instrument_data.function','like','%'.$keyword.'%')
                            ->select('instrument_data.uid',
                                            'instrument_data.salt',
                                            'instrument_type.name as type_name',
                                            'instrument_data.instrument_id',
                                            'instrument_data.name',
                                            'instrument_data.function',
                                            'instrument_site.name as site_name',
                                            'instrument_admin.name as admin_name')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->leftJoin('instrument_admin','instrument_admin.instrument_data_id','=','instrument_data.id')
                            ->groupBy('instrument_data.id')
                            ->orderBy('instrument_data.id','desc')
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
                                            'instrument_admin.name as admin_name')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->leftJoin('instrument_admin','instrument_admin.instrument_data_id','=','instrument_data.id')
                            ->groupBy('instrument_data.id')
                            ->orderBy('instrument_data.instrument_type_id','desc')
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
        $total_start_dt = date('Y.m.d',strtotime('-2 months',strtotime(date('Y-m-d'))));
        $total_end_dt = date('Y.m.d',strtotime('+2 months',strtotime(date('Y-m-d'))));
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
                $start_dt = date('Y.m.d',strtotime('-'.$week.' days',strtotime(date('Y-m-d'))));
                $start_dt_org = date('Y-m-d',strtotime('-'.$week.' days',strtotime(date('Y-m-d'))));
                $end_dt = date('Y.m.d',strtotime('+6 days',strtotime($start_dt_org)));
                if(strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
                {
                    $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                    $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
                }
                if(strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
                {
                    $start_dt_prev = date('Y-m-d',strtotime('-7 days',strtotime($start_dt_org)));
                    $end_dt_prev = date('Y-m-d',strtotime('-1 days',strtotime($start_dt_org)));
                }
            }
            else
            {
                $start_dt_org = date('Y-m-d',strtotime($start_dt));
                $start_dt = date('Y.m.d',strtotime($start_dt));
                $end_dt = date('Y.m.d',strtotime('+6 days',strtotime($start_dt_org)));
                if(strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
                {
                    $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                    $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
                }
                if(strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
                {
                    $start_dt_prev = date('Y-m-d',strtotime('-7 days',strtotime($start_dt_org)));
                    $end_dt_prev = date('Y-m-d',strtotime('-1 days',strtotime($start_dt_org)));
                }
            }
        }
        else
        {
            $week = date('w',strtotime($search_date));
            $start_dt = date('Y.m.d',strtotime('-'.$week.' days',strtotime($search_date)));
            $start_dt_org = date('Y-m-d',strtotime('-'.$week.' days',strtotime($search_date)));
            $end_dt = date('Y.m.d',strtotime('+6 days',strtotime($start_dt_org)));
            if(strtotime('+7 days',strtotime($start_dt_org)) <= strtotime($total_end_dt_org))
            {
                $start_dt_next = date('Y-m-d',strtotime('+7 days',strtotime($start_dt_org)));
                $end_dt_next = date('Y-m-d',strtotime('+13 days',strtotime($start_dt_org)));
            }
            if(strtotime('-1 days',strtotime($start_dt_org)) >= strtotime($total_start_dt_org))
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
                                        'instrument_data.function')
                    ->get();
        $sectionResult = array();
        $reservationResult = array();
        if (count($dataResult) > 0)
        {
            //開放時段
            $sectionResult = DB::table('instrument_section_set')
                        ->select('instrument_section.id',
                                    DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                    DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                        ->leftJoin('instrument_section','instrument_section_set.instrument_section_id','=','instrument_section.id')
                        ->where('instrument_section_set.instrument_data_id',$dataResult[0]['id'])
                        ->get();
            //預約資料
            $reservationResult = DB::table('instrument_reservation_data')
                        ->select('member_data.name as member_name',
                                    'instrument_reservation_data.reservation_dt',
                                    'instrument_reservation_data.reservation_section_id',
                                    'instrument_reservation_data.reservation_status',
                                    'instrument_reservation_data.attend_status'
                                )
                        ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                        ->where('instrument_reservation_data.instrument_id',$dataResult[0]['id'])
                        ->whereDate('instrument_reservation_data.reservation_dt','>=',$start_dt)
                        ->whereDate('instrument_reservation_data.reservation_dt','<=',$end_dt)
                        ->get();
            foreach($sectionResult as $k=>$v)
            {
                foreach($reservationResult as $k1=>$v1)
                {
                    if($v['id'] == $v1['reservation_section_id'])
                    {
                        if(!isset($sectionResult[$k]['reservation_log']))
                        {
                            $sectionResult[$k]['reservation_log'] = array();
                        }
                        array_push($sectionResult[$k]['reservation_log'],$v1);
                    }
                }
            }
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
        if(Request::input('reservation') == 1)
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
