@extends('Backend.layouts.master')
@expr(ViewHelper::plugin()->load('btseditor'))

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(count($dataResult) > 0)
                <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.activity_id') }}</th>
                                <td>
                                    <input type="text" name="activity_id" id="data-activity_id" class="form-control required" value="{{ $dataResult['activity_id'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    <input type="text" name="start_dt" id="data-start_dt" class="form-control datepicker required" value="{{ $dataResult['start_dt'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.end_dt') }}</th>
                                <td>
                                    <input type="text" name="end_dt" id="data-end_dt" class="form-control datepicker" value="{{ $dataResult['end_dt'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.activity_name') }}</th>
                                <td>
                                    <input type="text" name="activity_name" id="data-activity_name" class="form-control required" value="{{ $dataResult['activity_name'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.activity_type') }}</th>
                                <td>
                                    <select name="activity_type_id" id="data-activity_type_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($activity_typeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['activity_type_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.related_plateform') }}</th>
                                <td>
                                    <select name="instrument_type" id="data-instrument_type" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($instrument_typeResult as $k=>$v)
                                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                    <input type="button" id="add_plateform" class="btn btn-default" value="{{trans('page.btn.add_list')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.related_plateform_add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.plateform_name') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="related_plateform_add">
                                            @foreach($relative_plateformResult as $k=>$v)
                                            <tr class='plateform_{{$v["id"]}}'>
                                                <td>{{$v["name"]}}<input type='hidden' class='relative_plateform' name='relative_plateform[]' value='{{$v["id"]}}'></td>
                                                <td><input type='button' class='btn btn-default del_plateform' value='刪除' data-id='{{$v["id"]}}'></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.level') }}</th>
                                <td>
                                    <select name="level" id="data-level" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($level as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['level']) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.time') }}</th>
                                <td>
                                    <input type="number" name="time" id="data-time" class="form-control required" value="{{ $dataResult['time'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.score') }}</th>
                                <td>
                                    <input type="number" name="score" id="data-score" class="form-control required" value="{{ $dataResult['score'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.pass_type') }}</th>
                                <td>
                                    <select name="pass_type" id="data-pass_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($pass_type as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['pass_type']) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.pass_condition') }}</th>
                                <td>
                                    <input type="text" name="pass_condition" id="data-pass_condition" class="form-control required" value="{{ $dataResult['pass_condition'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.open_instrument') }}</th>
                                <td>
                                    {{ trans('validation.attributes.instrument') }}
                                    <select name="instrument" id="data-instrument" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($instrumentResult as $k=>$v)
                                        <option value='{{$v["id"]}}' data-plateform='{{$v["instrument_type_id"]}}' >{{$v["name"]}}</option>
                                        @endforeach
                                    </select>
                                    {{ trans('validation.attributes.permission') }}
                                    <select name="permission" id="data-permission" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($permission as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                    <input type="button" id="add_instrument" class="btn btn-default" value="{{trans('page.btn.add_list')}}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.open_instrument_add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.instrument_name') }}</th>
                                                <th>{{ trans('validation.attributes.permission') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="instrument_add">
                                            @foreach($activity_instrumentResult as $k=>$v)
                                            <tr class='instrument_{{$v["instrument_id"]}}_{{$v["permission_id"]}}'>
                                                <td>{{$v["instrument_name"]}}<input type='hidden' class='instrument' name='instrument[]' value='{{$v["instrument_id"]}}' data-plateform='{{$v["instrument_type_id"]}}'></td>
                                                <td>{{$permission[$v["permission_id"]]}}<input type='hidden' class='instrument_permission' name='instrument_permission[]' value='{{$v["permission_id"]}}'></td>
                                                <td><input type='button' class='btn btn-default del_instrument' value='刪除'></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.activity_content') }}</th>
                                <td>
                                    <div name="content" id="content" class="btseditor" data-name="content" data-value="{{ $dataResult['content'] }}"></div>
                                </td>
                            </tr> 
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.status') }}</th>
                                <td>
                                    <div>                  
                                        <label class="check"><input type="radio" name="enable" id="data_enable_1" class="iradio required" value="1" @if($dataResult['enable'] == 1) checked @endif /> {{ trans('enum.display.1') }}</label>
                                        <label class="check"><input type="radio" name="enable" id="data_enable_0" class="iradio required" value="0" @if($dataResult['enable'] == 0) checked @endif /> {{ trans('enum.display.0') }}</label> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ $dataResult['created_admin_name'] }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" value="{{ $dataResult['id'] }}" />
                                    {!! ViewHelper::button('submit') !!}
                                    {!! ViewHelper::button('cancel') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                @else
                <div align="center">{{ trans('message.info.norecord') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('script_include')
{!! ViewHelper::plugin()->renderJs() !!}
@endsection


@section('script')
<script src="{{ asset('assets/official/js/jquery.blockUI.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function () {
        initValidation();
        initBtsEditor();

        $(document).on("click",".del_plateform",function(){
            $(this).parent().parent().remove();
            var remove_platefprm_id = $(this).attr('data-id');
            $(".instrument").each(function(){
                if($(this).attr('data-plateform') == remove_platefprm_id)
                {
                    $(this).parent().parent().remove();
                }
            });
            //更新平台儀器資料
            var relative_plateform = [];
            $(".relative_plateform").each(function() {
                relative_plateform.push($(this).val());
            });
                
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_instrument')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':relative_plateform,'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                        
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $("#data-instrument").html($html);
                        
                }
            }
            $.ajax(ajaxProp);
        });

        $(document).on("click",".del_instrument",function(){
            $(this).parent().parent().remove();
        });


        $("#add_plateform").click(function(){
            if(typeof $("#related_plateform_add .plateform_"+$("#data-instrument_type").val()).html() == "undefined" && $("#data-instrument_type").val()!= "")
            {
                var html="<tr class='plateform_"+$("#data-instrument_type").val()+"'><td>"+$("#data-instrument_type :selected").text()+"<input type='hidden' class='relative_plateform' name='relative_plateform[]' value='"+$("#data-instrument_type").val()+"'></td><td><input type='button' class='btn btn-default del_plateform' value='刪除' data-id='"+$("#data-instrument_type").val()+"'></td>";
                $("#related_plateform_add").find(".dataTables_empty").parent().remove();
                $("#related_plateform_add").append(html);
                $("#data-instrument_type").val("");

                //更新平台儀器資料
                var relative_plateform = [];
                $(".relative_plateform").each(function() {
                    relative_plateform.push($(this).val());
                });
                
                var ajaxProp = {
                    url: "{{ Sitemap::node()->getChildren('get_instrument')->getUrl() }}",
                    type: "get",
                    dataType: "json",
                    data: {'id':relative_plateform,'_token':csrf_token},
                    error: function (jqXHR, textStatus, errorThrown) {
                        
                    },
                    success: function (response) {
                        $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                        for(var key in response)
                        {
                            $html += "<option value='"+response[key]['id']+"' data-plateform='"+response[key]['instrument_type_id']+"' >"+response[key]['name']+"</option>";
                        }
                        $("#data-instrument").html($html);
                        
                    }
                }
                $.ajax(ajaxProp);
            }
        });

        $("#add_instrument").click(function(){
            if(typeof $("#instrument_add .instrument_"+$("#data-instrument").val()+"_"+$("#data-permission").val()).html() == "undefined" && $("#data-instrument").val()!= "" && $("#data-permission").val()!= "")
            {
                var html="<tr class='instrument_"+$("#data-instrument").val()+"_"+$("#data-permission").val()+"'><td>"+$("#data-instrument :selected").text()+"<input type='hidden' class='instrument' name='instrument[]' value='"+$("#data-instrument").val()+"' data-plateform='"+$("#data-instrument :selected").attr('data-plateform')+"'></td><td>"+$("#data-permission :selected").text()+"<input type='hidden' class='instrument_permission' name='instrument_permission[]' value='"+$("#data-permission").val()+"'></td><td><input type='button' class='btn btn-default del_instrument' value='刪除'></td></tr>";
                $("#instrument_add").find(".dataTables_empty").parent().remove();
                $("#instrument_add").append(html);
                $("#data-instrument").val("");
                $("#data-permission").val("");
            }
        });

    });
    
    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                }) === false) {
                    return false;
                }
            }
        });
    }

    function initBtsEditor() {
        $('.btseditor').each(function () {
            new BtsEditor($(this).attr('id'), {
                jqfu_file_size: "10 MB",
                jqfu_category: 'activity',
                menu: ["pic", "text", "video"]
            })
        });
    }
</script>
@endsection