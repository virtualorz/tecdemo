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

class InstrumentPaymentController extends Controller {

    public function index() {

        //處理帳單單據
        $reservation_data = DB::table('instrument_reservation_data')
            ->select('instrument_reservation_data.*','member_data.pi_list_id')
            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
            ->whereNotNull('reservation_status')
            ->where('attend_status',1)
            ->whereNotNull('update_admin_id')
            ->whereNull('in_bill')
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
        $name = Request::input('name', '');
        $card_id_number = Request::input('card_id_number', '');
        $department = Request::input('department', '');
        $page_id = Request::input('page_id', '');
        $member_type = Request::input('member_type', '');

        $listResult = DB::table('payment_data');
        $idResult = array();
        if($name != "")
        {
            $idResulttmp = DB::table('member_data')
                    ->select('pi_list_id')
                    ->where('name','like','%'.$name.'%')
                    ->get();
            
            foreach($idResulttmp as $k=>$v)
            {
                array_push($idResult,$v['pi_list_id']);
            }
        }
        if($card_id_number != "")
        {
            $idResulttmp = DB::table('member_data')
                    ->select('pi_list_id')
                    ->where('card_id_number',$card_id_number)
                    ->get();

            $idResult_i = array();
            foreach($idResulttmp as $k=>$v)
            {
                array_push($idResult_i,$v['pi_list_id']);
            }
            $idResult = array_merge($idResult,$idResult_i);
        }
        if($department != "")
        {
            $listResult->where('system_pi_list.department_id','=',$department);
        }
        if($page_id != "")
        {
            $page_id = explode('-',$page_id);
            if(count($page_id) > 1)
            {
                $listResult->where('payment_data.uid','=',$page_id[0]);
                $listResult->where('payment_data.salt','=',$page_id[1]);
            }
        }
        if($member_type != "")
        {
            $idResulttmp = DB::table('member_data')
                    ->select('pi_list_id')
                    ->where('type',$member_type)
                    ->get();

            $idResult_i = array();
            foreach($idResulttmp as $k=>$v)
            {
                array_push($idResult_i,$v['pi_list_id']);
            }
            $idResult = array_merge($idResult,$idResult_i);
        }
        if(count($idResult) !=0)
        {
            $listResult->whereIn('payment_data.pi_list_id',$idResult);
        }

        $listResult = $listResult->select('payment_data.pi_list_id',
                                            'payment_data.pay_year',
                                            'payment_data.pay_month',
                                            'system_organize.name as organize_name',
                                            'system_department.name as department_name',
                                            'payment_data.total',
                                            'payment_data.print_member_id',
                                            'payment_data.create_admin_id',
                                            'system_pi_list.name as pi_name')
                                    ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                                    ->leftJoin('system_organize','system_pi_list.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                                    ->orderBy('payment_data.pay_year','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $departmentResult = DB::table('system_department')
                                    ->select('system_department.id','system_department.name','system_organize.name as organnize_name')
                                    ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                                    ->orderBy('system_department.id','asc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('departmentResult', $departmentResult);
        $this->view->with('member_typeResult', Config::get('data.id_type'));

        return $this->view;
    }

    public function confirm() {
        $id = explode('_',Route::input('id', '0_0_0'));
        $dataResult = DB::table('payment_data')
                            ->select('payment_data.*','system_department.name as department_name','system_organize.name as organize_name')
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                            ->where('pi_list_id',$id[0])
                            ->where('pay_year',$id[1])
                            ->where('pay_month',$id[2])
                            ->get();
        $reservationlogResult = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log.payment_reservation_log_id',
                                    'payment_reservation_log.pi_list_id',
                                    'payment_reservation_log.pay_year',
                                    'payment_reservation_log.pay_month',
                                    'instrument_reservation_data.*',
                                    'instrument_data.name as instrument_name',
                                    'member_data.name as member_name',
                                    'member_data.type as member_type')
                            ->leftJoin('instrument_reservation_data', function ($join) {
                                $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                            })
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->where('payment_reservation_log.pi_list_id',$id[0])
                            ->where('payment_reservation_log.pay_year',$id[1])
                            ->where('payment_reservation_log.pay_month',$id[2])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        $suppliesResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.*')
                            ->where('enable','1')
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('reservationlogResult', $reservationlogResult);
        $this->view->with('suppliesResult', $suppliesResult);
        $this->view->with('discount_type', Config::get('data.discount_type'));

        return $this->view;
    }

    public function detail() {
        $id = explode('_',Route::input('id', '0_0_0'));
        $dataResult = DB::table('payment_data')
                            ->select('payment_data.*','member_data.name as created_admin_name','system_department.name as department_name','system_organize.name as organize_name')
                            ->leftJoin('member_data','payment_data.create_admin_id','=','member_data.id')
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                            ->where('payment_data.pi_list_id',$id[0])
                            ->where('payment_data.pay_year',$id[1])
                            ->where('payment_data.pay_month',$id[2])
                            ->get();
        $reservationlogResult = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log.payment_reservation_log_id',
                                    'payment_reservation_log.pi_list_id',
                                    'payment_reservation_log.pay_year',
                                    'payment_reservation_log.pay_month',
                                    'payment_reservation_log.discount_JSON',
                                    'payment_reservation_log.supplies_JOSN',
                                    'payment_reservation_log.supplies_total',
                                    'instrument_reservation_data.*',
                                    'instrument_data.name as instrument_name',
                                    'member_data.name as member_name',
                                    'member_data.type as member_type')
                            ->leftJoin('instrument_reservation_data', function ($join) {
                                $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                            })
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->where('payment_reservation_log.pi_list_id',$id[0])
                            ->where('payment_reservation_log.pay_year',$id[1])
                            ->where('payment_reservation_log.pay_month',$id[2])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        $suppliesResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.*')
                            ->where('enable','1')
                            ->get();
        
        foreach($reservationlogResult as $k=>$v)
        {
            $reservationlogResult[$k]['discount_JSON'] = json_decode($v['discount_JSON'],true);
            $reservationlogResult[$k]['supplies_JOSN'] = json_decode($v['supplies_JOSN'],true);
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('reservationlogResult', $reservationlogResult);
        $this->view->with('suppliesResult', $suppliesResult);
        $this->view->with('discount_type', Config::get('data.discount_type'));

        return $this->view;
    }

    public function complete() {
        $id = explode('_',Route::input('id', '0_0_0'));
        $dataResult = DB::table('payment_data')
                            ->select('payment_data.*','system_department.name as department_name','system_organize.name as organize_name')
                            ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->leftJoin('system_organize','system_department.organize_id','=','system_organize.id')
                            ->where('pi_list_id',$id[0])
                            ->where('pay_year',$id[1])
                            ->where('pay_month',$id[2])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    ##

    public function ajax_confirm() {
        $validator = Validator::make(Request::all(), [
                    
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $id = explode('_',Request::input('id'));
        $reservationlogResult = DB::table('payment_reservation_log')
                            ->select('payment_reservation_log.payment_reservation_log_id',
                                    'payment_reservation_log.pi_list_id',
                                    'payment_reservation_log.pay_year',
                                    'payment_reservation_log.pay_month',
                                    'instrument_reservation_data.*',
                                    'instrument_data.name as instrument_name',
                                    'member_data.name as member_name',
                                    'member_data.type as member_type')
                            ->leftJoin('instrument_reservation_data', function ($join) {
                                $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                            })
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->where('payment_reservation_log.pi_list_id',$id[0])
                            ->where('payment_reservation_log.pay_year',$id[1])
                            ->where('payment_reservation_log.pay_month',$id[2])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        $payment_total = 0;
        //原始費用總額計算
        foreach($reservationlogResult as $k=>$v)
        {
            $payment_total += floatval($v['pay']);
        }
        //折扣計算
        $discount = Request::input('discount',array());
        $discount_number = Request::input('discount_number',array());
        foreach($discount as $k=>$v)
        {
            $v = explode('_',$v);
            $uid = explode('-',$v[0]);
            $discount_total = 0;
            $discount_JSON = array();
            $payment_reservation_log_id = 0;
            foreach($reservationlogResult as $k1=>$v1)
            {
                if($v1["payment_reservation_log_id"] == $uid[0] && $v1["pi_list_id"] == $uid[1] && $v1["pay_year"] == $uid[2] && $v1["pay_month"] == $uid[3])
                {
                    if($v[1] == "1")
                    {
                        $discount_total = floatval($v1['pay']) - (floatval($v1['pay']) * (floatval(100 - intval($discount_number[$k1]))/100));
                    }
                    else if($v[1] == "2")
                    {
                        $discount_total = floatval($discount_number[$k]);
                    }
                    $discount_JSON = json_encode(array('type'=>$v[1],'number'=>$discount_number[$k]));
                    $payment_reservation_log_id = $v1['payment_reservation_log_id'];
                }
            }
            $payment_total -= floatval($discount_total);
            
            //更新折扣紀錄
            $param = array($payment_reservation_log_id,$id,$discount_JSON,$discount_total);
            DB::transaction(function()use($param){
                $id = explode('_',Request::input('id'));
                
                DB::table('payment_reservation_log')
                    ->where('payment_reservation_log_id',$param[0])
                    ->where('pi_list_id',$param[1][0])
                    ->where('pay_year',$param[1][1])
                    ->where('pay_month',$param[1][2])
                    ->update(['discount_JSON'=>$param[2]
                    ]);
            });
        }
        

        //耗材計算
        $pay_code = Request::input('pay_code',array());
        $supplies = Request::input('supplies',array());
        $count = Request::input('count',array());
        $supplies_JOSN = array();
        $supplies_total = array();
        foreach($pay_code as $k=>$v)
        {
            $v = explode('_',$v);
            $uid = explode('-',$v[0]);
            $supplies_total_tmp = 0;
            $supplies_JOSN_tmp = array();
            foreach($reservationlogResult as $k1=>$v1)
            {
                if($v1["payment_reservation_log_id"] == $uid[0] && $v1["pi_list_id"] == $uid[1] && $v1["pay_year"] == $uid[2] && $v1["pay_month"] == $uid[3])
                {
                    $suppliesResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.rate'.$v1['member_type'])
                            ->where('id',$supplies[$k])
                            ->get();
                    if(isset($suppliesResult[0]['rate'.$v1['member_type']]))
                    {
                        $supplies_total_tmp = $suppliesResult[0]['rate'.$v1['member_type']] * $count[$k];
                        $supplies_JOSN_tmp = array('id'=>$supplies[$k],'count'=>$count[$k],'total'=>$supplies_total_tmp);
                    }
                }
            }
            if(isset($supplies_JOSN[$v[0]]))
            {
                array_push($supplies_JOSN[$v[0]],$supplies_JOSN_tmp);
            }
            else{
                $supplies_JOSN[$v[0]] = array($supplies_JOSN_tmp);
            }
            if(isset($supplies_total[$v[0]]))
            {
                $supplies_total[$v[0]] = floatval($supplies_total[$v[0]]) + floatval($supplies_total_tmp);
            }
            else{
                $supplies_total[$v[0]] = $supplies_total_tmp;
            }
        } 

        foreach($supplies_JOSN as $k=>$v)
        {
            $payment_total += floatval($supplies_total[$k]);
            //更新耗材紀錄
            $param = array($k,$v,$supplies_total);
            DB::transaction(function()use($param){
                $id = explode('-',$param[0]);
                
                DB::table('payment_reservation_log')
                    ->where('payment_reservation_log_id',$id[0])
                    ->where('pi_list_id',$id[1])
                    ->where('pay_year',$id[2])
                    ->where('pay_month',$id[3])
                    ->update(['supplies_JOSN'=>json_encode($param[1]),
                                'supplies_total'=>$param[2][$param[0]]
                    ]);
            });
        }
        
        //儲存帳單紀錄
        try {
            DB::transaction(function()use($payment_total){
                $id = explode('_',Request::input('id'));
                $result_before = DB::table('payment_data')
                                    ->where('pi_list_id',$id[0])
                                    ->where('pay_year',$id[1])
                                    ->where('pay_month',$id[2])
                                    ->get();

                DB::table('payment_data')
                    ->where('pi_list_id',$id[0])
                    ->where('pay_year',$id[1])
                    ->where('pay_month',$id[2])
                    ->update(['remark'=>Request::input('remark'),
                                'total'=>$payment_total,
                                'create_admin_id'=>User::id()
                    ]);
                
                $result_after = DB::table('payment_data')
                                    ->where('pi_list_id',$id[0])
                                    ->where('pay_year',$id[1])
                                    ->where('pay_month',$id[2])
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'payment_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
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

    public function ajax_complete() {
        $validator = Validator::make(Request::all(), [
                    'payment' => 'integer|required',
                    'receive' => 'string|required|max:128'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $id = explode('_',Request::input('id'));
                $payment_paylog = DB::table('payment_paylog')
                        ->select('payment_paylog_id')
                        ->where('pi_list_id',$id[0])
                        ->where('pay_year',$id[1])
                        ->where('pay_month',$id[2])
                        ->orderBy('payment_paylog_id','desc')
                        ->first();
                if(!isset($payment_paylog[0]['payment_paylog']))
                {
                    $payment_paylog = 0;
                }
                else
                {
                    $payment_paylog = $payment_paylog[0]['payment_paylog'];
                }
                $payment_paylog = intval($payment_paylog) +1;

                DB::table('payment_paylog')
                    ->insert(array(
                            'pi_list_id'=>$id[0],
                            'pay_year'=>$id[1],
                            'pay_month'=>$id[2],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'payment_paylog_id'=>$payment_paylog,
                            'payment'=>Request::input('payment'),
                            'receive'=>Request::input('receive'),
                            'create_admin_id'=>User::id()
                        ));

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
