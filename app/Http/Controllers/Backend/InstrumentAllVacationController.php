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

class InstrumentAllVacationController extends Controller {

    public function index() {

        $year = Request::input('year', date('Y'));
        $month = Request::input('month', date('m'));
        

        $listResultTmp = DB::table('instrument_vacation');
        $listResult = array();
        $listResultTmp = $listResultTmp->select('vacation_dt',
                                            'vacation_type',
                                            'remark')
                                    ->where('instrument_id','0')
                                    ->where('vacation_dt','>=',$year.'-'.$month.'-01')
                                    ->where('vacation_dt','<=',date('Y-m-d',strtotime('+1 month -1 days',strtotime($year.'-'.$month.'-01'))))
                                    ->orderBy('vacation_dt','asc')
                                    ->get();
        foreach($listResultTmp as $k=>$v)
        {
            $listResult[$v['vacation_dt']] = $v;
        }
        $week_first = date('w',strtotime($year.'-'.$month.'-01'));
        $last_day = date('d', strtotime ("-1 day", strtotime($year.'-'.($month+1).'-01')));

        $this->view->with('listResult', $listResult);
        $this->view->with('week_first', $week_first);
        $this->view->with('last_day', $last_day);
        $this->view->with('year', $year);
        $this->view->with('month', $month);
        $this->view->with('vacation_type', Config::get('data.vacation_type'));

        return $this->view;
    }

    ##

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'vacation_type' => 'array|required',
                    'remark' => 'array|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        try {
            DB::transaction(function(){
                $vacation_type = Request::input('vacation_type');
                $remark = Request::input('remark');
                $year = Request::input('year');
                $month = Request::input('month');

                //移除舊資料
                DB::table('instrument_vacation')
                    ->whereYear('vacation_dt','=', $year)
                    ->whereMonth('vacation_dt','=', $month)
                    ->where('instrument_id','=','0')
                    ->delete();
                
                foreach($vacation_type as $k=>$v)
                {
                    $value = explode('_',$v);
                    if($value[1] != '')
                    {
                        DB::table('instrument_vacation')
                            ->insert(['vacation_dt'=>$value[0],
                                        'instrument_id'=>0,
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'vacation_type'=>$value[1],
                                        'remark'=>$remark[$k],
                                        'create_admin_id'=>User::id()
                            ]);
                    }
                }
                DBProcedure::writeLog([
                    'table' => 'instrument_vacation',
                    'operator' => DBOperator::OP_INSERT,
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
