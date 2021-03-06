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
                                <td>{{ trans('validation.attributes.user') }}</td>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control" value="{{ Request::input('name', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.card_id_number') }}</td>
                                <td>
                                    <input type="text" name="card_id_number" id="data-card_id_number" class="form-control" value="{{ Request::input('card_id_number', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.department') }}</td>
                                <td>
                                    <select name="department" id="data-department" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($departmentResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('department', '')) selected @endif>{{$v['organize_name']}}/{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.page_id') }}</td>
                                <td>
                                    <input type="text" name="page_id" id="data-page_id" class="form-control" value="{{ Request::input('page_id', '') }}">
                                </td>
                            </tr>
                             <tr>
                                <td>{{ trans('validation.attributes.id_type') }}</td>
                                <td>
                                    <select name="member_type" id="data-member_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($member_typeResult as $k=>$v)
                                        <option value="{{$k}}" @if($k == Request::input('member_type', '')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="submit" name="submit_search" value="1" class="btn btn-default btnSubmit">{{ trans('page.btn.search') }}</button>
                                    <button type="button" class="btn btn-default btnReset">{{ trans('page.btn.reset') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            {!! ViewHelper::button('add') !!}
            {!! ViewHelper::button('delete') !!}
            {!! ViewHelper::button('output') !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.month') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-organize') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-department') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pi') }}</th>
                            <th width="15%">{{ trans('validation.attributes.total') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pay_status') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td>{{ $v['pay_year'] }}/{{ $v['pay_month'] }}</td>
                            <td>{{ $v['organize_name'] }}</td>
                            <td>{{ $v['department_name'] }}</td>
                            <td>{{ $v['pi_name'] }}</td>
                            <td>{{ $v['total'] }}</td>
                            <td>
                                @if($v['pay_count'] != 0)
                                    {{ trans('enum.apply_item-is_pay.1') }} 
                                @elseif($v['create_admin_id'] === null) 
                                    {{ trans('enum.payment_status.0') }} 
                                @else 
                                    @if($v['print_member_id'] === null) 
                                        {{ trans('enum.payment_status.1') }} 
                                    @else 
                                        {{ trans('enum.payment_status.2') }} 
                                    @endif 
                                @endif 
                            </td>
                            <td>
                                @if($v['create_admin_id'] === null)
                                {!! ViewHelper::button('confirm_pay', ['id' => $v['pi_list_id'].'_'.$v['pay_year'].'_'.$v['pay_month']]) !!}
                                @endif
                                @if($v['print_member_id'] !== null && $v['pay_count'] == 0)
                                {!! ViewHelper::button('complete_pay', ['id' => $v['pi_list_id'].'_'.$v['pay_year'].'_'.$v['pay_month']]) !!}
                                @endif
                                @if($v['create_admin_id'] !== null && $v['print_member_id'] === null)
                                {!! ViewHelper::button('reminder_pay', ['id' => $v['pi_list_id'].'_'.$v['pay_year'].'_'.$v['pay_month']]) !!}
                                @endif
                                {!! ViewHelper::button('detail', ['id' => $v['pi_list_id'].'_'.$v['pay_year'].'_'.$v['pay_month']]) !!}
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
        var name = "{{ Request::input('name', '') }}";
        var card_id_number = "{{ Request::input('card_id_number', '') }}";
        var department = "{{ Request::input('department', '') }}";
        var page_id = "{{ Request::input('page_id', '') }}";
        var member_type = "{{ Request::input('member_type', '') }}";

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

        $(".btn_output").click(function(){
            var search_condition = "?";
            if(name != '')
            {
                search_condition +="name="+name+"&";
            }
            if(card_id_number != '')
            {
                search_condition +="card_id_number="+card_id_number+"&";
            }
            if(department != '')
            {
                search_condition +="department="+department+"&";
            }
            if(page_id != '')
            {
                search_condition +="page_id="+page_id+"&";
            }
            if(member_type != '')
            {
                search_condition +="member_type="+member_type+"&";
            }
            location.href = $(this).attr('data-url')+search_condition;
        });
    });

</script>
@endsection