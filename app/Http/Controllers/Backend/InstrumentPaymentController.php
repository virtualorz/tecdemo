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
use Crypt;
use Excel;

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
                    if(!isset($reservation_log_id['payment_reservation_log_id']))
                    {
                        $reservation_log_id = 0;
                    }
                    else
                    {
                        $reservation_log_id = $reservation_log_id['payment_reservation_log_id'];
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
                                            'system_pi_list.name as pi_name',
                                            DB::raw('count(payment_paylog.payment_paylog_id) as pay_count'))
                                    ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                                    ->leftJoin('system_organize','system_pi_list.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                                    ->leftJoin('payment_paylog',function($join){
                                        $join->on('payment_paylog.pi_list_id','=','payment_data.pi_list_id');
                                        $join->on('payment_paylog.pay_year','=','payment_data.pay_year');
                                        $join->on('payment_paylog.pay_month','=','payment_data.pay_month');
                                    })
                                    ->orderBy('payment_data.pay_year','desc')
                                    ->groupBy('payment_data.pi_list_id')
                                    ->groupBy('payment_data.pay_year')
                                    ->groupBy('payment_data.pay_month')
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
                            ->where('payment_reservation_log.pi_list_id',$id[0])
                            ->where('payment_reservation_log.pay_year',$id[1])
                            ->where('payment_reservation_log.pay_month',$id[2])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        foreach($reservationlogResult as $k=>$v)
        {
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
                            ->where('payment_reservation_log.pi_list_id',$id[0])
                            ->where('payment_reservation_log.pay_year',$id[1])
                            ->where('payment_reservation_log.pay_month',$id[2])
                            ->orderBy('payment_reservation_log.payment_reservation_log_id','desc')
                            ->get();
        foreach($reservationlogResult as $k=>$v)
        {
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

    public function reminder() {
        $id = explode('_',Route::input('id', '0_0_0'));
        $listResult = DB::table('payment_reminder_log')
                            ->select(DB::raw('DATE_FORMAT(payment_reminder_log.created_at, "%Y.%m/%d") as created_at'),
                                    'payment_reminder_log.email',
                                    'member_admin.name as create_admin_name'
                            )
                            ->leftJoin('member_admin','payment_reminder_log.create_admin_id','=','member_admin.id')
                            ->where('pi_list_id',$id[0])
                            ->where('pay_year',$id[1])
                            ->where('pay_month',$id[2])
                            ->get();

        $this->view->with('listResult', $listResult);
        $this->view->with('id', Route::input('id', '0_0_0'));

        return $this->view;
    }

    public function output() {

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
                                            'system_pi_list.name as pi_name',
                                            DB::raw('count(payment_paylog.payment_paylog_id) as pay_count'),
                                            'payment_reservation_log.payment_reservation_log_id',
                                            'payment_reservation_log.pi_list_id',
                                            'payment_reservation_log.pay_year',
                                            'payment_reservation_log.pay_month',
                                            'payment_reservation_log.discount_JSON',
                                            'instrument_reservation_data.*',
                                            DB::raw('DATE_FORMAT(instrument_reservation_data.create_date, "%Y%m") as create_date_ym'),
                                            'instrument_data.name as instrument_name',
                                            'member_data.name as member_name',
                                            'member_data.type as member_type')
                                    ->leftJoin('system_pi_list','payment_data.pi_list_id','=','system_pi_list.id')
                                    ->leftJoin('system_organize','system_pi_list.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                                    ->leftJoin('payment_paylog',function($join){
                                        $join->on('payment_paylog.pi_list_id','=','payment_data.pi_list_id');
                                        $join->on('payment_paylog.pay_year','=','payment_data.pay_year');
                                        $join->on('payment_paylog.pay_month','=','payment_data.pay_month');
                                    })
                                    ->leftJoin('payment_reservation_log',function($join){
                                        $join->on('payment_reservation_log.pi_list_id','=','payment_data.pi_list_id');
                                        $join->on('payment_reservation_log.pay_year','=','payment_data.pay_year');
                                        $join->on('payment_reservation_log.pay_month','=','payment_data.pay_month');
                                    })
                                    ->leftJoin('instrument_reservation_data', function ($join) {
                                        $join->on('payment_reservation_log.instrument_reservation_data_id', '=', 'instrument_reservation_data.instrument_reservation_data_id');
                                        $join->on('payment_reservation_log.create_date', '=', 'instrument_reservation_data.create_date');
                                    })
                                    ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                                    ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                                    ->orderBy('payment_data.pay_year','desc')
                                    ->get();
        foreach($listResult as $k=>$v)
        {
            $listResult[$k]['discount_JSON'] = json_decode($v['discount_JSON'],true);
            $listResult[$k]['supplies_JOSN'] = json_decode($v['supplies_JOSN'],true);
            if($listResult[$k]['supplies_JOSN'] != '')
            {
                foreach($listResult[$k]['supplies_JOSN'] as $k1=>$v1)
                {
                    $supplies = DB::table('instrument_supplies')
                        ->select('name')
                        ->where('id',$v1['id'])
                        ->first();
                    if(isset($supplies['name']))
                    {
                        $listResult[$k]['supplies_JOSN'][$k1]['name'] = $supplies['name'];
                    }
                }
            }
            else
            {
                $listResult[$k]['supplies_JOSN'] = array();
            }
        }

        $result = array();
        foreach($listResult as $k=>$v)
        {
            $tmp = array();
            $tmp['月份'] = $v['pay_year'].'/'.$v['pay_month'];
            $tmp['單位'] = $v['organize_name'].'/'.$v['department_name'];
            $tmp['指導教授'] = $v['pi_name'];
            $tmp['總金額'] = $v['total']; 
            $tmp['使用時段'] = $v['use_dt_start'].' - '.$v['use_dt_end']; 
            $tmp['會員'] = $v['member_name']; 
            $tmp['儀器'] = $v['instrument_name']; 
            $tmp['費用'] = $v['pay'];
            if(is_array($v['discount_JSON']))
            {
                if($v['discount_JSON']['type'] == 1)
                {
                    $tmp['折扣'] = $v['discount_JSON']['number'].'%'; 
                }
                else
                {
                    $tmp['折扣'] = $v['discount_JSON']['number'].'元'; 
                }
            }
            if(is_array($v['supplies_JOSN']))
            {
                $tmp['耗材'] = "";
                foreach($v['supplies_JOSN'] as $k1=>$v1)
                {
                    $tmp['耗材'] .= $v1['name'].':'.$v1['count'].'=>'.$v1['total'].';';
                }
            }
            array_push($result,$tmp);
        }






        Excel::create(date('Y-m-d'), function($excel)use($result) {
            $excel->sheet('sheet1', function($sheet)use($result) {

                $sheet->with($result);

            });
        })->download('xls');



        /*$id = explode('_',Route::input('id', '0_0_0'));
        $listResult = DB::table('payment_reminder_log')
                            ->select(DB::raw('DATE_FORMAT(payment_reminder_log.created_at, "%Y.%m/%d") as created_at'),
                                    'payment_reminder_log.email',
                                    'member_admin.name as create_admin_name'
                            )
                            ->leftJoin('member_admin','payment_reminder_log.create_admin_id','=','member_admin.id')
                            ->where('pi_list_id',$id[0])
                            ->where('pay_year',$id[1])
                            ->where('pay_month',$id[2])
                            ->get();

        $this->view->with('listResult', $listResult);
        $this->view->with('id', Route::input('id', '0_0_0'));

        return $this->view;*/
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
        //計算耗材總額
        $supplies_total = 0;
        foreach($reservationlogResult as $k=>$v)
        {
            $supplies_total+= floatval($v['supplies_total']);
        }
        $payment_total = $payment_total + $supplies_total;
        //折扣計算
        $discount = Request::input('discount',array());
        $discount_number = Request::input('discount_number',array());
        foreach($discount as $k=>$v)
        {
            if($v != '')
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
                    'payment' => 'numeric|required',
                    'receive' => 'string|required|max:128'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $id = explode('_',Request::input('id'));
        $paymentResult = DB::table('payment_data')
                            ->where('pi_list_id',$id[0])
                            ->where('pay_year',$id[1])
                            ->where('pay_month',$id[2])
                            ->get();
        if(floatval($paymentResult[0]['total']) !== floatval(Request::input('payment')))
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array(trans('message.error.pay_error'));

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
                if(!isset($payment_paylog['payment_paylog_id']))
                {
                    $payment_paylog = 0;
                }
                else
                {
                    $payment_paylog = $payment_paylog['payment_paylog_id'];
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

    public function ajax_reminder() {
        $validator = Validator::make(Request::all(), [
                    
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
                $payment_reminder_log_id = DB::table('payment_reminder_log')
                        ->select('payment_reminder_log_id')
                        ->where('pi_list_id',$id[0])
                        ->where('pay_year',$id[1])
                        ->where('pay_month',$id[2])
                        ->orderBy('payment_reminder_log_id','desc')
                        ->limit(1)
                        ->get();
                if(!isset($payment_reminder_log_id[0]['payment_reminder_log_id']))
                {
                    $payment_reminder_log_id = 0;
                }
                else
                {
                    $payment_reminder_log_id = $payment_reminder_log_id[0]['payment_reminder_log_id'];
                }
                $payment_reminder_log_id = intval($payment_reminder_log_id)+1;

                //取得老師email
                $pi = DB::table('system_pi_list')
                    ->select('name','email')
                    ->where('id',$id[0])
                    ->first();
                //取得實驗室所有人員email
                $member = DB::table('member_data')
                    ->select('id','name','email','password')
                    ->where('pi_list_id',$id[0])
                    ->get();

                DB::table('payment_reminder_log')
                    ->insert(array(
                            'payment_reminder_log_id'=>$payment_reminder_log_id,
                            'pi_list_id'=>$id[0],
                            'pay_year'=>$id[1],
                            'pay_month'=>$id[2],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'email'=>$pi['email'],
                            'create_admin_id'=>User::id()
                        ));
                //給老師的信
                $dataResult = array('user'=>$pi['name'],'pay_month'=> $id[1].'年'.$id[2].'月');
                Mail::send('emails.reminder', [
                                'dataResult' => $dataResult,
                                    ], function ($m)use($pi) {
                                $m->to($pi['email'], '');
                                $m->subject("系統催繳通知");
                });
                //給學生的信
                foreach($member as $k=>$v)
                {
                    $member_notice_log_id = DB::table('member_notice_log')
                            ->select('member_notice_log_id')
                            ->where('member_data_id',$v['id'])
                            ->orderBy('member_notice_log_id','desc')
                            ->first();
                    if(!isset($member_notice_log_id['member_notice_log_id']))
                    {
                        $member_notice_log_id = 0;
                    }
                    else
                    {
                        $member_notice_log_id = $member_notice_log_id['member_notice_log_id'];
                    }
                    $member_notice_log_id = intval($member_notice_log_id) +1;

                    $id_ = DB::table('member_notice_log')
                            ->insertGetId(
                                array('uid'=>'-',
                                        'salt'=>'-',
                                        'member_data_id'=>$v['id'],
                                        'member_notice_log_id'=>$member_notice_log_id,
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'email'=>$v['email'],
                                        'title'=>'系統催繳通知',
                                        'content'=>'[{"column":1,"cell":[{"weight":1,"item":[{"type":"text","title":"","content":"您所屬的實驗室'.$id[1].'年'.$id[2].'月'.'帳單已逾期未，請儘速至系統列印帳單並繳交"}]}]}]',
                                        'is_read'=>'0',
                                        'create_admin_id'=>User::id()
                                )
                            );
                    //製作uid以及salt
                    $date = date('Y-m-d H:i:s').$id_;
                    $salt = substr(md5($date),5,5);
                    $uid = md5($salt.$date);
                    
                    DB::table('member_notice_log')
                        ->where('member_data_id',$v['id'])
                        ->where('member_notice_log_id',$member_notice_log_id)
                        ->update(['uid'=>$uid,
                                    'salt'=>$salt
                        ]);
                    //寄出訊息
                    $login_hash = DB::table('member_login_hash')
                        ->select('hash')
                        ->where('email',$v['email'])
                        ->where('password',$v['password'])
                        ->first();
                    if(count($login_hash) == 0)
                    {
                        $login_uid = Crypt::encrypt($v['email'].'_'.$v['password']);
                        $hash = md5($login_uid);
                        DB::table('member_login_hash')
                            ->where('email',$v['email'])
                            ->delete();
                        DB::table('member_login_hash')
                            ->insert(array(
                                    'email'=>$v['email'],
                                    'password'=>$v['password'],
                                    'hash'=>$hash,
                                    'uid'=>$login_uid
                                    ));
                    }
                    else
                    {
                        $hash = $login_hash['hash'];
                    }
                    
                    $dataResult = array('user'=>$v['name'],'pay_month'=> $id[1].'年'.$id[2].'月','url'=> asset('/member/message/detail/id-'.$uid.'-'.$salt.'-'.$hash));
                    Mail::send('emails.reminder', [
                                'dataResult' => $dataResult,
                                    ], function ($m)use($v) {
                                $m->to($v['email'], '');
                                $m->subject("系統催繳通知");
                    });
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
}
