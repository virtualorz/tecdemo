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

class MemberPlanController extends Controller {

    public function index() {
        $dataResult = DB::table('school_plan')
                            ->select('school_plan.id','school_plan.topic','school_plan.idea','school_plan.plan','school_plan.file','school_plan.contact_name','school_plan.contact_tel','school_plan.contact_email','school_plan.related_group','school_plan.related_url','school.city','school.school_name','school.photo')
                            ->leftJoin('school','school_plan.school_id','=','school.id')
                            ->where('school_id','=',User::Id())
                            ->get();
        if (count($dataResult) > 0)
        {
            //$dataResult[0]['plan'] = json_decode($dataResult[0]['plan'], true);
            $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);
            $dataResult[0]['related_group'] = json_decode($dataResult[0]['related_group'], true);
            $dataResult[0]['related_url'] = json_decode($dataResult[0]['related_url'], true);
        }
        else
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',User::Id())
                            ->get();
            $dataResult[0]['topic'] = "";
            $dataResult[0]['idea'] = "";
            $dataResult[0]['plan'] = "[]";
            $dataResult[0]['file'] = [];
            $dataResult[0]['contact_name'] = "";
            $dataResult[0]['contact_tel'] = "";
            $dataResult[0]['contact_email'] = "";
            $dataResult[0]['related_group'] = [];
            $dataResult[0]['related_url'] = [];
            $dataResult[0]['id'] = 0;
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

    ##
    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'topic' => 'string|required|max:100',
                    'idea' => 'string|required',
                    'plan' => 'string|required',
                    'contact_name' => 'string|required|max:20',
                    'contact_tel' => 'string|required|max:20',
                    'contact_email' => 'string|required|max:384',
                    'related_group' => 'array|required',
                    'related_url_name' => 'array|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $file = json_encode(Request::input('file'));
        $related_group = json_encode(Request::input('related_group'));
        
        $related_url_name = Request::input('related_url_name');
        $related_url_web = Request::input('related_url_web');
        $related_url = array();
        foreach($related_url_name as $k=>$v)
        {
            array_push($related_url,array('name'=>$v,'url'=>$related_url_web[$k]));
        }
        $related_url = json_encode($related_url);
        $param = array($file,$related_group,$related_url);


        try {
            DB::transaction(function($param) use($param){
                if(Request::input('id') != 0)
                {//edit
                    $result_before = DB::table('school_plan')
                                        ->where('id',Request::input('id'))
                                        ->get();
                    DB::table('school_plan')
                        ->where('id',Request::input('id'))
                        ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                    'topic'=>Request::input('topic'),
                                    'idea'=>Request::input('idea'),
                                    'plan'=>Request::input('plan'),
                                    'file'=>$param[0],
                                    'contact_name'=>Request::input('contact_name'),
                                    'contact_tel'=>Request::input('contact_tel'),
                                    'contact_email'=>Request::input('contact_email'),
                                    'related_group'=>$param[1],
                                    'related_url'=>$param[2]
                        ]);
                    $result_after = DB::table('school_plan')
                                        ->where('id',Request::input('id'))
                                        ->get();
                    DBProcedure::writeLog([
                        'table' => 'school_plan',
                        'operator' => DBOperator::OP_UPDATE,
                        'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                        'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                        'member_id' => User::id()
                    ]);
                }
                else
                {//add
                    $id = DB::table('school_plan')
                            ->insertGetId(
                                array('created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                        'school_id'=>User::Id(),
                                        'topic'=>Request::input('topic'),
                                        'idea'=>Request::input('idea'),
                                        'plan'=>Request::input('plan'),
                                        'file'=>$param[0],
                                        'contact_name'=>Request::input('contact_name'),
                                        'contact_tel'=>Request::input('contact_tel'),
                                        'contact_email'=>Request::input('contact_email'),
                                        'related_group'=>$param[1],
                                        'related_url'=>$param[2]
                                )
                            );
                    $result_after = DB::table('school_plan')
                                    ->where('id',$id)
                                    ->get();
                    DBProcedure::writeLog([
                        'table' => 'school_plan',
                        'operator' => DBOperator::OP_INSERT,
                        'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                        'admin_id' => User::id()
                    ]);
                }
                
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
}
