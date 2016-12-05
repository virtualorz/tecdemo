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

class MemberExecuteController extends Controller {

    public function index() {
        $dataResult = DB::table('school_execute')
                            ->select('school_execute.id','school.city','school.town','school.school_name','school.photo',DB::raw('DATE_FORMAT(school_execute.date, "%Y.%m.%d") as date'))
                            ->leftJoin('school','school_execute.school_id','=','school.id')
                            ->where('school_execute.school_id','=',User::Id())
                            ->orderBy('school_execute.id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($dataResult->toJson(),true)['total']);
        
        if (count($dataResult) == 0)
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','school.photo')
                            ->where('id','=',User::Id())
                            ->get();
        }
        else
        {
            $dataResult = json_decode($dataResult->toJson(),true)['data'];
        }
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', User::Id());
        $this->view->with('twCity',Config::get('data.twCity'));
        
        return $this->view;
    }

    public function add() {
        $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',User::Id())
                            ->get();
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', User::Id());
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',User::Id())
                            ->get();
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $dataEditResult = DB::table('school_execute')
                            ->select('id','date','member','content','file','photo')
                            ->where('id',$id)
                            ->get();
        //照片處理
        $photo = json_decode($dataEditResult[0]['photo'],true);
        $photo_text = array();
        foreach($photo as $k=>$v)
        {
            array_push($photo_text,$photo[$k]['text']);
            unset($photo[$k]['text']);
        }
        $dataEditResult[0]['photo'] = FileUpload::getFiles(json_encode($photo));
        $dataEditResult[0]['photo_text'] = $photo_text;

        //檔案處理
        $file = json_decode($dataEditResult[0]['file'],true);
        $dataEditResult[0]['file'] = $file;

        $this->view->with('dataEditResult', $dataEditResult[0]);
        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', User::Id());
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'date' => 'string|required',
                    'member' => 'string|required|max:200',
                    'content' => 'string|required',
                    'file' => 'array|required',
                    'photo' => 'array|required',
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

        $file = json_encode(Request::input('file'));
        $photo = Request::input('photo');
        $photo_text = Request::input('photo_text');log::error($photo_text);
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);
        $param = array($file,$photo);

        try {
            DB::transaction(function($param) use($param) {
                $id = DB::table('school_execute')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'school_id'=>User::Id(),
                                    'date'=>Request::input('date'),
                                    'member'=>Request::input('member'),
                                    'content'=>Request::input('content'),
                                    'file'=>$param[0],
                                    'photo'=>$param[1],
                            )
                        );
                $result_after = DB::table('school_execute')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'school_execute',
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
                    'date' => 'string|required',
                    'member' => 'string|required|max:200',
                    'content' => 'string|required',
                    'file' => 'array|required',
                    'photo' => 'array|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $file = json_encode(Request::input('file'));
        $photo = Request::input('photo');
        $photo_text = Request::input('photo_text');
        foreach($photo as $k=>$v)
        {
            $photo[$k]['text'] = $photo_text[$k];
        }
        $photo = json_encode($photo);
        $param = array($file,$photo);


        try {
            DB::transaction(function($param) use($param){
                $result_before = DB::table('school_execute')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('school_execute')
                    ->where('id',Request::input('id'))
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'date'=>Request::input('date'),
                                'member'=>Request::input('member'),
                                'content'=>Request::input('content'),
                                'file'=>$param[0],
                                'photo'=>$param[1]
                    ]);
                $result_after = DB::table('school_execute')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'school_execute',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => User::id()
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

                $result_before = DB::table('school_execute')
                                    ->where('id',$id)
                                    ->get();
                DB::table('school_execute')
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
