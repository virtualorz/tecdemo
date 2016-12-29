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
                                <th width="15%">{{ trans('validation.attributes.month') }}</th>
                                <td>{{ $dataResult['pay_year'] }}/{{ $dataResult['pay_month'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ $dataResult['uid'] }}-{{ $dataResult['salt'] }}
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
                                                <th>{{ trans('validation.attributes.member') }}</th>
                                                <th>{{ trans('validation.attributes.instrument') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                                <th>{{ trans('validation.attributes.discount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservationlogResult as $k=>$v)
                                            <tr>
                                                <td>{{ $v['uid'] }}-{{ $v['salt'] }}</td>
                                                <td>{{ $v['use_dt_start'] }}-{{ $v['use_dt_end'] }}</td>
                                                <td>{{ $v['member_name'] }}</td>
                                                <td>{{ $v['instrument_name'] }}</td>
                                                <td id="pay_{{$k}}">{{ $v['pay'] }}</td>
                                                <td>{{ $discount_type[$v['discount_JSON']['type']] }} : <br>
                                                    {{ $v['discount_JSON']['number'] }}
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
                                                    <td>{{ $v['uid'] }}-{{ $v['salt'] }}</td>
                                                    @foreach($suppliesResult as $k2=>$v2)
                                                        @if($v2['id'] == $v1['id'])
                                                        <td>{{ $v2['name'] }}</td>
                                                        @endif
                                                    @endforeach
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
                                <th><span class="red">*</span>{{ trans('validation.attributes.pay_total') }}</th>
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
                                <th>{{ trans('validation.attributes.department') }}</th>
                                <td>
                                    {{ $dataResult['organize_name'] }}/{{ $dataResult['department_name'] }}
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