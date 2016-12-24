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

class InstrumentRateController extends Controller {

    public function index() {

        $id = Route::input('id');
        $listResult = DB::table('instrument_rate');

        $listResult = $listResult->select('instrument_rate_id','instrument_data_id','start_dt','rate_type','member_1','member_2','member_3','member_4','rate','disabled')
                                    ->where('instrument_data_id',$id)
                                    ->orderBy('instrument_rate_id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function edit() {
        $id = explode('_',Route::input('id', 0));
        $dataResult = DB::table('instrument_rate')
                            ->select('instrument_rate.*','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_rate.create_admin_id','=','member_admin.id')
                            ->where('instrument_rate.instrument_rate_id',$id[0])
                            ->where('instrument_rate.instrument_data_id',$id[1])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = explode('_',Route::input('id', 0));
        $dataResult = DB::table('instrument_rate')
                            ->select('instrument_rate.*','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_rate.create_admin_id','=','member_admin.id')
                            ->where('instrument_rate.instrument_rate_id',$id[0])
                            ->where('instrument_rate.instrument_data_id',$id[1])
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'start_dt' => 'string|required',
                    'rate_type' => 'string|required',
                    'member_1' => 'numeric|required',
                    'member_2' => 'numeric|required',
                    'member_3' => 'numeric|required',
                    'member_4' => 'numeric|required',
                    'rate' => 'numeric|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        try {
            DB::transaction(function(){
                DB::table('instrument_rate')
                    ->where('instrument_data_id',explode('_',Request::input('id'))[1])
                    ->update(['disabled'=>1,
                    ]);
                //新增一筆
                $instrument_rate_id = DB::table('instrument_rate')
                        ->select('instrument_rate_id')
                        ->where('instrument_data_id',explode('_',Request::input('id'))[1])
                        ->orderBy('instrument_rate_id','desc')
                        ->limit(1)
                        ->get();
                if(!isset($instrument_rate_id[0]['instrument_rate_id']))
                {
                    $instrument_rate_id = 0;
                }
                else
                {
                    $instrument_rate_id = $instrument_rate_id[0]['instrument_rate_id'];
                }
                $instrument_rate_id = intval($instrument_rate_id)+1;

                $id = DB::table('instrument_rate')
                        ->insertGetId(
                            array('instrument_rate_id'=>$instrument_rate_id,
                                    'instrument_data_id'=>explode('_',Request::input('id'))[1],
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'rate_type'=>Request::input('rate_type'),
                                    'member_1'=>Request::input('member_1'),
                                    'member_2'=>Request::input('member_2'),
                                    'member_3'=>Request::input('member_3'),
                                    'member_4'=>Request::input('member_4'),
                                    'rate'=>Request::input('rate'),
                                    'remark'=>Request::input('remark'),
                                    'disabled'=>0,
                                    'create_admin_id'=>User::id(),
                            )
                        );
                $result_after = DB::table('instrument_rate')
                                ->where('instrument_rate_id',$instrument_rate_id)
                                ->where('instrument_data_id',explode('_',Request::input('id'))[1])
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_rate',
                    'operator' => DBOperator::OP_INSERT,
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
}
