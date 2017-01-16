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
                                <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.instrument_type') }}</th>
                                <td>{{ $dataResult['type_name'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.site_name') }}</th>
                                <td>{{ $dataResult['site_name'] }}</td>
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
                                <th>{{ trans('validation.attributes.instrument_function') }}</th>
                                <td>
                                    {{ $dataResult['function'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.admin') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.admin-name') }}</th>
                                                <th>{{ trans('validation.attributes.admin-email') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="admin_add">
                                        @foreach($adminResult as $k=>$v)
                                        <tr>
                                            <td>{{$v['name']}}</td>
                                            <td>{{$v['email']}}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.open_section') }}</th>
                                <td>
                                   <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('enum.section.1') }}</th>
                                                <th>{{ trans('enum.section.2') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sectionResult as $k=>$v)
                                            <tr>
                                                <td>
                                                @if($v['1'] != '')
                                                    @if(in_array($v['1']['id'],$sectionSetResult))
                                                    {{$v['1']['start_time']}} - {{$v['1']['end_time']}}
                                                    @endif
                                                @endif
                                                </td>
                                                <td>
                                                @if($v['2'] != '')
                                                    @if(in_array($v['2']['id'],$sectionSetResult))
                                                    {{$v['2']['start_time']}} - {{$v['2']['end_time']}}
                                                    @endif
                                                @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.reservation_limit') }}</th>
                                <td>
                                    {{ $dataResult['reservation_limit'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.reservation_notice') }}</td>
                                <td>{{ trans('enum.yn.'.$dataResult['notice']) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.reservation_cancel_limit') }}</th>
                                <td>{{ $dataResult['cancel_limit'] }}{{trans('page.text.days')}}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.reservation_cancel_notice') }}</th>
                                <td>{{ trans('enum.yn.'.$dataResult['cancel_notice']) }}</td>
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