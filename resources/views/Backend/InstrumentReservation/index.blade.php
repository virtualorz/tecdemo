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
                                <td>{{ trans('validation.attributes.instrument') }}</td>
                                <td>
                                    <select name="instrument" id="data-instrument" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($instrumentResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == Request::input('instrument', '')) selected @endif>{{$v['type_name']}} - {{$v['instrument_id']}} - {{$v['name']}}</option>
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
            {!! ViewHelper::button('delete') !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.reservation_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.reservation_section') }}</th>
                            <th width="15%">{{ trans('validation.attributes.reservation_status') }}</th>
                            <th width="15%">{{ trans('validation.attributes.instrument_id') }}</th>
                            <th width="15%">{{ trans('validation.attributes.instrument_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.user') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-organize') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-department') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pi') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['instrument_reservation_data_id'].'_'.$v['create_date'] }}" /></label>
                            </td>
                            <td>{{ $v['reservation_dt'] }}</td>
                            <td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
                            <td>
                                @if($v['attend_status'] === 0)
                                {{ trans('enum.attend_status.'.$v['attend_status']) }}
                                @else
                                {{ trans('enum.reservation_status.'.$v['reservation_status']) }}
                                @endif
                            </td>
                            <td>{{ $v['instrument_id'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['member_name'] }}</td>
                            <td>{{ $v['organize_name'] }}</td>
                            <td>{{ $v['department_name'] }}</td>
                            <td>{{ $v['pi_name'] }}</td>
                            <td>
                                @if($v['attend_status'] == null && $v['attend_status'] !== 0 && $v['reservation_status'] !== null && $v['reservation_status'] !== 2 )
                                {!! ViewHelper::button('complete', ['id' => $v['instrument_reservation_data_id'].'_'.$v['create_date']]) !!}
                                {!! ViewHelper::button('dcomplete', ['id' => $v['instrument_reservation_data_id'].'_'.$v['create_date'],'use_dt_start'=>$v['reservation_dt'].' '.$v['start_time'],'use_dt_end'=>$v['reservation_dt'].' '.$v['end_time']]) !!}
                                @endif
                                @if(($v['reservation_status'] === 1 || $v['reservation_status'] === 0) && $v['attend_status'] == null)
                                {!! ViewHelper::button('notattend', ['id' => $v['instrument_reservation_data_id'].'_'.$v['create_date']]) !!}
                                @endif
                                @if($v['reservation_status'] !== null && $v['reservation_status'] == 0 && $v['reservation_status'] !== 2)
                                {!! ViewHelper::button('removewait', ['id' => $v['instrument_reservation_data_id'].'_'.$v['create_date']]) !!}
                                @endif
                                {!! ViewHelper::button('detail', ['id' => $v['instrument_reservation_data_id'].'_'.$v['create_date']]) !!}
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
        $(".dcomplete").click(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('dcomplete')->getUrl() }}",
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'use_dt_start':$(this).attr('data-start'),'use_dt_end':$(this).attr('data-end'),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                },
                success: function (response) {console.log(response);
                    if(response.result == "no")
                    {
                        $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                    }
                    else
                    {
                        location.reload();
                    }
                    
                }
            }
            $.ajax(ajaxProp);
        });
        $(".notattend").click(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('notattend')->getUrl() }}",
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                },
                success: function (response) {
                    if(response.result == "no")
                    {
                        $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                    }
                    else
                    {
                        location.reload();
                    }
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $(".removewait").click(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('removewait')->getUrl() }}",
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                },
                success: function (response) {
                    if(response.result == "no")
                    {
                        $.mbAlert().mbStyle('warning').mbTitleText(response.msg).mbContentHtml(response.detail[0]).mbOpen();
                    }
                    else
                    {
                        location.reload();
                    }
                    
                }
            }
            $.ajax(ajaxProp);
        });
    });

</script>
@endsection