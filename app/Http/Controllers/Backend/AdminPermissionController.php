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

class AdminPermissionController extends Controller {

    public function index() {
        $listResult = DB::table('member_admin_permission')
                            ->select('id','name','enable',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
                            ->orderBy('id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function add() {
        $treePermission = [];

        $nodeAllChildren = Sitemap::node('backend')->getChildren(null, ['menu' => true, 'permission' => function($k) {
                return $k <= SitemapAccess::ACCESS_REQUIRED;
            }]);
        foreach ($nodeAllChildren as $k => $v) {
            $treePerm = Sitemap::getPermissionTree($v);

            if (count($treePerm) > 0) {
                $treePermission[] = $treePerm;
            }
        }

        $this->view->with('treePermissionJson', json_encode($treePermission));

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $treePermission = [];
        $treeAutoFunctionSelectedJson = '[]';
        $dataResult = DB::table('member_admin_permission')
                            ->select('member_admin_permission.id','member_admin_permission.created_at','member_admin_permission.name','member_admin_permission.content','member_admin_permission.enable','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','member_admin_permission.create_admin_id','=','member_admin.id')
                            ->where('member_admin_permission.id',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $treePermissionSelectedJson = $dataResult[0]['content'];
        }

        $nodeAllChildren = Sitemap::node('backend')->getChildren(null, ['menu' => true, 'permission' => function($k) {
                return $k <= SitemapAccess::ACCESS_REQUIRED;
            }]);
        foreach ($nodeAllChildren as $k => $v) {
            $treePerm = Sitemap::getPermissionTree($v);

            if (count($treePerm) > 0) {
                $treePermission[] = $treePerm;
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('treePermissionJson', json_encode($treePermission));
        $this->view->with('treePermissionSelectedJson', $treePermissionSelectedJson);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $treePermission = [];
        $treeAutoFunctionSelectedJson = '[]';
        $dataResult = DB::table('member_admin_permission')
                            ->select('member_admin_permission.id','member_admin_permission.created_at','member_admin_permission.name','member_admin_permission.content','member_admin_permission.enable','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','member_admin_permission.create_admin_id','=','member_admin.id')
                            ->where('member_admin_permission.id',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $treePermissionSelectedJson = $dataResult[0]['content'];
        }

        $nodeAllChildren = Sitemap::node('backend')->getChildren(null, ['menu' => true, 'permission' => function($k) {
                return $k <= SitemapAccess::ACCESS_REQUIRED;
            }]);
        foreach ($nodeAllChildren as $k => $v) {
            $treePerm = Sitemap::getPermissionTree($v);

            if (count($treePerm) > 0) {
                $treePermission[] = $treePerm;
            }
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('treePermissionJson', json_encode($treePermission));
        $this->view->with('treePermissionSelectedJson', $treePermissionSelectedJson);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:20',
                    'content' => 'string|required',
                    'enable' => 'integer|required',
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
        $dataPermission = json_encode(array_filter(explode(',', Request::input('content', ''))));

        try {
            DB::transaction(function($dataPermission) use ($dataPermission){
                $id = DB::table('member_admin_permission')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'content'=>$dataPermission,
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('member_admin_permission')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'member_admin_permission',
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
                    'name' => 'string|required|max:20',
                    'content' => 'string|required',
                    'enable' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $dataPermission = json_encode(array_filter(explode(',', Request::input('content', ''))));


        try {
            DB::transaction(function($dataPermission) use($dataPermission){
                $result_before = DB::table('member_admin_permission')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('member_admin_permission')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'content'=>$dataPermission,
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('member_admin_permission')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_admin_permission',
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

                $result_before = DB::table('member_admin_permission')
                                    ->where('id',$id)
                                    ->get();
                DB::table('member_admin_permission')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'member_admin_permission',
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
