@extends('backend.layouts.master')

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
                                <th><span class="red">*</span>{{ trans('validation.attributes.name-target') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required" value="{{$dataResult['name']}}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.dt-start') }}</th>
                                <td>
                                    <input type="text" name="start_dt" id="data-start_dt" class="form-control required datepicker" value="{{$dataResult['start_dt']}}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.dt-end') }}</th>
                                <td>
                                    <input type="text" name="end_dt" id="data-end_dt" class="form-control required datepicker" value="{{$dataResult['end_dt']}}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.item-main') }}</th>
                                <td>
                                    <input type="text" name="item_main_insert" id="data-item_main_insert" class="form-control">
                                    <button type="button" class="btn btn-default" id="item_main_add">加入</button>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.item-main-add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.item') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item_main_table">
                                        @foreach($dataResult['item'] as $k=>$v)
                                            <tr>
                                                <td>{{$v['value']}}</td>
                                                <td>
                                                    <input type='hidden' name='item_main_key[]' value='{{$k}}'>
                                                    <input type='hidden' name='item_main[]' value='{{$v["value"]}}'>
                                                    <button type='button' class='btn btn-default item_main_del' data-count='{{$k}}'>刪除</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.item-sub') }}</th>
                                <td>
                                    所屬主項目：
                                    <select name="item_sub_select" id="data-item_sub_select" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($dataResult['item'] as $k=>$v)
                                        <option value='{{$k}}'>{{$v["value"]}}</option>
                                        @endforeach
                                    </select>
                                    子項目名稱：
                                    <input type="text" name="item_insert" id="data-item_sub_insert" class="form-control">
                                    <button type="button" class="btn btn-default" id="item_sub_add">加入</button>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.item-sub-add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.item-main') }}</th>
                                                <th>{{ trans('validation.attributes.item-sub') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item_sub_table">
                                        @foreach($dataResult['item'] as $k=>$v)
                                            @foreach($v['sub_item'] as $k1=>$v1)
                                            <tr>
                                                <td>{{$v['value']}}</td>
                                                <td>{{$v1}}</td>
                                                <td>
                                                    <input type='hidden' name='item_sub_rel_main[]' value='{{$k}}'>
                                                    <input type='hidden' name='item_sub[]' value='{{$v1}}'>
                                                    <button type='button' class='btn btn-default item_sub_del' data-count='{{$k}}'>刪除</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
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
        var count = parseInt("{{count($dataResult['item'])}}");
        $(document).on("click",".item_main_del",function(){
            var cour_count = $(this).attr('data-count');
            $("#data-item_sub_select option[value='"+$(this).attr('data-count')+"']").remove();
            $(".item_sub_del").each(function(){
                if($(this).attr('data-count') == cour_count)
                {
                    $(this).parent().parent().remove();
                }
            });
            $(this).parent().parent().remove();
        });
        $(document).on("click",".item_sub_del",function(){
            $(this).parent().parent().remove();
        });
        $("#item_main_add").click(function(){
            if($("#data-item_main_insert").val() != "")
            {
                $("#DataTables_Table_1").find(".dataTables_empty").parent().remove();
                $("#item_main_table").append("<tr><td>"+$("#data-item_main_insert").val()+"</td><td><input type='hidden' name='item_main_key[]' value='"+count+"'><input type='hidden' name='item_main[]' value='"+$("#data-item_main_insert").val()+"'><button type='button' class='btn btn-default item_main_del' data-count='"+count+"'>刪除</button></td></tr>");
                $("#data-item_sub_select").append("<option value='"+count+"'>"+$("#data-item_main_insert").val()+"</option>");
                $("#data-item_main_insert").val("");
                count++;
            }
        });

        $("#item_sub_add").click(function(){
            if($("#data-item_sub_insert").val() != "" && $("#data-item_sub_select").val() != "")
            {
                $("#DataTables_Table_2").find(".dataTables_empty").parent().remove();
                $("#item_sub_table").append("<tr><td>"+$("#data-item_sub_select :selected").text()+"</td><td>"+$("#data-item_sub_insert").val()+"</td><td><input type='hidden' name='item_sub_rel_main[]' value='"+$("#data-item_sub_select").val()+"'><input type='hidden' name='item_sub[]' value='"+$("#data-item_sub_insert").val()+"'><button type='button' class='btn btn-default item_sub_del' data-count='"+$("#data-item_sub_select").val()+"'>刪除</button></td></tr>");
                $("#data-item_sub_insert").val("");
                $("#data-item_sub_select").val("");
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