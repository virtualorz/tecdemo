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
                                    {{ $dataResult['name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.card_id_number') }}</th>
                                <td>
                                    {{ $dataResult['card_id_number'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.id_number') }}</th>
                                <td>
                                    {{ $dataResult['id_number'] }}
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
                                <th>{{ trans('validation.attributes.department_title') }}</th>
                                <td>
                                    {{ $dataResult['title'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.email') }}</th>
                                <td>
                                    {{ $dataResult['email'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.phone') }}</th>
                                <td>
                                    {{ $dataResult['phone'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    {{ $dataResult['pi_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.lab_phone') }}</th>
                                <td>
                                    {{ $dataResult['lab_phone'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.id_type') }}</th>
                                <td>
                                    @foreach($member_typeResult as $k=>$v)
                                        @if($k == $dataResult['type'])
                                        {{ $v }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    {{ $dataResult['start_dt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.limit_month') }}</th>
                                <td>
                                    {{ $dataResult['limit_month'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.permission') }}</th>
                                <td>
                                    @foreach($permission as $k=>$v)
                                        @if(in_array($k,$permissionResult)) {{ $v.' ' }} @endif
                                    @endforeach
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