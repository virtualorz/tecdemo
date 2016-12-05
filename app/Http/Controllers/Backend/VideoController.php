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

class VideoController extends Controller {

    public function index() {
        $title = Request::input('title', '');

        $listResult = DB::table('video');
        if($title != "")
        {
            $listResult->where('title','like','%'.$title.'%');
        }

        $listResult = $listResult->select('id','title',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
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
        $dataResult = DB::table('video')
                            ->select('video.id','video.created_at','video.title','video.date','video.url','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','video.create_admin_id','=','member_admin.id')
                            ->where('video.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('video')
                            ->select('video.id','video.created_at','video.title','video.date','video.url','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','video.create_admin_id','=','member_admin.id')
                            ->where('video.id',$id)
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'title' => 'string|required|max:50',
                    'date' => 'string|required',
                    'url' => 'string|required|max:100',
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

        //處理youtube 影片連結
        $url_ready = true;
        if(strpos(Request::input('url'),"youtu.be/")!== false || strpos(Request::input('url'),"www.youtube.com/")!== false)
        {
            $uid="";
            if(strpos(Request::input('url'),"youtu.be/")!== false)
            {
                $tmp = explode("youtu.be/",Request::input('url'));
                if(isset($tmp[1]))
                {
                    $uid = $tmp[1];
                }
                else
                {
                    $url_ready = false;
                }
            }
            else if(strpos(Request::input('url'),"www.youtube.com/")!== false)
            {
                $tmp = explode("?v=",Request::input('url'));
                if(isset($tmp[1]))
                {
                    $tmp1 = explode("&",$tmp[1]);
                    $uid = $tmp1[0];
                }
                else
                {
                    $url_ready = false;
                }
            }
        }
        else
        {
            $url_ready = false;
        }

        if($url_ready === false)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'][] = trans('message.error.youtube_url_error');
            return $this->view;
        }
        $youtube_url = "https://www.youtube.com/embed/".$uid;

        try {
            DB::transaction(function($youtube_url) use ($youtube_url){
                $id = DB::table('video')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'date'=>Request::input('date'),
                                    'title'=>Request::input('title'),
                                    'url'=>$youtube_url,
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('video')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'video',
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
                    'date' => 'string|required',
                    'url' => 'string|required|max:100',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //處理youtube 影片連結
        $url_ready = true;
        if(strpos(Request::input('url'),"youtu.be/")!== false || strpos(Request::input('url'),"www.youtube.com/")!== false)
        {
            $uid="";
            if(strpos(Request::input('url'),"youtu.be/")!== false)
            {
                $tmp = explode("youtu.be/",Request::input('url'));
                if(isset($tmp[1]))
                {
                    $uid = $tmp[1];
                }
                else
                {
                    $url_ready = false;
                }
            }
            else if(strpos(Request::input('url'),"www.youtube.com/")!== false)
            {
                $tmp = explode("?v=",Request::input('url'));
                if(isset($tmp[1]))
                {
                    $tmp1 = explode("&",$tmp[1]);
                    $uid = $tmp1[0];
                }
                else
                {
                    $url_ready = false;
                }
            }
        }
        else
        {
            $url_ready = false;
        }

        if($url_ready === false)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'][] = trans('message.error.youtube_url_error');
            return $this->view;
        }
        $youtube_url = "https://www.youtube.com/embed/".$uid;


        try {
            DB::transaction(function($youtube_url) use ($youtube_url){
                $result_before = DB::table('video')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('video')
                    ->where('id',Request::input('id'))
                    ->update(['date'=>Request::input('date'),
                                'title'=>Request::input('title'),
                                'url'=>$youtube_url,
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('video')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'video',
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

                $result_before = DB::table('video')
                                    ->where('id',$id)
                                    ->get();
                DB::table('video')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'video',
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
