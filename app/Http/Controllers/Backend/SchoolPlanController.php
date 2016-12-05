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

class SchoolPlanController extends Controller {

    public function index() {
        $topic = Request::input('topic', '');
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');

        $listResult = DB::table('school_plan');
        if($topic != "")
        {
            $listResult->where('school_plan.topic','like','%'.$topic.'%');
        }
        if($city != "")
        {
            $listResult->where('school.city','=',$city);
        }
        if($town != "")
        {
            $listResult->where('school.town','=',$town);
        }
        if($school_id != "")
        {
            $listResult->where('school_plan.school_id','=',$school_id);
        }

        $listResult = $listResult->select('school_plan.id','school_plan.topic','school.school_name',DB::raw('DATE_FORMAT(school_plan.created_at, "%Y-%m-%d") as created_at'))
                                    ->leftJoin('school','school_plan.school_id','=','school.id')
                                    ->orderBy('school_plan.id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $schoolResult = array();
        if($city != '' && $town != '')
        {
            $schoolResult = DB::table('school')
                            ->select('id','school_name')
                            ->where('city',$city)
                            ->where('town',$town)
                            ->get();
        }

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('schoolResult',$schoolResult);
        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school_plan')
                            ->select('school_plan.id','school_plan.created_at','school_plan.topic','school_plan.idea','school_plan.plan','school_plan.file','school_plan.contact_name','school_plan.contact_tel','school_plan.contact_email','school_plan.related_group','school_plan.related_url','school.school_name')
                            ->leftJoin('school','school_plan.school_id','=','school.id')
                            ->where('school_plan.id',$id)
                            ->get();
        if (count($dataResult[0]) > 0)
        {
            $dataResult[0]['plan'] = json_decode($dataResult[0]['plan'], true);
            $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);
            $dataResult[0]['related_group'] = json_decode($dataResult[0]['related_group'], true);
            $dataResult[0]['related_url'] = json_decode($dataResult[0]['related_url'], true);
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        return $this->view;
    }

    ##

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

                $result_before = DB::table('school_plan')
                                    ->where('id',$id)
                                    ->get();
                DB::table('school_plan')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_plan',
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

    public function ajax_get_town() {

        $twTown = Config::get('data.twTown');
        return $twTown[Request::input('id')];
    }
}
