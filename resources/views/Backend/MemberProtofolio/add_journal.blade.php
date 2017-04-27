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
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.journal_type') }}</th>
                                <td>
                                    <select name="journal_type" id="data-journal_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($journal as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.release_dt') }}</th>
                                <td>
                                    <input type="text" name="release_dt" id="data-release_dt" class="form-control datepicker required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.topic') }}</th>
                                <td>
                                    <input type="text" name="topic" id="data-topic" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.journal_topic') }}</th>
                                <td>
                                    <input type="text" name="journal" id="data-journal" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.author') }}</th>
                                <td>
                                    <input type="text" name="author" id="data-author" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.url') }}</th>
                                <td>
                                    <input type="text" name="url" id="data-url" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ User::get('name', '') }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" id="data-id" value="{{ $id }}">
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