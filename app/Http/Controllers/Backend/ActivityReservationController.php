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

class ActivityReservationController extends Controller {

    public function index() {

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
                                            DB::raw('DATE_FORMAT(activity_reservation_data.created_at, "%Y-%m-%d") as created_at'),
                                            'member_data.name',
                                            'member_data.email')
                                    ->leftJoin('member_data','activity_reservation_data.member_id','=','member_data.id')
                                    ->where('reservation_status',1)
                                    ->orderBy('id','desc')
                                    ->get();
        $this->view->with('listResult', $listResult);
        $this->view->with('id_type', Config::get('data.id_type'));
        return $this->view;
    }

    ##

    public function ajax_cancel() {
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
                $id = explode('_',$v);

                DB::table('activity_reservation_data')
                    ->where('activity_id',$id[0])
                    ->where('member_id',$id[1])
                    ->update(['reservation_status'=>0
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

        
        $this->view['msg'] = trans('message.success.cancel');
        return $this->view;
    }
}
