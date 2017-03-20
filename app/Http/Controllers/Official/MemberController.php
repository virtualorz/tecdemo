<?php

namespace App\Http\Controllers\Official;

//
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Log;
use User;
use Validator;
use PDF;
use Cache;
use Session;

class MemberController extends Controller {

    public function index() {
        $noticeResult = DB::table('member_notice_log')
                            ->select('member_notice_log.member_notice_log_id',
                                        DB::raw('DATE_FORMAT(member_notice_log.created_at, "%Y/%m/%d") as created_at'),
                                        'member_notice_log.uid',
                                        'member_notice_log.salt',
                                        'member_notice_log.title',
                                        'member_notice_log.email',
                                        'member_notice_log.is_read',
                                        'member_admin.name as create_admin_name')
                            ->leftJoin('member_admin','member_notice_log.create_admin_id','=','member_admin.id')
                            ->where('member_data_id','=',User::Id())
                            ->orderBy('created_at','desc')
                            ->take(5)
                            ->get();
        
        $paymentResult = DB::table('payment_data')
                            ->select('payment_data.pay_year',
                                        'payment_data.pay_month',
                                        'payment_data.uid',
                                        'payment_data.salt',
                                        'payment_data.total',
                                        'payment_data.print_member_id',
                                        'payment_data.create_admin_id',
                                        DB::raw('count(payment_paylog.payment) as payment_count'),
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('payment_paylog',function($join){
                                $join->on('payment_data.pi_list_id','=','payment_paylog.pi_list_id');
                                $join->on('payment_data.pay_year','=','payment_paylog.pay_year');
                                $join->on('payment_data.pay_month','=','payment_paylog.pay_month');
                            })
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->where('payment_data.pi_list_id','=',User::get('pi_list_id'))
                            ->whereNull('payment_data.print_member_id')
                            ->groupBy('payment_data.pi_list_id')
                            ->groupBy('payment_data.pay_year')
                            ->groupBy('payment_data.pay_month')
                            ->orderBy('payment_data.created_at','desc')
                            ->get();
        
        $activityResult = DB::table('activity_reservation_data')
                            ->select('activity_data.id',
                                        'activity_data.uid',
                                        'activity_data.salt',
                                        'activity_data.activity_id',
                                        'activity_reservation_data.created_at',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->where('activity_reservation_data.reservation_status','=',1)
                            ->where('activity_reservation_data.attend_status','=',0)
                            ->whereDate('activity_data.end_dt','>',date('Y-m-d'))
                            ->orderBy('activity_data.start_dt','asc')
                            ->take(5)
                            ->get();

        $instrumentResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.uid',
                                        'instrument_reservation_data.salt',
                                        'instrument_reservation_data.reservation_dt as reservation_dt_org',
                                        DB::raw('DATE_FORMAT(instrument_reservation_data.reservation_dt, "%Y/%m/%d") as reservation_dt'),
                                        'instrument_reservation_data.instrument_reservation_data_id',
                                        'instrument_reservation_data.create_date',
                                        'instrument_data.name',
                                        'instrument_data.uid as instrument_uid',
                                        'instrument_data.salt as instrument_salt',
                                        'instrument_data.cancel_limit',
                                        DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                        DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->where('instrument_reservation_data.member_id','=',User::Id())
                            ->whereNull('instrument_reservation_data.attend_status')
                            ->whereDate('instrument_reservation_data.reservation_dt','>=',date('Y-m-d'))
                            ->where(function($query){
                                $query->OrWhere('instrument_reservation_data.reservation_status','=',1);
                                $query->OrWhere('instrument_reservation_data.reservation_status','=',0);
                            })
                            ->orderBy('instrument_reservation_data.reservation_dt','asc')
                            ->take(5)
                            ->get();
        foreach($instrumentResult as $k=>$v)
        {
            //寫入預約取消最後期限
            $instrumentResult[$k]['cancel_limit_dt'] = date('Y-m-d',strtotime("+".$v['cancel_limit']." days",strtotime(date('Y-m-d')))); 
        }

        $this->view->with('noticeResult', $noticeResult);
        $this->view->with('paymentResult', $paymentResult);
        $this->view->with('activityResult', $activityResult);
        $this->view->with('instrumentResult', $instrumentResult);
        
        return $this->view;
    }

    public function basic() {
        $id = User::Id();
        $dataResult = DB::table('member_data')
                            ->select('member_data.*',
                                        DB::raw('DATE_FORMAT(member_data.start_dt, "%Y/%m/%d") as start_dt'),
                                        'member_data.start_dt as start_dt_org',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('member_admin','member_data.create_admin_id','=','member_admin.id')
                            ->where('member_data.id',$id)
                            ->get();
        $permissionResult = array();
        $permissionResultTmp = DB::table('member_permission')
                                    ->where('member_data_id',$id)
                                    ->select('permission')
                                    ->get();
        foreach($permissionResultTmp as $k=>$v)
        {
            array_push($permissionResult,$v['permission']);
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('permissionResult', $permissionResult);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('member_typeResult', Config::get('data.id_type'));
        
        return $this->view;
    }

    public function print_data() {
        $memberResult = DB::table('member_data')
                            ->select('member_data.*',
                                        DB::raw('DATE_FORMAT(member_data.start_dt, "%Y/%m/%d") as start_dt'),
                                        'member_data.start_dt as start_dt_org',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('member_admin','member_data.create_admin_id','=','member_admin.id')
                            ->where('member_data.id','=',User::Id())
                            ->first();

        $pdf_name = md5(date('Y-m-d H:i:s'));

        $pdf = PDF::loadView('Official.elements.apply_print', array(
            'memberResult'=>$memberResult
            ));
        $pdf->setTemporaryFolder(public_path().'/files/tmp/');
        return $pdf->download($pdf_name.'.pdf');
    }

    public function print_register_data() {
        $memberResult = Session::get('cache_register',array());
        if(count($memberResult) !=0)
        {
            $organize = DB::table('system_organize')
                ->select('name')
                ->where('id',$memberResult['organize_id'])
                ->first();
            if(count($organize) !=0)
            {
                $memberResult['organize_name'] = $organize['name'];
            }
            $department = DB::table('system_department')
                ->select('name')
                ->where('id',$memberResult['department_id'])
                ->first();
            if(count($department) !=0)
            {
                $memberResult['department_name'] = $department['name'];
            }
            $memberResult['card_id_number'] = '';
            $pdf_name = md5(date('Y-m-d H:i:s'));

            $pdf = PDF::loadView('Official.elements.apply_print', array(
                'memberResult'=>$memberResult
                ));
            $pdf->setTemporaryFolder(public_path().'/files/tmp/');
            return $pdf->download($pdf_name.'.pdf');
        }
    }

    ##

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'password' => 'string|required|max:200|same:passwordR',
                    'phone' => 'string|required|max:24',
                    'lab_phone' => 'string|required|max:24',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $id = User::id();

                $result_before = DB::table('member_data')
                                    ->where('id',$id)
                                    ->get();
                if($result_before[0]['password'] == Request::input('password'))
                {
                    DB::table('member_data')
                        ->where('id',$id)
                        ->update(['title'=>Request::input('title'),
                                    'phone'=>Request::input('phone'),
                                    'lab_phone'=>Request::input('lab_phone')
                        ]);
                }
                else
                {
                    DB::table('member_data')
                        ->where('id',$id)
                        ->update(['title'=>Request::input('title'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'lab_phone'=>Request::input('lab_phone')
                        ]);
                }
                $result_after = DB::table('member_data')
                                    ->where('id',$id)
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => $id
                ]);

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
}
