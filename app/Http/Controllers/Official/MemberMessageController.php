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
use Crypt;
use Sitemap;
use SitemapAccess;

class MemberMessageController extends Controller {

    public function index() {
        $listResult = DB::table('member_notice_log')
                            ->select('member_notice_log.member_notice_log_id',
                                        DB::raw('DATE_FORMAT(member_notice_log.created_at, "%Y.%m.%d") as created_at'),
                                        'member_notice_log.uid',
                                        'member_notice_log.salt',
                                        'member_notice_log.title',
                                        'member_notice_log.email',
                                        'member_notice_log.is_read',
                                        'member_admin.name as create_admin_name')
                            ->leftJoin('member_admin','member_notice_log.create_admin_id','=','member_admin.id')
                            ->where('member_data_id','=',User::Id())
                            ->orderBy('created_at','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        
        return $this->view;
    }

    public function detail() {
        $id = explode('-',Route::input('id'));
        if(isset($id[2]))
        {
            $login_uid = explode('_',Crypt::decrypt($id[2]));
            $dataResult = DB::table('member_data')
                            ->select('id','email','name','title','start_dt','limit_month','enable','pi_list_id')
                            ->where('email',$login_uid[0])
                            ->where('password',$login_uid[1])
                            ->first();
            if(count($dataResult) !=0)
            {
                if($dataResult['enable'] == 0 || strtotime($dataResult['start_dt']) > strtotime(date('Y-m-d')) || strtotime("+".$dataResult['limit_month']." month",strtotime($dataResult['start_dt'])) < strtotime(date('Y-m-d')))
                {
                    return redirect('/');
                }

                //取得儀器使用權限
                $instrumentPermission = array();
                $instrumentResult = DB::table('member_permission')
                            ->select('permission')
                            ->where('member_data_id',$dataResult['id'])
                            ->get();
                foreach($instrumentResult as $k=>$v)
                {
                    array_push($instrumentPermission,$v['permission']);
                }

                $dataUser = [
                    'id' => $dataResult['id'],
                    'account' => $dataResult['email'],
                    'name' => $dataResult['name'],
                    'title' => $dataResult['title'],
                    'pi_list_id' => $dataResult['pi_list_id'],
                    'permission' => Sitemap::getPermissionAll('official', SitemapAccess::SUPER_REQUIRED),
                    'instrumentPermission' => $instrumentPermission,
                ];

                User::login($dataUser);
                User::cacheClear();
            }
            else
            {
                return redirect('/');
            }
        }
        else if($login_uid == '' && User::id() == null)
        {
            return redirect('login');
        }
        //設定為已讀
        DB::table('member_notice_log')
            ->where('uid',$id[0])
            ->where('salt',$id[1])
            ->where('member_data_id',User::id())
            ->update(['is_read'=>1]);
        $dataResult = DB::table('member_notice_log')
                            ->select('member_notice_log.*',
                                    DB::raw('DATE_FORMAT(member_notice_log.created_at, "%Y.%m.%d") as created_at'))
                            ->where('uid',$id[0])
                            ->where('salt',$id[1])
                            ->where('member_data_id',User::id())
                            ->get();
        if(count($dataResult) > 0)
        {
            //內容
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }

        $this->view->with('dataResult', $dataResult[0]);
        
        return $this->view;
    }

    ##

    public function ajax_add() {
        $validator = Validator::make(Request::all(), [
                    'journal_type' => 'integer|required',
                    'release_dt' => 'date|required',
                    'topic' => 'string|required|max:50',
                    'journal' => 'string|required|max:50',
                    'author' => 'string|required',
                    'url' => 'string|required|max:256',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $id = User::id();

                $journal_id = DB::table('member_journal')
                        ->select('member_journal_id')
                        ->where('member_data_id',$id)
                        ->orderBy('member_journal_id','desc')
                        ->limit(1)
                        ->get();
                if(!isset($journal_id[0]['member_journal_id']))
                {
                    $journal_id = 0;
                }
                else
                {
                    $journal_id = $journal_id[0]['member_journal_id'];
                }
                $journal_id = intval($journal_id)+1;
                DB::table('member_journal')
                        ->insert(array(
                            'member_data_id'=>$id,
                            'member_journal_id'=>$journal_id,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'type'=>Request::input('journal_type'),
                            'release_dt'=>Request::input('release_dt'),
                            'topic'=>Request::input('topic'),
                            'journal'=>Request::input('journal'),
                            'author'=>Request::input('author'),
                            'url'=>Request::input('url')
                        ));

                $result_after = DB::table('member_journal')
                                    ->where('member_data_id',$id)
                                    ->where('member_journal_id',$journal_id)
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_journal',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => $id
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
                    'id' => 'integer|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            $id = Request::input('id', '0');;

                $result_before = DB::table('member_journal')
                                    ->where('member_journal_id',$id)
                                    ->where('member_data_id',User::id())
                                    ->get();
                DB::table('member_journal')
                    ->where('member_journal_id',$id)
                    ->where('member_data_id',User::id())
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'member_journal',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'member_id' => User::id()
                ]);
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
