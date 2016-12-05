@extends('backend.layouts.master')
@expr(ViewHelper::plugin()->load('jqueryfileupload'))

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
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
                                <td>{{ date('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.location') }}</th>
                                <td>
                                    <select name="location" id="data-location" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($location as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.city_name') }}</th>
                                <td>
                                    <select name="city" id="data-city" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($twCity as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.town_name') }}</th>
                                <td>
                                    <select name="town" id="data-town" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.name-school') }}</th>
                                <td>
                                    <input type="text" name="school_name" id="data-school_name" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.admin-account') }}</th>
                                <td>
                                    <input type="text" name="account" id="data-account" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.admin-password') }}</th>
                                <td>
                                    <input type="password" name="password" id="data-password" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.admin-password_confirmation') }}</th>
                                <td>
                                    <input type="password" name="passwordR" id="data-passwordR" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.aboutus_teacher-photo') }}</th>
                                <td>
                                    <div id="data-photo" class="jqfuUploader" name="photo" data-name="photo" data-category="school_photo" data-file_ext="jpg|jpeg|png|gif" data-file_size="5MB" data-file_limit=1 data-img_scale="960_720" data-file_px="277_320" ></div>
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
                </form>
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
        initUpload();

        $("#data-city").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_town')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-city").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+key+"'>"+response[key][1]+"</option>";
                    }
                    $("#data-town").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $(".btnSubmit").click(function(){
            if($("#data-password").val() != $("#data-passwordR").val())
            {
                alert("密碼輸入錯誤");
                return false;
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
    function initUpload() {
        $('.jqfuUploader').each(function () {
            new Jqfu($(this).attr('id'),{});
        });
    }
</script>
@endsection