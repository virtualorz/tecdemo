@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.info_event-event_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.level') }}</th>
                            <th width="15%">{{ trans('validation.attributes.time') }}</th>
                            <th width="15%">{{ trans('validation.attributes.attend_status') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pass_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['id'] }}" /></label>
                            </td>
                            <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                            <td>{{ $v['activity_name'] }}</td>
                            <td>{{ $v['level'] }}</td>
                            <td>{{ $v['time'] }}</td>
                            <td>{{ trans('enum.attend_status.'.$v['attend_status']) }}</td>
                            <td>{{ trans('enum.pass_status.'.$v['pass_status']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('backend.elements.pagination')
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