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

class ActivityRegController extends Controller {

    public function index() {

        $listResult1 = DB::table('activity_reg');
        $listResult1 = $listResult1->select('activity_reg.id',
                                            DB::raw('DATE_FORMAT(activity_reg.created_at, "%Y/%m/%d") as created_at'),
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                            'activity_data.activity_name',
                                            'member_data.name',
                                            'member_data.email',
                                            'system_organize.name as organize_name',
                                            'system_department.name as department_name',
                                            'system_pi_list.name as pi_name',
                                            'activity_reg.reason')
                                    ->leftJoin('activity_data','activity_reg.activity_id','=','activity_data.id')
                                    ->leftJoin('member_data','activity_reg.member_id','=','member_data.id')
                                    ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                                    ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                                    ->whereNull('activity_reg.is_pass')
                                    ->orderBy('id','desc')
                                    ->get();
        $listResult2 = DB::table('activity_reg');
        $listResult2 = $listResult2->select('activity_reg.id',
                                            DB::raw('DATE_FORMAT(activity_reg.created_at, "%Y/%m/%d") as created_at'),
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                            'activity_data.activity_name',
                                            'activity_reg.is_pass',
                                            'member_data.name',
                                            'member_data.email',
                                            'system_organize.name as organize_name',
                                            'system_department.name as department_name',
                                            'system_pi_list.name as pi_name',
                                            'activity_reg.reason')
                                    ->leftJoin('activity_data','activity_reg.activity_id','=','activity_data.id')
                                    ->leftJoin('member_data','activity_reg.member_id','=','member_data.id')
                                    ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                                    ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                                    ->whereNotNull('activity_reg.is_pass')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult2->toJson(),true)['total']);

        
        $this->view->with('listResult1', $listResult1);
        $this->view->with('listResult2', $listResult2);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('activity_reg')
                            ->select('activity_reg.id',
                                        'activity_reg.reason',
                                        'activity_reg.is_pass',
                                        DB::raw('DATE_FORMAT(activity_data.created_at, "%Y/%m/%d") as created_at'),
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_data.score',
                                        'member_data.name',
                                        'member_data.email',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('activity_data','activity_reg.activity_id','=','activity_data.id')
                            ->leftJoin('member_data','activity_reg.member_id','=','member_data.id')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->where('activity_reg.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    ##

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'is_pass' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        try {
            DB::transaction(function(){
                $is_pass = Request::input('is_pass');
                $result_before = DB::table('activity_reg')
                                    ->where('id',Request::input('id'))
                                    ->get();
                
                if($is_pass == 1)
                {
                    $activity_data = DB::table('activity_data')
                        ->select('pass_type')
                        ->where('id',$result_before[0]['activity_id'])
                        ->get();
                    $pass_status = 0;
                    if($activity_data[0]['pass_type'] == 1)
                    {
                        $pass_status = 1;
                    }

                    DB::table('activity_reservation_data')
                        ->where('activity_id',$result_before[0]['activity_id'])
                        ->where('member_id',$result_before[0]['member_id'])
                        ->update(['attend_status'=>1,
                                    'pass_status'=>$pass_status,
                                    'activity_reg_id'=>Request::input('id')
                        ]);
                }
                
                DB::table('activity_reg')
                    ->where('id',Request::input('id'))
                    ->update(['is_pass'=>Request::input('is_pass'),
                                'create_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('activity_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_reg',
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
}
