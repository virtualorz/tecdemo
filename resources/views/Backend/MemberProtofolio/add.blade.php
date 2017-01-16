@extends('Backend.layouts.master')

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ trans('page.title.basic_data') }}
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
                                <td>{{ date('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.card_id_number') }}</th>
                                <td>
                                    <input type="text" name="card_id_number" id="data-card_id_number" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.id_number') }}</th>
                                <td>
                                    <input type="text" name="id_number" id="data-id_number" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-organize') }}</th>
                                <td>
                                    <select name="organize_id" id="data-organize" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($organizeResult as $k=>$v)
                                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-department') }}</th>
                                <td>
                                    <select name="department_id" id="data-department" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.department_title') }}</th>
                                <td>
                                    <input type="text" name="title" id="data-title" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.email') }}</th>
                                <td>
                                    <input type="text" name="email" id="data-email" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.password') }}</th>
                                <td>
                                    <input type="password" name="password" id="data-password" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.passwordR') }}</th>
                                <td>
                                    <input type="password" name="passwordR" id="data-passwordR" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.phone') }}</th>
                                <td>
                                    <input type="text" name="phone" id="data-phone" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    <select name="pi_list_id" id="data-pi" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.lab_phone') }}</th>
                                <td>
                                    <input type="text" name="lab_phone" id="data-lab_phone" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.id_type') }}</th>
                                <td>
                                    <select name="type" id="data-type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($member_typeResult as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    <input type="text" name="start_dt" id="data-start_dt" class="form-control required datepicker">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.limit_month') }}</th>
                                <td>
                                    <input type="number" name="limit_month" id="data-limit_month" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.permission') }}</th>
                                <td>
                                    @foreach($permission as $k=>$v)
                                        <label class="check"><input type="checkbox" name="permission[]" class="icheckbox ckbItem" value="{{$k}}" />{{$v}}</label>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td><span class="red">*</span>{{ trans('validation.attributes.enable') }}</td>
                                <td>
                                    <div>                  
                                        <label class="check"><input type="radio" name="enable" id="data_enable_1" class="iradio required" value="1" checked /> {{ trans('enum.enable.1') }}</label>
                                        <label class="check"><input type="radio" name="enable" id="data_enable_0" class="iradio required" value="0" /> {{ trans('enum.enable.0') }}</label> 
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ trans('page.title.journal_data') }}
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{ trans('validation.attributes.journal_type') }}</th>
                                <td>
                                    <select name="journal_type" id="data-journal_type" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($journal as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.release_dt') }}</th>
                                <td>
                                    <input type="text" name="release_dt" id="data-release_dt" class="form-control datepicker journal">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.topic') }}</th>
                                <td>
                                    <input type="text" name="topic" id="data-topic" class="form-control journal">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.journal_topic') }}</th>
                                <td>
                                    <input type="text" name="journal" id="data-journal" class="form-control journal">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.author') }}</th>
                                <td>
                                    <input type="text" name="author" id="data-author" class="form-control journal">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.url') }}</th>
                                <td>
                                    <input type="text" name="url" id="data-url" class="form-control journal">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ User::get('name', '') }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    {!! ViewHelper::button('submit') !!}
                                    {!! ViewHelper::button('cancel') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
</form>
@endsection


@section('script_include')
{!! ViewHelper::plugin()->renderJs() !!}
@endsection


@section('script')
<script type="text/javascript">

    $(document).ready(function () {
        initValidation();

        $("#data-organize").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_department')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-organize").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $("#data-department").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $("#data-department").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_pi')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-department").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $("#data-pi").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $("#data-journal_type").change(function(){
            if($(this).val() != "")
            {
                $(".journal").addClass("required");
            }
            else
            {
                $(".journal").removeClass("required");
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
</script>
@endsection