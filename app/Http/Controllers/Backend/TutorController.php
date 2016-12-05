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

class TutorController extends Controller {

    public function index() {
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');

        $listResult = DB::table('school_tutor');
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
            $listResult->where('school_tutor.school_id','=',$school_id);
        }
        $listResult = $listResult->select('school_tutor.id','school_tutor.date','school.city','school.town','school.school_name',DB::raw('DATE_FORMAT(school_tutor.created_at, "%Y-%m-%d") as created_at'))
                                ->leftJoin('school','school_tutor.school_id','=','school.id')
                                ->orderBy('school_tutor.id','desc')
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

    public function add() {

        $this->view->with('location',Config::get('data.location'));
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.id','school_tutor.created_at','school_tutor.school_id','school_tutor.date','school_tutor.member','school_tutor.content','school_tutor.file','school_tutor.photo','school.city','school.town','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','school_tutor.create_admin_id','=','member_admin.id')
                            ->leftJoin('school','school_tutor.school_id','=','school.id')
                            ->where('school_tutor.id',$id)
                            ->get();
        $schoolResult = DB::table('school')
                            ->select('id','school_name')
                            ->where('city',$dataResult[0]['city'])
                            ->where('town',$dataResult[0]['town'])
                            ->get();
        //照片處理
        $photo = json_decode($dataResult[0]['photo'],true);
        $photo_text = array();
        foreach($photo as $k=>$v)
        {
            array_push($photo_text,$photo[$k]['text']);
            unset($photo[$k]['text']);
        }

        $photo = json_encode($photo);
        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('schoolResult',$schoolResult);
        $this->view->with('photo_text',$photo_text);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.id','school_tutor.created_at','school_tutor.school_id','school_tutor.date','school_tutor.member','school_tutor.content','school_tutor.file','school_tutor.photo','school.city','school.town','school.school_name','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','school_tutor.create_admin_id','=','member_admin.id')
                            ->leftJoin('school','school_tutor.school_id','=','school.id')
                            ->where('school_tutor.id',$id)
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

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'school_id' => 'integer|required',
                    'date' => 'string|required',
                    'member' => 'string|required',
                    'content' => 'string|required',
                    'file' => 'string|required',
                    'photo' => 'string|required',
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
        $photo = json_decode(Request::input('photo'),true);
        $photo_text = Request::input('photo_text');
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);

        try {
            DB::transaction(function($photo) use($photo){
                $id = DB::table('school_tutor')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'school_id'=>Request::input('school_id'),
                                    'date'=>Request::input('date'),
                                    'member'=>Request::input('member'),
                                    'content'=>Request::input('content'),
                                    'file'=>Request::input('file'),
                                    'photo'=>$photo,
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('school_tutor')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'school_tutor',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        return $this->view;
    }

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'school_id' => 'integer|required',
                    'date' => 'string|required',
                    'member' => 'string|required',
                    'content' => 'string|required',
                    'file' => 'string|required',
                    'photo' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $photo = json_decode(Request::input('photo'),true);
        $photo_text = Request::input('photo_text');
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);


        try {
            DB::transaction(function($photo) use($photo){
                $result_before = DB::table('school_tutor')
                                    ->where('id',Request::input('id'))
                                    ->get();
                
                DB::table('school_tutor')
                    ->where('id',Request::input('id'))
                    ->update(['school_id'=>Request::input('school_id'),
                                'date'=>Request::input('date'),
                                'member'=>Request::input('member'),
                                'content'=>Request::input('content'),
                                'file'=>Request::input('file'),
                                'photo'=>$photo,
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('school_tutor')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'school_tutor',
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

                $result_before = DB::table('school_tutor')
                                    ->where('id',$id)
                                    ->get();
                DB::table('school_tutor')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'school_tutor',
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
