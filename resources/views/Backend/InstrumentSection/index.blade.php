@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
            {!! ViewHelper::button('add') !!}
            {!! ViewHelper::button('delete') !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.section_type') }}</th>
                            <th width="15%">{{ trans('validation.attributes.section') }}</th>
                            <th width="15%">{{ trans('validation.attributes.enable') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['id'] }}" /></label>
                            </td>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $section_type[$v['section_type']] }}</td>
                            <td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
                            <td><span class="label label-{{Config::get('data.label.enable.'.$v['enable'])}}">{{ trans('enum.enable.'.$v['enable']) }}</span></td>
                            <td>
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
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