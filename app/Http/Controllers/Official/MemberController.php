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

class MemberController extends Controller {

    public function index() {
        $noticeResult = DB::table('member_notice_log')
                            ->select('member_notice_log.uid',
                                        'member_notice_log.salt',
                                        DB::raw('DATE_FORMAT(member_notice_log.created_at, "%Y.%m.%d") as created_at'),
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
                                        DB::raw('SUM(payment_paylog.payment) as payment_sum'),
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('payment_paylog',function($join){
                                $join->on('payment_data.pi_list_id','=','payment_paylog.pi_list_id');
                                $join->on('payment_data.pay_year','=','payment_paylog.pay_year');
                                $join->on('payment_data.pay_month','=','payment_paylog.pay_month');
                            })
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->where('payment_data.pi_list_id','=',User::get('pi_list_id'))
                            ->whereNull('payment_data.print_member_id')
                            ->orderBy('payment_data.created_at','desc')
                            ->get();
        
        $activityResult = DB::table('activity_reservation_data')
                            ->select('activity_data.uid',
                                        'activity_data.salt',
                                        'activity_data.activity_id',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->orderBy('activity_data.start_dt','desc')
                            ->take(5)
                            ->get();

        $instrumentResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.uid',
                                        'instrument_reservation_data.salt',
                                        'instrument_reservation_data.reservation_dt',
                                        'instrument_data.name',
                                        DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                        DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->OrWhere('instrument_reservation_data.reservation_status','=',1)
                            ->OrWhere('instrument_reservation_data.reservation_status','=',0)
                            ->whereNull('instrument_reservation_data.attend_status')
                            ->where('instrument_reservation_data.member_id','=',User::Id())
                            ->orderBy('instrument_reservation_data.reservation_dt','desc')
                            ->take(5)
                            ->get();

        $this->view->with('noticeResult', $noticeResult);
        $this->view->with('paymentResult', $paymentResult);
        $this->view->with('activityResult', $activityResult);
        $this->view->with('instrumentResult', $instrumentResult);
        
        return $this->view;
    }
}
