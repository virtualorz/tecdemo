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
use PDF;

class MemberEportfolioController extends Controller {

    public function index() {

        $activityResult = DB::table('activity_reservation_data')
                            ->select('activity_data.id',
                                        'activity_data.uid',
                                        'activity_data.salt',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.relative_plateform',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_data.score',
                                        'activity_data.pass_type',
                                        'activity_reservation_data.attend_status',
                                        'activity_reservation_data.score as pass_score',
                                        'activity_reservation_data.pass_status')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->where('activity_reservation_data.attend_status','=',1)
                            ->orderBy('activity_data.start_dt','desc')
                            ->get();
        foreach($activityResult as $k=>$v)
        {
            $relative_plateform_string = "";
            $relative_plateform = json_decode($v['relative_plateform'],true);
            foreach($relative_plateform as $k1=>$v1)
            {
                $plate_formResult = DB::table('instrument_type')
                                    ->select('name')
                                    ->where('id',$v1)
                                    ->get();
                if(count($plate_formResult) !=0)
                {
                    $relative_plateform_string .= $plate_formResult[0]['name'] .'/';
                }

            }
            $activityResult[$k]['plate_formResult_string'] = $relative_plateform_string;
        }

        $this->view->with('activityResult', $activityResult);
        
        return $this->view;
    }

    public function print_protofolio() {
        $activityResult = DB::table('activity_reservation_data')
                            ->select('activity_data.id',
                                        'activity_data.uid',
                                        'activity_data.salt',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.relative_plateform',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_data.score',
                                        'activity_data.pass_type',
                                        'activity_reservation_data.attend_status',
                                        'activity_reservation_data.score as pass_score',
                                        'activity_reservation_data.pass_status')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->where('activity_reservation_data.attend_status','=',1)
                            ->orderBy('activity_data.start_dt','desc')
                            ->get();
        foreach($activityResult as $k=>$v)
        {
            $relative_plateform_string = "";
            $relative_plateform = json_decode($v['relative_plateform'],true);
            foreach($relative_plateform as $k1=>$v1)
            {
                $plate_formResult = DB::table('instrument_type')
                                    ->select('name')
                                    ->where('id',$v1)
                                    ->get();
                if(count($plate_formResult) !=0)
                {
                    $relative_plateform_string .= $plate_formResult[0]['name'] .'/';
                }

            }
            $activityResult[$k]['plate_formResult_string'] = $relative_plateform_string;
        }
        $pdf_name = md5(date('Y-m-d H:i:s'));

        $pdf = PDF::loadView('Official.elements.eprotfolio_print', array(
            'activityResult'=>$activityResult
            ));
        $pdf->setTemporaryFolder(env('DIR_WEB').'files\\tmp\\');
        return $pdf->download($pdf_name.'.pdf');
    }
}
