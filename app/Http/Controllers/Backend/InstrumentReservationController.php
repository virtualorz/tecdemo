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
            $listResult->where('instrument_data.name','like','%'.$name.'%');
        }
        if($card_id_number != "")
        {
            $listResult->where('instrument_data.card_id_number','=',$card_id_number);
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
                                            'instrument_data.instrument_id',
                                            'instrument_data.name',
                                            'member_data.name as member_name')
                                    ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                                    ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                                    ->leftJoin('member_data','instrument_reservation_data.member_id','=','member_data.id')
                                    ->orderBy('instrument_reservation_data.created_at','desc')
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
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_data')
                            ->select('instrument_data.*',
                                        'instrument_type.name as type_name',
                                        'instrument_site.name as site_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_data.create_admin_id','=','member_admin.id')
                            ->leftJoin('instrument_type','instrument_data.instrument_type_id','=','instrument_type.id')
                            ->leftJoin('instrument_site','instrument_data.instrument_site_id','=','instrument_site.id')
                            ->where('instrument_data.id',$id)
                            ->get();
        //管理員名單
        $adminResult = DB::table('instrument_admin')
                            ->select('name','email')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_admin_id','desc')
                            ->get();
        //使用時段
        $sectionSetResult = array();
        $sectionSetResultTmp = DB::table('instrument_section_set')
                            ->select('instrument_section_id')
                            ->where('instrument_data_id',$id)
                            ->orderBy('instrument_section_set_id','desc')
                            ->get();
        foreach($sectionSetResultTmp as $k=>$v)
        {
            array_push($sectionSetResult,$v['instrument_section_id']);
        }

        $sectionResultTmp = DB::table('instrument_section')
                                    ->select('id','section_type','start_time','end_time')
                                    ->where('enable',1)
                                    ->orderBy('section_type','asc')
                                    ->get();
        $sectionResult = array();
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 1)
            {
                $tmp = array('1'=>$v,'2'=>'');
                array_push($sectionResult,$tmp);
            }
        }
        foreach($sectionResultTmp as $k=>$v)
        {
            if($v['section_type'] == 2)
            {
                $isset = false;
                foreach($sectionResult as $k1=>$v1)
                {
                    if($v1['2']== '')
                    {
                        $isset = true;
                        $sectionResult[$k1]['2'] = $v;
                        break;
                    }
                }
                if(!$isset)
                {
                    $tmp = array('1'=>'','2'=>$v);
                    array_push($sectionResult,$tmp);
                }
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('adminResult', $adminResult);
        $this->view->with('sectionSetResult', $sectionSetResult);
        $this->view->with('sectionResult', $sectionResult);

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
                $id = $v;

                $result_before = DB::table('instrument_data')
                                    ->where('id',$id)
                                    ->get();
                DB::table('instrument_data')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_data',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                
                //管理員名單刪除
                DB::table('instrument_admin')
                    ->where('instrument_data_id',$id)
                    ->delete();
                //使用時段刪除
                DB::table('instrument_section_set')
                    ->where('instrument_data_id',$id)
                    ->delete();
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

    public function ajax_get_instrument() {

        $id = Request::input('id');
        $listResult = DB::table('instrument_data');
        $listResult = $listResult->select('id','instrument_platform_id','name')
                                    ->whereIn('instrument_platform_id',$id)
                                    ->get();
        
        return $listResult;
    }
}
