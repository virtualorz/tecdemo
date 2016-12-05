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

class SchoolExecuteController extends Controller {

    public function index() {
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');

        $listResult = DB::table('school_execute');
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
            $listResult->where('school_execute.school_id','=',$school_id);
        }

        $listResult = $listResult->select('school_execute.id','school_execute.date','school.city','school.town','school.school_name',DB::raw('DATE_FORMAT(school_execute.created_at, "%Y-%m-%d") as created_at'))
                                    ->leftJoin('school','school_execute.school_id','=','school.id')
                                    ->orderBy('school_execute.id','desc')
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
        $dataResult = DB::table('school_execute')
                            ->select('school_execute.id','school_execute.created_at','school_execute.school_id','school_execute.date','school_execute.member','school_execute.content','school_execute.file','school_execute.photo','school.city','school.town','school.school_name')
                            ->leftJoin('school','school_execute.school_id','=','school.id')
                            ->where('school_execute.id',$id)
                            ->get();
        if (count($dataResult[0]) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
        //照片處理
        $photo = json_decode($dataResult[0]['photo'],true);
        $photo_text = array();
        foreach($photo as $k=>$v)
        {
            array_push($photo_text,$photo[$k]['text']);
            unset($photo[$k]['text']);
        }
        $dataResult[0]['photo'] = json_encode($photo);
        
        $dataResult[0]['photo'] = FileUpload::getFiles($dataResult[0]['photo']);
        foreach($dataResult[0]['photo'] as $k=>$v)
        {
            $dataResult[0]['photo'][$k]['text'] = $photo_text[$k];
        }
        $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);

        $this->view->with('dataResult', $dataResult[0]);
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

                $result_before = DB::table('school_execute')
                                    ->where('id',$id)
                                    ->get();
                DB::table('school_tutor')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_execute',
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
}
