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
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-organize') }}</th>
                                <td>
                                    <select name="organize_id" id="data-organize_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($organizeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['organize_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                        <option value="-1">{{trans('page.text.other')}}</option>
                                    </select>
                                    <input type="text" name="other_organize" id="data-other_organize" class="form-control" style="display:none">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.system-department') }}</th>
                                <td>
                                    <select name="department_id" id="data-department_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($departmentResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['department_id']) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                        <option value="-1">{{trans('page.text.other')}}</option>
                                    </select>
                                    <input type="text" name="other_department" id="data-other_department" class="form-control" style="display:none">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required" value="{{ $dataResult['name'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.email') }}</th>
                                <td>
                                    <input type="text" name="email" id="data-email" class="form-control required" value="{{ $dataResult['email'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.phone') }}</th>
                                <td>
                                    <input type="text" name="phone" id="data-phone" class="form-control required" value="{{ $dataResult['phone'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.name-contact') }}</th>
                                <td>
                                    <input type="text" name="contact_name" id="data-contact_name" class="form-control required" value="{{ $dataResult['contact_name'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.phone-contact') }}</th>
                                <td>
                                    <input type="text" name="contact_phone" id="data-contact_phone" class="form-control required" value="{{ $dataResult['contact_phone'] }}">
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
        $("#data-organize_id").change(function(){
            if($(this).val() == '-1')
            {
                $("#data-other_organize").show();
            }
            else
            {
                $("#data-other_organize").val('');
                $("#data-other_organize").hide();
            }
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_department')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-organize_id").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $html += "<option value='-1'>{{trans('page.text.other')}}</option>";
                    $("#data-department_id").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });
        $("#data-department_id").change(function(){
            if($(this).val() == '-1')
            {
                $("#data-other_department").show();
            }
            else
            {
                $("#data-other_department").val('');
                $("#data-other_department").hide();
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