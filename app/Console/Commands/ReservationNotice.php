<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use DB;
use Mail;
use Log;

class ReservationNotice extends Command
{
    
    public function handle()
    {
        //取得24小時候預約時段名單
        $tomorrow = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
        $notice_list = DB::table('instrument_reservation_data')
                    ->select('member_data.name as member_name','member_data.email','instrument_reservation_data.reservation_dt','instrument_section.start_time','instrument_data.name')
                    ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                    ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                    ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                    ->whereDate('instrument_reservation_data.reservation_dt','=',$tomorrow)
                    ->where('instrument_reservation_data.reservation_status','1')
                    ->where('instrument_data.notice','1')
                    ->get();
        foreach($notice_list as $k=>$v)
        {
            if( (strtotime($v['reservation_dt'].' '.$v['start_time']) - strtotime(date('Y-m-d H:i:s'))) >=82800 && (strtotime($v['reservation_dt'].' '.$v['start_time']) - strtotime(date('Y-m-d H:i:s'))) <= 86400)
            {
                $dataResult = array('user'=>$v['member_name'],'date'=> $v['reservation_dt'], 'instrument'=>$v['name']);
                Mail::queue('emails.instrument_use', [
                                'dataResult' => $dataResult,
                                    ], function ($m)use($v) {
                                $m->to($v['email'], '');
                                $m->subject("系統使用通知");
                });
                
            }
        }
    }
}
