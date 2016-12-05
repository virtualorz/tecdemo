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
                                <th><span class="red">*</span>{{ trans('validation.attributes.school') }}</th>
                                <td>
                                    {{ trans('validation.attributes.city_name') }}:
                                    <select name="city" id="data-city" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($twCity as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['city']) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                    {{ trans('validation.attributes.town_name') }}:
                                    <select name="town" id="data-town" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($twTown[$dataResult['city']] as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['town']) selected @endif>{{$v[1]}}</option>
                                        @endforeach
                                    </select>
                                    {{ trans('validation.attributes.school') }}:
                                    <select name="school_id" id="data-school_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($schoolResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['school_id']) selected @endif>{{$v['school_name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.aboutus_teacher-photo') }}</th>
                                <td>
                                    <div id="data-photo" class="jqfuUploader" name="photo" data-name="photo" data-category="school_photo" data-file_ext="jpg|jpeg|png|gif" data-file_size="5MB" data-file_limit=1 data-img_scale="960_720" data-file_px="277_320" data-value="{{ $dataResult['photo'] }}"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.enable') }}</th>
                                <td>
                                    <label class="check"><input type="radio" name="enable" id="data_enable_1" class="iradio required" value="1" @if($dataResult['enable'] == 1) checked @endif /> {{ trans('enum.enable.1') }}</label>
                                    <label class="check"><input type="radio" name="enable" id="data_enable_0" class="iradio required" value="0" @if($dataResult['enable'] == 0) checked @endif /> {{ trans('enum.enable.0') }}</label> 
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