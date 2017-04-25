@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        

        <div class="panel panel-default">
            <div class="panel-heading">
            {{trans('page.title.unprocess_list')}}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.apply_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.apply_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-organize') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-department') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pi') }}</th>
                            <th width="15%">{{ trans('validation.attributes.email') }}</th>
                            <th width="15%">{{ trans('validation.attributes.apply_reason') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult1 as $k => $v)
                        <tr>
                            <td>{{ $v['created_at'] }}</td>
                            @if($v['end_dt'] == null)
                                <td>{{ $v['start_dt'] }} {{trans('message.info.start')}}</td>
                            @else
                                <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                            @endif
                            <td>{{ $v['activity_name'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['organize_name'] }}</td>
                            <td>{{ $v['department_name'] }}</td>
                            <td>{{ $v['pi_name'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ $v['reason'] }}</td>
                            <td>
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            {{trans('page.title.history_list')}}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.apply_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.apply_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-organize') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-department') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pi') }}</th>
                            <th width="15%">{{ trans('validation.attributes.email') }}</th>
                            <th width="15%">{{ trans('validation.attributes.apply_reason') }}</th>
                            <th width="25%">{{ trans('validation.attributes.is_pass') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult2 as $k => $v)
                        <tr>
                            <td>{{ $v['created_at'] }}</td>
                            @if($v['end_dt'] == null)
                                <td>{{ $v['start_dt'] }} {{trans('message.info.start')}}</td>
                            @else
                                <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                            @endif
                            <td>{{ $v['activity_name'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['organize_name'] }}</td>
                            <td>{{ $v['department_name'] }}</td>
                            <td>{{ $v['pi_name'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ $v['reason'] }}</td>
                            <td>{{ trans('enum.is_pass.'.$v['is_pass']) }}</td>
                            <td>
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('Backend.elements.pagination')
            </div>
        </div>

    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">

    $(document).ready(function () {
    });

</script>
@endsection