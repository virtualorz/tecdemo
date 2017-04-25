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
                                <td>{{ trans('validation.attributes.name') }}</td>
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
                                <td>{{ trans('validation.attributes.email') }}</td>
                                <td>
                                    <input type="text" name="email" id="data-email" class="form-control" value="{{ Request::input('email', '') }}">
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
                                <td>{{ trans('validation.attributes.system-organize') }}</td>
                                <td>
                                    <select name="organize" id="data-organize" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($organizeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('organize', '')) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.system-department') }}</td>
                                <td>
                                    <select name="department" id="data-department" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @if(isset($departmentResult))
                                        @foreach($departmentResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('department', '')) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.pi') }}</td>
                                <td>
                                    <select name="pi" id="data-pi" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @if(isset($piResult))
                                        @foreach($piResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('pi', '')) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                        @endif
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
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.card_id_number') }}</th>
                            <th width="15%">{{ trans('validation.attributes.id_type') }}</th>
                            <th width="15%">{{ trans('validation.attributes.enable') }}</th>
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
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['card_id_number'] }}</td>
                            <td>{{ trans('enum.member_type.'.$v['type']) }}</td>
                            <td>{{ trans('enum.member-enable.'.$v['enable']) }}</td>
                            <td>
                                @if($v['enable'] == 0)
                                {!! ViewHelper::button('active', ['id' => $v['id']]) !!}
                                @else
                                {!! ViewHelper::button('notice', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('activitylog', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
                                @endif
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