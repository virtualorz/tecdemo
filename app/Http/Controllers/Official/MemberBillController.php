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

class MemberBillController extends Controller {

    public function index() {
        //取得同實驗室人員
        $memberResult = array();
        $memberResultTmp = DB::table('member_data')
            ->select('id')
            ->where('pi_list_id',User::Id())
            ->get();
        foreach($memberResultTmp as $k=>$v)
        {
            array_push($memberResult,$v['id']);
        }
        //處理帳單單據
        $reservation_data = DB::table('instrument_reservation_data')
            ->select('instrument_reservation_data.*','member_data.pi_list_id')
            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
            ->whereNotNull('reservation_status')
            ->where('attend_status',1)
            ->whereNotNull('update_admin_id')
            ->whereNull('in_bill')
            ->whereIn('member_id',$memberResult)
            ->whereDate('use_dt_start','<', date('Y-m-d',mktime(0, 0, 0, date('m'), 1, date('Y'))))
            ->get();
        
        foreach($reservation_data as $k=>$v)
        {
            $pay_year = date("Y",strtotime($v['use_dt_start']));
            $pay_month = date("m",strtotime($v['use_dt_start']));
            //確認本月帳單是否存在
            $payment_data = DB::table('payment_data')
                ->select('pi_list_id','pay_year','pay_month')
                ->where('pi_list_id',$v['pi_list_id'])
                ->where('pay_year',$pay_year)
                ->where('pay_month',$pay_month)
                ->get();
            $param = array($v,$pay_year,$pay_month);

            if(count($payment_data) == 0)
            {//帳單未建立
                try {
                    DB::transaction(function()use($param){
                        //製作uid以及salt
                        $date = $param[0]['pi_list_id'].$param[1].$param[2];
                        $salt = substr(md5($date),5,5);
                        $uid = md5($salt.$date);

                        DB::table('payment_data')
                                ->insert(
                                    array('pi_list_id'=>$param[0]['pi_list_id'],
                                            'pay_year'=>$param[1],
                                            'pay_month'=>$param[2],
                                            'uid'=>$uid,
                                            'salt'=>$salt,
                                            'created_at'=>date('Y-m-d H:i:s'),
                                            'remark' => ''
                                    )
                                );
                    });

                } catch (DBProcedureException $e) {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.database');
                    $this->view['detail'][] = $e->getMessage();

                    return $this->view;
                }
            }
            //寫入使用紀錄
            try {
                DB::transaction(function()use($param){
                    $reservation_log_id = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log_id')
                            ->where('pi_list_id',$param[0]['pi_list_id'])
                            ->where('pay_year',$param[1])
                            ->where('pay_month',$param[2])
                            ->orderBy('payment_reservation_log_id','desc')
                            ->first();
                    if(!isset($reservation_log_id[0]['payment_reservation_log_id']))
                    {
                        $reservation_log_id = 0;
                    }
                    else
                    {
                        $reservation_log_id = $reservation_log_id[0]['payment_reservation_log_id'];
                    }
                    $reservation_log_id = intval($reservation_log_id) +1;
                    
                    DB::table('payment_reservation_log')
                            ->insert(
                                array('payment_reservation_log_id'=>$reservation_log_id,
                                        'pi_list_id'=>$param[0]['pi_list_id'],
                                        'pay_year'=>$param[1],
                                        'pay_month'=>$param[2],
                                        'instrument_reservation_data_id'=>$param[0]['instrument_reservation_data_id'],
                                        'create_date'=>$param[0]['create_date']
                                )
                            );
                });
                //更新標注預約紀錄已寫入帳單
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$param[0]['instrument_reservation_data_id'])
                    ->where('create_date',$param[0]['create_date'])
                    ->update(['in_bill'=>'1'
                    ]);


            } catch (DBProcedureException $e) {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.database');
                $this->view['detail'][] = $e->getMessage();

                return $this->view;
            }
        }

        //顯示搜尋結果
        $month = Request::input('month', '');
        $pay = Request::input('pay', '');
        $pay_status = Request::input('pay_status', '');
        

