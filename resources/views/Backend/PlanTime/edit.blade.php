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
                                <th><span class="red">*</span>{{ trans('validation.attributes.name-stage') }}</th>
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
                                <th>{{ trans('validation.attributes.item') }}</th>
                                <td>
                                    <input type="text" name="item_insert" id="data-item_insert" class="form-control">
                                    <button type="button" class="btn btn-default" id="item_add">加入</button>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.item-add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.item') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item_table">
                                        @foreach($dataResult['item'] as $k=>$v)
                                            <tr>
                                                <td>{{$v}}</td>
                                                <td><input type='hidden' name='item[]' value='{{$v}}'><button type='button' class='btn btn-default item_del'>刪除</button></td>
                                            </tr>
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
        $(document).on("click",".item_del",function(){
            $(this).parent().parent().remove();
        });
        $("#item_add").click(function(){
            $("#DataTables_Table_1").find(".dataTables_empty").parent().remove();
            $("#item_table").append("<tr><td>"+$("#data-item_insert").val()+"</td><td><input type='hidden' name='item[]' value='"+$("#data-item_insert").val()+"'><button type='button' class='btn btn-default item_del'>刪除</button></td></tr>");
            $("#data-item_insert").val("");
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