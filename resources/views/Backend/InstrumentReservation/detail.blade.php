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
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="15%">{{ trans('validation.attributes.update_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ $dataResult['create_date'] }}_{{ $dataResult['instrument_reservation_data_id'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.instrument_id') }}</th>
                                <td>
                                    {{ $dataResult['instrument_id'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.instrument_name') }}</th>
                                <td>
                                    {{ $dataResult['name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.user') }}</th>
                                <td>
                                    {{ $dataResult['member_name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    {{ $dataResult['pi_name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.reservation_section') }}</th>
                                <td>
                                    {{ $dataResult['reservation_dt'] }} {{ $dataResult['start_time'] }} - {{ $dataResult['end_time'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.start_time') }}</th>
                                <td>
                                    {{ $dataResult['use_dt_start'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.end_time') }}</th>
                                <td>
                                    {{ $dataResult['use_dt_end'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                                <th>{{ trans('validation.attributes.item_count') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplies_add">
                                            @foreach($dataResult['supplies_JOSN'] as $k=>$v)
                                                <tr>
                                                    <td>{{ $v['name'] }}</td>
                                                    <td>{{ $v['count'] }}</td>
                                                    <td>{{ $v['total'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    {{ $dataResult['remark'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.update_admin_id') }}</th>
                                <td>{{ $dataResult['created_admin_name'] }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    {!! ViewHelper::button('back') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
<script type="text/javascript">

    $(document).ready(function () {
        
    });
    
</script>
@endsection