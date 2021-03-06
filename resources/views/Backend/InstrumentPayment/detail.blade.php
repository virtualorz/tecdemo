@extends('Backend.layouts.master')

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
                                <th width="15%">{{ trans('validation.attributes.month') }}</th>
                                <td>{{ $dataResult['pay_year'] }}/{{ $dataResult['pay_month'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ date('ym',strtotime($dataResult['pay_year'].'-'.$dataResult['pay_month'].'-01')) }}{{ $dataResult['salt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pay_content') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.pay_code') }}</th>
                                                <th>{{ trans('validation.attributes.section') }}</th>
                                                <th>{{ trans('validation.attributes.user') }}</th>
                                                <th>{{ trans('validation.attributes.instrument') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                                <th>{{ trans('validation.attributes.discount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservationlogResult as $k=>$v)
                                            <tr>
                                                <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                                <td>{{ date('Y/m/d H:i',strtotime($v['use_dt_start'])) }} <br> - <br> {{ date('Y/m/d H:i',strtotime($v['use_dt_end'])) }}</td>
                                                <td>{{ $v['member_name'] }}</td>
                                                <td>{{ $v['instrument_name'] }}</td>
                                                <td id="pay_{{$k}}">{{ $v['pay'] }}</td>
                                                <td>
                                                    @if($v['discount_JSON'] != '')
                                                    {{ $discount_type[$v['discount_JSON']['type']] }} : <br>
                                                    {{ $v['discount_JSON']['number'] }}
                                                    @if($v['discount_JSON']['type'] == 1) % @else 元 @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.pay_code') }}</th>
                                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                                <th>{{ trans('validation.attributes.item_count') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplies_add">
                                            @foreach($reservationlogResult as $k=>$v)
                                                @foreach($v['supplies_JOSN'] as $k1=>$v1)
                                                <tr>
                                                    <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                                    <td>{{ $v1['name'] }}</td>
                                                    <td>{{ $v1['count'] }}</td>
                                                    <td>{{ $v1['total'] }}</td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pay_total') }}</th>
                                <td>
                                    {{ $dataResult['total'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    {{ $dataResult['remark'] }}
                                </td>
                            </tr>
                           <tr>
                                <th>{{ trans('validation.attributes.system-organize') }}</th>
                                <td>
                                    {{ $dataResult['organize_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.system-department') }}</th>
                                <td>
                                    {{ $dataResult['department_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    {{ $dataResult['pi_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
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
<script src="{{ asset('assets/official/js/jquery.blockUI.min.js') }}"></script>
<script type="text/javascript">
    
    $(document).ready(function () {
        

    });

</script>
@endsection