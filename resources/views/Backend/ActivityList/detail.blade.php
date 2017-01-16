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
                                <th>{{ trans('validation.attributes.activity_id') }}</th>
                                <td>
                                    {{ $dataResult['activity_id'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    {{ $dataResult['start_dt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.end_dt') }}</th>
                                <td>
                                    {{ $dataResult['end_dt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.activity_name') }}</th>
                                <td>
                                    {{ $dataResult['activity_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.activity_type') }}</th>
                                <td>
                                    {{ $dataResult['activity_type_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.related_plateform') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.plateform_name') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="related_plateform_add">
                                            @foreach($relative_plateformResult as $k=>$v)
                                            <tr>
                                                <td>{{$v["name"]}}</td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.level') }}</th>
                                <td>
                                    {{ $level[$dataResult['level']] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.time') }}</th>
                                <td>
                                    {{ $dataResult['time'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.score') }}</th>
                                <td>
                                    {{ $dataResult['score'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pass_type') }}</th>
                                <td>
                                    {{ $pass_type[$dataResult['pass_type']] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pass_condition') }}</th>
                                <td>
                                    {{ $dataResult['pass_condition'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.open_instrument') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.instrument_name') }}</th>
                                                <th>{{ trans('validation.attributes.permission') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="instrument_add">
                                            @foreach($activity_instrumentResult as $k=>$v)
                                            <tr>
                                                <td>{{$v["instrument_name"]}}</td>
                                                <td>{{$permission[$v["permission_id"]]}}</td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.content') }}</th>
                                <td>
                                    @if(isset($dataResult['content']))
                                    @include('Backend.elements.btseditor', ['btseditorContent' => $dataResult['content']])
                                    @endif
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
<script type="text/javascript">

    $(document).ready(function () {
        
    });
    
</script>
@endsection