@extends('Backend.layouts.master')

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
                                <th><span class="red">*</span>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required" value="{{ $dataResult['name'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.card_id_number') }}</th>
                                <td>
                                    <input type="text" name="card_id_number" id="data-card_id_number" class="form-control required" value="{{ $dataResult['card_id_number'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-organize') }}</th>
                                <td>
                                    <select name="organize_id" id="data-organize" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($organizeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['organize_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-department') }}</th>
                                <td>
                                    <select name="department_id" id="data-department" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($departmentResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['department_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.department_title') }}</th>
                                <td>
                                    <input type="text" name="title" id="data-title" class="form-control" value="{{ $dataResult['title'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.email') }}</th>
                                <td>
                                    <input type="text" name="email" id="data-email" class="form-control required" value="{{ $dataResult['email'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.password') }}</th>
                                <td>
                                    <input type="password" name="password" id="data-password" class="form-control required" value="{{ $dataResult['password'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.passwordR') }}</th>
                                <td>
                                    <input type="password" name="passwordR" id="data-passwordR" class="form-control required" value="{{ $dataResult['password'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.phone') }}</th>
                                <td>
                                    <input type="text" name="phone" id="data-phone" class="form-control required" value="{{ $dataResult['phone'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    <select name="pi_list_id" id="data-pi" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($piResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['pi_list_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.lab_phone') }}</th>
                                <td>
                                    <input type="text" name="lab_phone" id="data-lab_phone" class="form-control required" value="{{ $dataResult['lab_phone'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.id_type') }}</th>
                                <td>
                                    <select name="type" id="data-type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($member_typeResult as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['type']) selected @endif >{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    <input type="text" name="start_dt" id="data-start_dt" class="form-control required datepicker" value="{{ $dataResult['start_dt'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.limit_month') }}</th>
                                <td>
                                    <input type="number" name="limit_month" id="data-limit_month" class="form-control required" value="{{ $dataResult['limit_month'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.permission') }}</th>
                                <td>
                                    @foreach($permission as $k=>$v)
                                        <label class="check"><input type="checkbox" name="permission[]" class="icheckbox ckbItem" value="{{$k}}" @if(in_array($k,$permissionResult)) checked @endif />{{$v}}</label>
                                    @endforeach
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