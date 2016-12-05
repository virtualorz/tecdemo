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

class LearningController extends Controller {

    public function index() {
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');
        $school_others = Request::input('school_others', '');

        $listResult = DB::table('learning');
        if($city != "")
        {
            $listResult->where('learning.city','=',$city);
        }
        if($town != "")
        {
            $listResult->where('learning.town','=',$town);
        }
        if($school_id != "" && $school_id != "-1")
        {
            $listResult->where('learning.school_id','=',$school_id);
        }
        if($school_others != "")
        {
            //$listResult->whereNull('learning.school_id');
            $listResult->where('learning.school_others','like','%'.$school_others.'%');
        }
        $listResult = $listResult->select('learning.id','learning.title','learning.date','learning.city as learning_city','learning.town as learning_town','learning.school_id','learning.school_others','school.city','school.town','school.school_name',DB::raw('DATE_FORMAT(learning.created_at, "%Y-%m-%d") as created_at'))
                                ->leftJoin('school','learning.school_id','=','school.id')
                                ->orderBy('learning.id','desc')
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
        $dataResult = DB::table('learning')
                            ->select('learning.id','learning.created_at','learning.title','learning.city as learning_city','learning.town as learning_town','learning.school_id','learning.school_others','learning.date','learning.member','learning.content','learning.file','learning.photo','school.city','school.town','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','learning.create_admin_id','=','member_admin.id')
                            ->leftJoin('school','learning.school_id','=','school.id')
                            ->where('learning.id',$id)
                            ->get();
        if($dataResult[0]['school_id'] == null)
        {
            $dataResult[0]['city'] = $dataResult[0]['learning_city'];
            $dataResult[0]['town'] = $dataResult[0]['learning_town'];
        }
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
        $dataResult = DB::table('learning')
                            ->select('learning.id','learning.created_at','learning.title','learning.city as learning_city','learning.town as learning_town','learning.school_id','learning.school_others','learning.date','learning.member','learning.content','learning.file','learning.photo','school.city','school.town','school.school_name','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','learning.create_admin_id','=','member_admin.id')
                            ->leftJoin('school','learning.school_id','=','school.id')
                            ->where('learning.id',$id)
                            ->get();
        if($dataResult[0]['school_id'] == null)
        {
            $dataResult[0]['city'] = $dataResult[0]['learning_city'];
            $dataResult[0]['town'] = $dataResult[0]['learning_town'];
            $dataResult[0]['school_name'] = $dataResult[0]['school_others'];
        }
        
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
                    'title' => 'string|required|max:50',
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
        //學校處理
        $school_id = Request::input('school_id');
        $school_others = Request::input('school_others');
        if($school_id == "-1")
        {
            $school_id = null;
        }
        else
        {
            $school_others = "";
        }

        //照片處理
        $photo = json_decode(Request::input('photo'),true);
        $photo_text = Request::input('photo_text');
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);
        $parameter = array($school_id,$photo,$school_others);
        try {
            DB::transaction(function($parameter) use($parameter){
                $id = DB::table('learning')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'title'=>Request::input('title'),
                                    'city'=>Request::input('city'),
                                    'town'=>Request::input('town'),
                                    'school_id'=>$parameter[0],
                                    'school_others'=>$parameter[2],
                                    'date'=>Request::input('date'),
                                    'member'=>Request::input('member'),
                                    'content'=>Request::input('content'),
                                    'file'=>Request::input('file'),
                                    'photo'=>$parameter[1],
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('learning')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'learning',
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
                    'title' => 'string|required|max:50',
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
        //學校處理
        $school_id = Request::input('school_id');
        $school_others = Request::input('school_others');
        if($school_id == "-1")
        {
            $school_id = null;
        }
        else
        {
            $school_others = "";
        }

        //照片處理
        $photo = json_decode(Request::input('photo'),true);
        $photo_text = Request::input('photo_text');
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);
        $parameter = array($school_id,$photo,$school_others);

        try {
            DB::transaction(function($parameter) use($parameter){
                $result_before = DB::table('school_tutor')
                                    ->where('id',Request::input('id'))
                                    ->get();
                
                DB::table('learning')
                    ->where('id',Request::input('id'))
                    ->update(['title'=>Request::input('title'),
                                'city'=>Request::input('city'),
                                'town'=>Request::input('town'),
                                'school_id'=>$parameter[0],
                                'school_others'=>$parameter[2],
                                'date'=>Request::input('date'),
                                'member'=>Request::input('member'),
                                'content'=>Request::input('content'),
                                'file'=>Request::input('file'),
                                'photo'=>$parameter[1],
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('learning')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'learning',
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

                $result_before = DB::table('learning')
                                    ->where('id',$id)
                                    ->get();
                DB::table('learning')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'learning',
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