        $listResult = DB::table('payment_data');

        if($month != "")
        {
            $tmp = explode('-',$month);
            $listResult->where('payment_data.pay_year','=',$tmp[0]);
            $listResult->where('payment_data.pay_month','=',$tmp[1]);
        }
        if($pay != "")
        {
            $listResult->where('payment_data.total','<=',$pay);
        }
        if($pay_status != "")
        {
            if($pay_status == "1")
            {
                $listResult->whereNotNull('payment_data.create_admin_id');
            }
            else if($pay_status == "0")
            {
                $listResult->whereNull('payment_data.create_admin_id');
            }
        }

        $listResult = $listResult->select('payment_data.pay_year',
                                            'payment_data.pay_month',
                                            'payment_data.uid',
                                            'payment_data.salt',
                                            'payment_data.total',
                                            'payment_data.print_member_id',
                                            'payment_data.create_admin_id',
                                            'system_pi_list.name as pi_name')
                                    ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                                    ->where('pi_list_id','=',User::get('pi_list_id'))
                                    ->orderBy('payment_data.pay_year','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        
        return $this->view;
    }

    public function detail() {
        $id = explode('-',Route::input('id', '0-0'));
        $dataResult = DB::table('payment_data')
                            ->select('payment_data.*','member_data.name as created_admin_name','system_department.name as department_name','system_organize.name as organize_name')
                            ->leftJoin('member_data','payment_data.create_admin_id','=','member_data.id')
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                            ->where('payment_data.uid',$id[0])
                            ->where('payment_data.salt',$id[1])
                            ->get();
        $reservationlogResult = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log.payment_reservation_log_id',
                                    'payment_reservation_log.pi_list_id',
                                    'payment_reservation_log.pay_year',
                                    'payment_reservation_log.pay_month',
                                    'payment_reservation_log.discount_JSON',
                                    'instrument_reservation_data.*',
                                    DB::raw('DATE_FORMAT(instrument_reservation_data.create_date, "%Y%m") as create_date_ym'),
                                    'instrument_data.name as instrument_name',
                                    'member_data.name as member_name',
                                    'member_data.type as member_type')
                            ->leftJoin('instrument_reservation_data', function ($join) {
                                $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                            })
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->where('payment_reservation_log.pi_list_id',$dataResult[0]['pi_list_id'])
                            ->where('payment_reservation_log.pay_year',$dataResult[0]['pay_year'])
                            ->where('payment_reservation_log.pay_month',$dataResult[0]['pay_month'])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        foreach($reservationlogResult as $k=>$v)
        {
            $reservationlogResult[$k]['date'] = date('Y.m.d',strtotime($v['use_dt_start']));
            $reservationlogResult[$k]['use_dt_start'] = date('H:i',strtotime($v['use_dt_start']));
            $reservationlogResult[$k]['use_dt_end'] = date('H:i',strtotime($v['use_dt_end']));

            $reservationlogResult[$k]['discount_JSON'] = json_decode($v['discount_JSON'],true);
            $reservationlogResult[$k]['supplies_JOSN'] = json_decode($v['supplies_JOSN'],true);
            if($reservationlogResult[$k]['supplies_JOSN'] != '')
            {
                foreach($reservationlogResult[$k]['supplies_JOSN'] as $k1=>$v1)
                {
                    $supplies = DB::table('instrument_supplies')
                        ->select('name')
                        ->where('id',$v1['id'])
                        ->first();
                    if(isset($supplies['name']))
                    {
                        $reservationlogResult[$k]['supplies_JOSN'][$k1]['name'] = $supplies['name'];
                    }
                }
            }
            else
            {
                $reservationlogResult[$k]['supplies_JOSN'] = array();
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('reservationlogResult', $reservationlogResult);
        $this->view->with('discount_type', Config::get('data.discount_type'));
        $this->view->with('id', Route::input('id', '0-0'));
        
        return $this->view;
    }

    public function print_bill() {
        $id = explode('-',Route::input('id', '0-0'));
        $dataResult = DB::table('payment_data')
                            ->select('payment_data.*','member_data.name as created_admin_name','system_department.name as department_name','system_organize.name as organize_name','system_pi_list.name as pi_name')
                            ->leftJoin('member_data','payment_data.create_admin_id','=','member_data.id')
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                            ->where('payment_data.uid',$id[0])
                            ->where('payment_data.salt',$id[1])
                            ->get();
        $reservationlogResult = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log.payment_reservation_log_id',
                                    'payment_reservation_log.pi_list_id',
                                    'payment_reservation_log.pay_year',
                                    'payment_reservation_log.pay_month',
                                    'payment_reservation_log.discount_JSON',
                                    'instrument_reservation_data.*',
                                    DB::raw('DATE_FORMAT(instrument_reservation_data.create_date, "%Y%m") as create_date_ym'),
                                    'instrument_data.name as instrument_name',
                                    'member_data.name as member_name',
                                    'member_data.type as member_type')
                            ->leftJoin('instrument_reservation_data', function ($join) {
                                $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                            })
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->where('payment_reservation_log.pi_list_id',$dataResult[0]['pi_list_id'])
                            ->where('payment_reservation_log.pay_year',$dataResult[0]['pay_year'])
                            ->where('payment_reservation_log.pay_month',$dataResult[0]['pay_month'])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        foreach($reservationlogResult as $k=>$v)
        {
            $reservationlogResult[$k]['date'] = date('Y.m.d',strtotime($v['use_dt_start']));
            $reservationlogResult[$k]['use_dt_start'] = date('H:i',strtotime($v['use_dt_start']));
            $reservationlogResult[$k]['use_dt_end'] = date('H:i',strtotime($v['use_dt_end']));

            $reservationlogResult[$k]['discount_JSON'] = json_decode($v['discount_JSON'],true);
            $reservationlogResult[$k]['supplies_JOSN'] = json_decode($v['supplies_JOSN'],true);
            if($reservationlogResult[$k]['supplies_JOSN'] != '')
            {
                foreach($reservationlogResult[$k]['supplies_JOSN'] as $k1=>$v1)
                {
                    $supplies = DB::table('instrument_supplies')
                        ->select('name')
                        ->where('id',$v1['id'])
                        ->first();
                    if(isset($supplies['name']))
                    {
                        $reservationlogResult[$k]['supplies_JOSN'][$k1]['name'] = $supplies['name'];
                    }
                }
            }
            else
            {
                $reservationlogResult[$k]['supplies_JOSN'] = array();
            }
        }

        $pdf_name = md5(date('Y-m-d H:i:s'));

        $pdf = PDF::loadView('Official.elements.payment_print', array(
            'dataResult'=>$dataResult[0],
            'reservationlogResult'=>$reservationlogResult,
            'discount_type'=>Config::get('data.discount_type'),
            ));
        $pdf->setTemporaryFolder(env('DIR_WEB').'files\\tmp\\');
        return $pdf->download($pdf_name.'.pdf');
    }

    ##

    public function ajax_reg() {
        $validator = Validator::make(Request::all(), [
                    'reason' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){

                DB::table('activity_reg')
                        ->insert(array(
                            'created_at'=>date('Y-m-d H:i:s'),
                            'activity_id'=>Request::input('id'),
                            'member_id'=>User::id(),
                            'reason'=>Request::input('reason')
                        ));

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.reg');
        return $this->view;
    }

    public function ajax_cancel() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            $id = explode('_',Request::input('id', '0_0'));

                $result_before = DB::table('activity_reservation_data')
                                    ->where('activity_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('created_at',$id[1])
                                    ->get();
                DB::table('activity_reservation_data')
                    ->where('activity_id',$id[0])
                    ->where('member_id',User::id())
                    ->where('created_at',$id[1])
                    ->update(['reservation_status'=>'0']);
                
                $result_after = DB::table('activity_reservation_data')
                                    ->where('activity_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('created_at',$id[1])
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_reservation_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => User::id()
                ]);
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

        
        $this->view['msg'] = trans('message.success.cancel');
        return $this->view;
    }
}
