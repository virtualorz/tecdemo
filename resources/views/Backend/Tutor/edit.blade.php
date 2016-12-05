@extends('backend.layouts.master')
@expr(ViewHelper::plugin()->load('jqueryfileupload'))
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
                                <th><span class="red">*</span>{{ trans('validation.attributes.tutor_time') }}</th>
                                <td>
                                    <input type="text" name="date" id="data-date" class="form-control required datepicker" value="{{ $dataResult['date'] }}">
                                </td>
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
                                <th><span class="red">*</span>{{ trans('validation.attributes.tutor_member') }}</th>
                                <td>
                                    <textarea name="member" id="data-member" class="form-control required">{{ $dataResult['member'] }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.tutor_content') }}</th>
                                <td>
                                    <div name="content" id="content" class="btseditor" data-name="content" data-value="{{ $dataResult['content'] }}"></div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.file') }}</th>
                                <td>
                                    <div id="data-file" class="jqfuUploader" name="file" data-name="file" data-category="tutor_file" data-file_ext="jpg|jpeg|png|gif|docx|doc|pdf|ppt|pptx" data-file_size="10MB" data-value="{{ $dataResult['file'] }}"></div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.photo') }}</th>
                                <td>
                                    <div id="data-photo" class="jqfuUploader_photo" name="photo" data-name="photo" data-category="tutor_photo" data-file_ext="jpg|jpeg|png|gif" data-file_size="5MB" data-img_scale="960_720" data-file_px="360_270" data-value="{{ $dataResult['photo'] }}"></div>
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
        initBtsEditor();

        //照片文字處理
        var photo_text = {!! json_encode($photo_text) !!};
        var count = 0;
        $("#data-photo .jqfu-file").each(function(){
            $(this).find(".jqfu-file-col1").after('<td class="jqfu-file-col1-1">文字說明:<input type="text" name="photo_text[]" value="'+photo_text[count]+'"></td>');
            count++;
        });

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

        $("#data-town").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_school')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'city':$("#data-city").val(),'town':$("#data-town").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['school_name']+"</option>";
                    }
                    $("#data-school_id").html($html);
                    
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
    function initUpload() {
        $('.jqfuUploader').each(function () {
            new Jqfu($(this).attr('id'),{
                complete:function(e, config,data){}
            });
        });
        $('.jqfuUploader_photo').each(function () {
            new Jqfu($(this).attr('id'),{
                complete:function(e, config,data){
                    data.context.find('.jqfu-file-col1').after('<td class="jqfu-file-col1-1">文字說明:<input type="text" name="photo_text[]"></td>');
                }
            });
        });
    }
    function initBtsEditor() {
        $('.btseditor').each(function () {
            new BtsEditor($(this).attr('id'), {
                jqfu_file_size: "10 MB",
                jqfu_category: 'news',
                menu: ["pic", "text"]
            })
        });
    }
</script>
@endsection