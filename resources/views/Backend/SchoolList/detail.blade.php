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
                                <th>{{ trans('validation.attributes.location') }}</th>
                                <td>
                                    @foreach($location as $k=>$v)
                                    @if($k == $dataResult['location']) {{$v}} @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.city_name') }}</th>
                                <td>
                                    @foreach($twCity as $k=>$v)
                                    @if($k == $dataResult['city']) {{$v}} @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.town_name') }}</th>
                                <td>
                                    @foreach($twTown[$dataResult['city']] as $k=>$v)
                                    @if($k == $dataResult['town']) {{$v[1]}} @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.name-school') }}</th>
                                <td>
                                    {{ $dataResult['school_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.admin-account') }}</th>
                                <td>
                                    {{ $dataResult['account'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.aboutus_teacher-photo') }}</th>
                                <td>
                                    <img src="{{$dataResult['photo']}}" width="300" />
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