@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
            @if(count($listResult) == 0)
            {!! ViewHelper::button('addv2',['id' => $id]) !!}
            @endif
            {!! ViewHelper::button('delete') !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.start_dt') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate_type') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate1') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate2') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate3') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate4') }}</th>
                            <th width="15%">{{ trans('validation.attributes.rate_multi') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td>{{ $v['start_dt'] }}</td>
                            <td>{{ trans('enum.rate_type.'.$v['rate_type']) }}</td>
                            <td>{{ $v['member_1'] }}</td>
                            <td>{{ $v['member_2'] }}</td>
                            <td>{{ $v['member_3'] }}</td>
                            <td>{{ $v['member_4'] }}</td>
                            <td>{{ $v['rate'] }}</td>
                            <td>
                                @if($v['disabled'] == 0)
                                {!! ViewHelper::button('edit', ['id' => $v['instrument_rate_id'].'_'.$v['instrument_data_id']]) !!}
                                @endif
                                {!! ViewHelper::button('detail', ['id' => $v['instrument_rate_id'].'_'.$v['instrument_data_id']]) !!}
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