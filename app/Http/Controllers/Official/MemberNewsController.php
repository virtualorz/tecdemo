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

class MemberNewsController extends Controller {

    public function index() {
        $dataResult = DB::table('news')
                            ->select('news.id','news.title','school.city','school.school_name','school.photo',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->leftJoin('school','news.school_id','=','school.id')
                            ->where('news.school_id','=',User::Id())
                            ->orderBy('news.id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($dataResult->toJson(),true)['total']);
        
        if (count($dataResult) == 0)
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
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

        $dataEditResult = DB::table('news')
                            ->select('news.id','news.created_at','news.title','news.content','news.is_notice','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','news.create_admin_id','=','member_admin.id')
                            ->where('news.id',$id)
                            ->get();

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
                    'title' => 'string|required|max:50',
                    'content' => 'string|required',
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

        try {
            DB::transaction(function(){
                $id = DB::table('news')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'school_id'=>User::Id(),
                                    'is_notice'=>0,
                                    'title'=>Request::input('title'),
                                    'content'=>Request::input('content'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('news')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'news',
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
                    'title' => 'string|required|max:50',
                    'content' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }


        try {
            DB::transaction(function(){
                $result_before = DB::table('news')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('news')
                    ->where('id',Request::input('id'))
                    ->update(['title'=>Request::input('title'),
                                'content'=>Request::input('content'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('news')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'news',
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

                $result_before = DB::table('news')
                                    ->where('id',$id)
                                    ->get();
                DB::table('news')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'news',
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
