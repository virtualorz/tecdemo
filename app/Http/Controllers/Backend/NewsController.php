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

class NewsController extends Controller {

    public function index() {
        $title = Request::input('title', '');

        $listResult = DB::table('news');
        if($title != "")
        {
            $listResult->where('title','like','%'.$title.'%');
        }

        $listResult = $listResult->select('id','title',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
                                    ->whereNull('school_id')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function add() {

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('news')
                            ->select('news.id','news.created_at','news.title','news.content','news.is_notice','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','news.create_admin_id','=','member_admin.id')
                            ->where('news.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('news')
                            ->select('news.id','news.created_at','news.title','news.content','news.is_notice','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','news.create_admin_id','=','member_admin.id')
                            ->where('news.id',$id)
                            ->get();
        if (count($dataResult[0]) > 0) {
            //$dataResult[0][0]['created_at'] = (new DateTime($dataResult[0][0]['created_at']))->format('Y/m/d');
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'title' => 'string|required|max:50',
                    'content' => 'string|required',
                    'is_notice' => 'integer|required',
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
                                    'school_id'=>NULL,
                                    'is_notice'=>Request::input('is_notice'),
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
                    'is_notice' => 'integer|required',
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
                    ->update(['is_notice'=>Request::input('is_notice'),
                                'title'=>Request::input('title'),
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
