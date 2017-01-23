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

class InstrumentReservationController extends Controller {

    public function index() {

        $name = Request::input('name', '');
        $card_id_number = Request::input('card_id_number', '');
        $instrument = Request::input('instrument', '');

        $listResult = DB::table('instrument_reservation_data');
        if($name != "")
        {
            $listResult->where('member_data.name','like','%'.$name.'%');
        }
        if($card_id_number != "")
        {
            $listResult->where('member_data.card_id_number','=',$card_id_number);
        }
        if($instrument != "")
        {
            $listResult->where('instrument_reservation_data.instrument_id','=',$instrument);
        }

        $listResult = $listResult->select('instrument_reservation_data.instrument_reservation_data_id',
                                            'instrument_reservation_data.create_date',
                                            'instrument_reservation_data.reservation_dt',
                                            'instrument_section.start_time',
                                            'instrument_section.end_time',
                                            'instrument_reservation_data.reservation_status',
                                            'instrument_reservation_data.attend_status',
                                            'instrument_data.instrument_id',
                                            'instrument_data.name',
                                            'member_data.name as member_name')
                                    ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                                    ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                                    ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                                    ->orderBy('instrument_reservation_data.reservation_dt','desc')
                                    ->orderBy('instrument_section.start_time','asc')
                                    ->orderBy('instrument_reservation_data.created_at','asc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $instrumentResult = DB::table('instrument_data')
                                    ->select('instrument_data.id','instrument_data.name','instrument_type.name as type_name')
                                    ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                                    ->orderBy('instrument_type.id','asc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('instrumentResult', $instrumentResult);

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
                                        'member_data.type as member_type',
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->where('instrument_reservation_data.instrument_reservation_data_id',$id[0])
                            ->where('instrument_reservation_data.create_date',$id[1])
                            ->get();

        $suppliesResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.*')
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('suppliesResult', $suppliesResult);

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
        if(count($dataResult) != 0)
        {
            $dataResult[0]['supplies_JOSN'] = json_decode($dataResult[0]['supplies_JOSN'],true);
            if($dataResult[0]['supplies_JOSN'] != '')
            {
                foreach($dataResult[0]['supplies_JOSN'] as $k=>$v)
                {
                    $supplies = DB::table('instrument_supplies')
                        ->select('name')
                        ->where('id',$v['id'])
                        ->first();
                    if(isset($supplies['name']))
                    {
                        $dataResult[0]['supplies_JOSN'][$k]['name'] = $supplies['name'];
                    }
                }
            }
            else
            {
                $dataResult[0]['supplies_JOSN'] = array();
            }
        }

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
            ->whereDate('start_dt','<=',date('Y-m-d'))
            ->orderBy('instrument_data_id','desc')
            ->take(1)
            ->get();
        if(!isset($instrument_dataResult[0]['rate_type']))
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array(trans('message.error.rate_error'));

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

        //耗材計算
        $supplies = Request::input('supplies',array());
        $count = Request::input('count',array());
        $member_type = Request::input('member_type','1');
        
        $supplies_JOSN = array();
        $supplies_total = 0;
        foreach($supplies as $k=>$v)
        {
            $suppliesResult = DB::table('instrument_supplies')
                    ->select('instrument_supplies.rate'.$member_type)
                    ->where('id',$v)
                    ->get();
            if(isset($suppliesResult[0]['rate'.$member_type]))
            {
                $supplies_total_tmp = $suppliesResult[0]['rate'.$member_type] * $count[$k];
                $supplies_total += $supplies_total_tmp;
                array_push($supplies_JOSN,array('id'=>$v,'count'=>$count[$k],'total'=>$supplies_total_tmp));
            }
        }

        $param = array($pay,json_encode($supplies_JOSN),$supplies_total);
        
        try {
            DB::transaction(function()use($param){
                $id = explode('_',Request::input('id'));
                
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('create_date',$id[1])
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'use_dt_start'=>Request::input('use_dt_start'),
                                'use_dt_end'=>Request::input('use_dt_end'),
                                'attend_status'=>1,
                                'pay'=>$param[0],
                                'supplies_JOSN'=>$param[1],
                                'supplies_total'=>$param[2],
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
