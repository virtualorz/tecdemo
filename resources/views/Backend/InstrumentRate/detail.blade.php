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
                                <th>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    {{ $dataResult['start_dt'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.rate_type') }}</th>
                                <td>
                                    {{ trans('enum.rate_type.'.$dataResult['rate_type']) }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.rate1') }}</th>
                                <td>
                                    {{ $dataResult['member_1'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.rate2') }}</th>
                                <td>
                                    {{ $dataResult['member_2'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.rate3') }}</th>
                                <td>
                                    {{ $dataResult['member_3'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.rate4') }}</th>
                                <td>
                                    {{ $dataResult['member_4'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.rate_multi') }}</th>
                                <td>
                                    {{ $dataResult['rate'] }}
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