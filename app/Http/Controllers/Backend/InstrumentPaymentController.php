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

    public function complete() {
        $id = explode('_',Route::input('id', '0_0'));
        $dataResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.*',
                                        'instrument_section.start_time',
                                        'instrument_section.end_time',
                                        'instrument_data.instrument_id',
                                        'instrument_data.name',
                                        'member_data.name as member_name',
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->where('instrument_reservation_data.instrument_reservation_data_id',$id[0])
                            ->where('instrument_reservation_data.create_date',$id[1])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = explode('_',Route::input('id', '0_0'));
        $dataResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.*',
                                        'instrument_section.start_time',
                                        'instrument_section.end_time',
                                        'instrument_data.instrument_id',
                                        'instrument_data.name',
                                        'member_data.name as member_name',
                                        'system_pi_list.name as pi_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('member_admin','instrument_reservation_data.update_admin_id','=','member_admin.id')
                            ->where('instrument_reservation_data.instrument_reservation_data_id',$id[0])
                            ->where('instrument_reservation_data.create_date',$id[1])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    ##

    public function ajax_complete() {
        $validator = Validator::make(Request::all(), [
                    'use_dt_start' => 'string|required',
                    'use_dt_end' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $id = explode('_',Request::input('id'));
        //使用費計算
        $dataResult = DB::table('instrument_reservation_data')
            ->select('instrument_reservation_data.instrument_id','member_data.type')
            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
            ->where('instrument_reservation_data.instrument_reservation_data_id',$id[0])
            ->where('instrument_reservation_data.create_date',$id[1])
            ->get();

        $instrument_dataResult = DB::table('instrument_rate')
            ->select('rate_type','member_1','member_2','member_3','member_4','rate')
            ->where('instrument_data_id',$dataResult[0]['instrument_id'])
            ->where('start_dt','>=',date('Y-m-d'))
            ->orderBy('instrument_data_id','desc')
            ->limit('1')
            ->get();
        if(!isset($instrument_dataResult[0]['rate_type']))
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('message.error.rate_error');

            return $this->view;
        }
        $pay = 0;
        $use_hour = (strtotime(Request::input('use_dt_end')) - strtotime(Request::input('use_dt_start')))/3600;
        if($instrument_dataResult[0]['rate_type'] == 1)
        {
            $pay = $instrument_dataResult[0]['member_'.$dataResult[0]['type']];
        }
        else if($instrument_dataResult[0]['rate_type'] == 2)
        {
            $pay = $instrument_dataResult[0]['member_'.$dataResult[0]['type']]*$use_hour*2;
        }
        else if($instrument_dataResult[0]['rate_type'] == 3)
        {
            $pay = $instrument_dataResult[0]['member_'.$dataResult[0]['type']]*$use_hour;
        }

        
        try {
            DB::transaction(function()use($pay){
                $id = explode('_',Request::input('id'));
                
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('create_date',$id[1])
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'use_dt_start'=>Request::input('use_dt_start'),
                                'use_dt_end'=>Request::input('use_dt_end'),
                                'attend_status'=>1,
                                'pay'=>$pay,
                                'remark'=>Request::input('remark'),
                                'update_admin_id'=>User::id()
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

    public function ajax_notattend() {
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
                
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('create_date',$id[1])
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'attend_status'=>0,
                                'update_admin_id'=>User::id()
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

    public function ajax_removewait() {
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
                
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('create_date',$id[1])
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'reservation_status'=>null,
                                'update_admin_id'=>User::id()
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
        try {
            foreach ($ids as $k => $v) {
                $id = $id = explode('_',$v);


                $result_before = DB::table('instrument_reservation_data')
                                    ->where('instrument_reservation_data_id',$id[0])
                                    ->where('create_date',$id[1])
                                    ->get();
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('create_date',$id[1])
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_reservation_data',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
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

        
        $this->view['msg'] = trans('message.success.delete');
        return $this->view;
    }
}
