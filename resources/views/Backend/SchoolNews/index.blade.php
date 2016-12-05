@extends('backend.layouts.master')


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
                                <td>{{ trans('validation.attributes.title') }}</td>
                                <td>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="{{ trans('validation.attributes.title') }}" maxlength="10" value="{{ Request::input('title', '') }}"  />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.school') }}</td>
                                <td>
                                    {{ trans('validation.attributes.city_name') }}:
                                    <select name="city" id="data-city" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($twCity as $k=>$v)
                                        <option value="{{$k}}" @if($k == Request::input('city', '')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                    {{ trans('validation.attributes.town_name') }}:
                                    <select name="town" id="data-town" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @if(Request::input('city', '') != '')
                                        @foreach($twTown[Request::input('city', '')] as $k=>$v)
                                        <option value="{{$k}}" @if($k == Request::input('town', '')) selected @endif>{{$v[1]}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    {{ trans('validation.attributes.school') }}:
                                    <select name="school_id" id="data-school_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($schoolResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('school_id', '')) selected @endif>{{$v['school_name']}}</option>
                                        @endforeach
                                    </select>
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
                            <th width="15%">{{ trans('validation.attributes.school') }}</th>
                            <th width="15%">{{ trans('validation.attributes.title') }}</th>
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
                            <td>{{ $v['school_name'] }}</td>
                            <td>{{ $v['title'] }}</td>
                            <td>
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('backend.elements.pagination')
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">

    $(document).ready(function () {
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

</script>
@endsection