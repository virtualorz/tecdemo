<?php

namespace App\Http\Controllers\Official;

//
use User;
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Validator;
use Log;
use Sitemap;
use SitemapAccess;

class ActivityController extends Controller {

    public function index() {
        
        $keyword = Request::input('keyword', '');
        $searchResult = array();
        $liest_aResult = array();
        $liest_unaResult = array();
        $pagination = array();
        if($keyword != "")
        {
            $searchResult = DB::table('activity_data')
                            ->where('activity_data.activity_name','like','%'.$keyword.'%')
                            ->select('activity_data.id',
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                            'activity_data.activity_id',
                                            'activity_type.name as type_name',
                                            'activity_data.activity_name',
                                            'activity_data.time',
                                            'activity_data.score',
                                            DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                            ->leftJoin('activity_reservation_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('id','desc')
                            ->get();
        }
        else
        {
            $liest_aResult = DB::table('activity_data')
                            ->whereNull('end_dt')
                            ->orWhere(function ($query) {
                                $query->whereDate('start_dt','<',date('Y-m-d'))
                                    ->whereDate('end_dt', '>', date('Y-m-d'));
                            })
                            ->select('activity_data.id',
                                                DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                                'activity_data.activity_id',
                                                'activity_type.name as type_name',
                                                'activity_data.activity_name',
                                                'activity_data.time',
                                                'activity_data.score',
                                                DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                            ->leftJoin('activity_reservation_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('id','desc')
                            ->get();
            $liest_unaResult = DB::table('activity_data')
                            ->whereDate('end_dt','<',date('Y-m-d'))
                            ->select('activity_data.id',
                                                DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                                DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                                'activity_data.activity_id',
                                                'activity_type.name as type_name',
                                                'activity_data.activity_name',
                                                'activity_data.time',
                                                'activity_data.score',
                                                DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                            ->leftJoin('activity_reservation_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->leftJoin('activity_type','activity_data.activity_type_id','=','activity_type.id')
                            ->groupBy('activity_data.id')
                            ->orderBy('id','desc')
                            ->paginate(Config::get('pagination.items'));
            $pagination = $this->getPagination(json_decode($liest_unaResult->toJson(),true)['total']);
        }

        
        

        $this->view->with('searchResult', $searchResult);
        $this->view->with('liest_aResult', $liest_aResult);
        $this->view->with('liest_unaResult', $liest_unaResult);
        $this->view->with('pagination', $pagination);

        return $this->view;
    }

    public function finish() {

        return $this->view;
    }

    ##

    public function ajax_register() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:10',
                    'card_id_number' => 'string|required|max:20',
                    'id_number' => 'string|required|max:12',
                    'organize' => 'integer|required',
                    'department' => 'integer|required',
                    'email' => 'string|required|max:200',
                    'password' => 'string|required|max:200|same:passwordR',
                    'phone' => 'string|required|max:24',
                    'pi' => 'integer|required',
                    'lab_phone' => 'string|required|max:24',
                    'member_agree' => 'required',
        ]);
        if ($validator->fails()) {
            $invalid[] = $validator->errors();
        }
        if (count($invalid) > 0) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $invalid;
            return $this->view;
        }
        //檢查重複
        $count = DB::table('member_data')
                ->select('email')
                ->where('email',Request::input('email'))
                ->count();
        
        if($count != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複！');
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = DB::table('member_data')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'card_id_number'=>Request::input('card_id_number'),
                                    'id_number'=>Request::input('id_number'),
                                    'organize_id'=>Request::input('organize'),
                                    'department_id'=>Request::input('department'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'enable'=>0
                            )
                        );
                $result_after = DB::table('member_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => $id
                ]);
                
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.register');
        return $this->view;
    }

    public function ajax_get_department() {

        $id = Request::input('id');
        $listResult = DB::table('system_department');
        $listResult = $listResult->select('id','name')
                                    ->where('organize_id',$id)
                                    ->get();
        
        return $listResult;
    }

    public function ajax_get_pi() {

        $id = Request::input('id');
        $listResult = DB::table('system_pi_list');
        $listResult = $listResult->select('id','name')
                                    ->where('department_id',$id)
                                    ->get();
        
        return $listResult;
    }

}
