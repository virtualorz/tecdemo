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
                                <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.name-stage') }}</th>
                                <td>
                                    {{$dataResult['name']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.dt-start') }}</th>
                                <td>
                                    {{$dataResult['start_dt']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.dt-end') }}</th>
                                <td>
                                    {{$dataResult['end_dt']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.item-add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.item') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item_table">
                                        @foreach($dataResult['item'] as $k=>$v)
                                            <tr>
                                                <td>{{$v}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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