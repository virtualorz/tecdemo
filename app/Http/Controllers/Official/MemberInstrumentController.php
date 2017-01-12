<?php

namespace App\Http\Controllers\Official;

//
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Log;
use User;
use Validator;

class MemberInstrumentController extends Controller {

    public function index() {
        $instrumentResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.uid',
                                        'instrument_reservation_data.salt',
                                        'instrument_reservation_data.reservation_dt',
                                        'instrument_reservation_data.instrument_reservation_data_id',
                                        'instrument_reservation_data.create_date',
                                        'instrument_reservation_data.reservation_status',
                                        'instrument_data.name',
                                        'instrument_data.instrument_id',
                                        'instrument_data.uid as instrument_uid',
                                        'instrument_data.salt as instrument_salt',
                                        DB::raw('DATE_FORMAT(instrument_reservation_data.reservation_dt, "%Y.%m.%d") as reservation_dt'),
                                        DB::raw('DATE_FORMAT(instrument_reservation_data.reservation_dt, "%y%m") as reservation_dt_ym'),
                                        DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                        DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->where('instrument_reservation_data.member_id','=',User::Id())
                            ->whereNull('instrument_reservation_data.attend_status')
                            ->where(function($query){
                                $query->OrWhere('instrument_reservation_data.reservation_status','=',1);
                                $query->OrWhere('instrument_reservation_data.reservation_status','=',0);
                            })
                            ->orderBy('instrument_reservation_data.reservation_dt','desc')
                            ->get();

        $historyResult = DB::table('instrument_reservation_data')
                            ->select('instrument_reservation_data.uid',
                                        'instrument_reservation_data.salt',
                                        'instrument_reservation_data.reservation_dt',
                                        'instrument_data.name',
                                        'instrument_data.instrument_id',
                                        'instrument_reservation_data.reservation_status',
                                        'instrument_reservation_data.attend_status',
                                        'instrument_data.uid as instrument_uid',
                                        'instrument_data.salt as instrument_salt',
                                        DB::raw('DATE_FORMAT(instrument_reservation_data.reservation_dt, "%Y.%m.%d") as reservation_dt'),
                                        DB::raw('DATE_FORMAT(instrument_reservation_data.reservation_dt, "%y%m") as reservation_dt_ym'),
                                        DB::raw('DATE_FORMAT(instrument_section.start_time, "%H:%i") as start_time'),
                                        DB::raw('DATE_FORMAT(instrument_section.end_time, "%H:%i") as end_time'))
                            ->leftJoin('instrument_data','instrument_reservation_data.instrument_id','=','instrument_data.id')
                            ->leftJoin('instrument_section','instrument_reservation_data.reservation_section_id','=','instrument_section.id')
                            ->where(function($query){
                                $query->whereNull('instrument_reservation_data.reservation_status');
                                $query->OrWhere('instrument_reservation_data.reservation_status','=','2');
                                $query->OrWhere(function($q1){
                                    $q1->whereNotNull('instrument_reservation_data.reservation_status');
                                    $q1->whereNotNull('instrument_reservation_data.attend_status');
                                });
                            })
                            ->where('instrument_reservation_data.member_id','=',User::Id())
                            ->orderBy('instrument_reservation_data.reservation_dt','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($historyResult->toJson(),true)['total']);

        $this->view->with('instrumentResult', $instrumentResult);
        $this->view->with('historyResult', $historyResult);
        $this->view->with('pagination', $pagination);
        
        return $this->view;
    }

    public function reg() {
        $id = explode('-',Route::input('id'));
        $dataResult = DB::table('activity_data')
                            ->select('id','activity_name')
                            ->where('uid',$id[0])
                            ->where('salt',$id[1])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        
        return $this->view;
    }

    public function detail() {
        $id = Route::input('id');
        $dataResult = DB::table('member_journal')
                            ->select('member_journal.*',
                                        DB::raw('DATE_FORMAT(member_journal.created_at, "%Y.%m.%d") as created_at'),
                                        DB::raw('DATE_FORMAT(member_journal.release_dt, "%Y.%m.%d") as release_dt'))
                            ->where('member_data_id',User::id())
                            ->where('member_journal_id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('journal', Config::get('data.journal'));
        
        return $this->view;
    }

    ##

    public function ajax_reg() {
        $validator = Validator::make(Request::all(), [
                    'reason' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){

                DB::table('activity_reg')
                        ->insert(array(
                            'created_at'=>date('Y-m-d H:i:s'),
                            'activity_id'=>Request::input('id'),
                            'member_id'=>User::id(),
                            'reason'=>Request::input('reason')
                        ));

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.reg');
        return $this->view;
    }

    public function ajax_cancel() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            $id = explode('_',Request::input('id', '0_0'));

                $result_before = DB::table('instrument_reservation_data')
                                    ->where('instrument_reservation_data_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('create_date',$id[1])
                                    ->get();
                DB::table('instrument_reservation_data')
                    ->where('instrument_reservation_data_id',$id[0])
                    ->where('member_id',User::id())
                    ->where('create_date',$id[1])
                    ->update(['reservation_status'=>'2']);
                
                $result_after = DB::table('instrument_reservation_data')
                                    ->where('instrument_reservation_data_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('create_date',$id[1])
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_reservation_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => User::id()
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

        
        $this->view['msg'] = trans('message.success.cancel');
        return $this->view;
    }
}
