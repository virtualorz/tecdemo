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
                                <td>{{ trans('validation.attributes.instrument_type') }}</td>
                                <td>
                                    <select name="type" id="data-type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($typeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('type', '')) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.instrument_name') }}</td>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control" value="{{ Request::input('name', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.site_name') }}</td>
                                <td>
                                    <select name="site" id="data-site" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($siteResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('site', '')) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.section') }}</td>
                                <td>
                                    <label class="check"><input type="checkbox" name="section1" class="icheckbox ckbItem_section" value="1" @if(Request::input('section1', '') == 1 ) checked @endif />{{ trans('enum.section.1') }}</label>
                                    <label class="check"><input type="checkbox" name="section2" class="icheckbox ckbItem_section" value="1" @if(Request::input('section2', '') == 1 ) checked @endif />{{ trans('enum.section.2') }}</label>
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
                            <th width="15%">{{ trans('validation.attributes.instrument_type') }}</th>
                            <th width="15%">{{ trans('validation.attributes.instrument_id') }}</th>
                            <th width="15%">{{ trans('validation.attributes.name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.open_section') }}</th>
                            <th width="7%">{{ trans('validation.attributes.rate_status') }}</th>
                            <th width="10%">{{ trans('validation.attributes.create_admin_id') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['id'] }}" /></label>
                            </td>
                            <td>{{ $v['type_name'] }}</td>
                            <td>{{ $v['instrument_id'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ trans('enum.section.'.explode('_',$v['open_section'])[0]) }}/{{ trans('enum.section.'.explode('_',$v['open_section'])[1]) }}</td>
                            <td>
                                @if($v['start_dt'] == null)
                                {{ trans('enum.rate_status.0') }}
                                @else
                                    @if(strtotime($v['start_dt']) > strtotime(date('Y-m-d')))
                                    {{ trans('enum.rate_status.2') }}
                                    @else
                                    {{ trans('enum.rate_status.1') }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $v['created_admin_name'] }}</td>
                            <td>
                                {!! ViewHelper::button('rate', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('vacation', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
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