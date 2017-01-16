@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">                                 
                <h3 class="panel-title">{{ trans('page.title.search') }}</h3>
                <ul class="panel-controls">
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                </ul>                                
            </div>
            <div class="panel-body">
                <form id="formq" method="get" action="{{ Sitemap::node()->getUrl() }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('validation.attributes.date') }}</td>
                                <td>
                                    <input type="text" name="date" id="data-date" class="form-control datepicker" value="{{ Request::input('date', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.account') }}</td>
                                <td>
                                    <input type="text" name="account" id="data-account" class="form-control" value="{{ Request::input('account', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-default btnReset">{{ trans('page.btn.reset') }}</button>
                                    <button type="submit" name="submit_search" value="1" class="btn btn-default btnSubmit">{{ trans('page.btn.search') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.account') }}</th>
                            <th width="15%">{{ trans('validation.attributes.operator') }}</th>
                            <th width="15%">{{ trans('validation.attributes.page') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['id'] }}" /></label>
                            </td>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ trans('enum.system_operator.'.$v['operator']) }}</td>
                            <td>{{ trans('sitemap.'.str_replace(array('.add','.edit','.detail','.submit'),'',$v['page']).'._name') }}</td>
                            <td>
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('Backend.elements.pagination')
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">

    $(document).ready(function () {
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

</script>
@endsection