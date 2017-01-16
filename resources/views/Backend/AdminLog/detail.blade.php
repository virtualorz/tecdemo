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
                                <th>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    {{$dataResult['name']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.account') }}</th>
                                <td>
                                    {{$dataResult['email']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.operator') }}</th>
                                <td>
                                    {{ trans('enum.system_operator.'.$dataResult['operator']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.table') }}</th>
                                <td>
                                    {{$dataResult['table']}}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.page') }}</th>
                                <td>
                                    {{ trans('sitemap.'.str_replace(array('.add','.edit','.detail','.submit'),'',$dataResult['page']).'._name') }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.value_before') }}</th>
                                <td>
                                    {{ $dataResult['before_JSON'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.value_after') }}</th>
                                <td>
                                    {{ $dataResult['after_JSON'] }}
                                </td>
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