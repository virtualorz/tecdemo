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

class ActivityPassController extends Controller {

    public function index() {

        $listResult = DB::table('activity_data');
        $listResult = $listResult->select('activity_data.id',
                                            DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y/%m/%d") as start_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y/%m/%d") as end_dt'),
                                            DB::raw('DATE_FORMAT(activity_data.created_at, "%Y/%m/%d") as created_at'),
                                            'activity_data.activity_name',
                                            'activity_data.level',
                                            'activity_data.time',
                                            DB::raw('count(activity_reservation_data.member_id) as reservation_count'))
                                    ->leftJoin('activity_reservation_data',function($join){
                                        $join->on('activity_reservation_data.activity_id','=','activity_data.id')
                                        ->where('activity_reservation_data.attend_status','=',1);
                                    })
                                    ->where('activity_data.pass_type',2)
                                    ->orderBy('activity_data.start_dt','desc')
                                    ->groupBy('activity_data.id')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function student_list() {

        $id = Route::input('id', 0);
        $name = Request::input('name', '');
        $card_id_number = Request::input('card_id_number', '');
        $id_type = Request::input('id_type', '');

        $listResult = DB::table('activity_reservation_data');
        if($name != "")
        {
            $listResult->where('member_data.name','like','%'.$name.'%');
        }
        if($card_id_number != "")
        {
            $listResult->where('member_data.card_id_number',$card_id_number);
        }
        if($id_type != "")
        {
            $listResult->where('member_data.type','=',$id_type);
        }

        $listResult = $listResult->select('activity_reservation_data.activity_id',
                                            'activity_reservation_data.member_id',
                                            DB::raw('DATE_FORMAT(activity_reservation_data.created_at, "%Y%m/%d") as created_at'),
                                            'activity_reservation_data.attend_status',
                                            'activity_reservation_data.pass_status',
                                            'activity_reservation_data.score',
                                            'member_data.name',
                                            'member_data.email')
                                    ->leftJoin('member_data','activity_reservation_data.member_id','=','member_data.id')
                                    ->where('reservation_status',1)
                                    ->where('activity_id',$id)
                                    ->orderBy('id','desc')
                                    ->get();
        $this->view->with('listResult', $listResult);
        $this->view->with('id_type', Config::get('data.id_type'));
        return $this->view;
    }

    ##

    public function ajax_pass() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $ids = Request::input('id', "0_0");
        $score = Request::input('score', "0");
        try {
            $id = explode('_',$ids);

                DB::table('activity_reservation_data')
                    ->where('activity_id',$id[0])
                    ->where('member_id',$id[1])
                    ->update(['pass_status'=>1,
                                'score' =>$score
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

        
        $this->view['msg'] = trans('message.success.attend');
        return $this->view;
    }

    public function ajax_pass_cancel() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $ids = Request::input('id', "0_0");
        $score = Request::input('score', "0");
        try {
            $id = explode('_',$ids);

                DB::table('activity_reservation_data')
                    ->where('activity_id',$id[0])
                    ->where('member_id',$id[1])
                    ->update(['pass_status'=>0
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

        
        $this->view['msg'] = trans('message.success.attend');
        return $this->view;
    }
}
