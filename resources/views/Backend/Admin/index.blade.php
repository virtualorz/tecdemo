@extends('backend.layouts.master')


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
                            <th width="15%">{{ trans('validation.attributes.name') }}</th>
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
                            <td>{{ $v['name'] }}</td>
                            <td>
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
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