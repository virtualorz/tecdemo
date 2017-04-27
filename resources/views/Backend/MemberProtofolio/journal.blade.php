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
            {!! ViewHelper::button('addv2',['id' => $id]) !!}
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
                            <th width="15%">{{ trans('validation.attributes.topic') }}</th>
                            <th width="15%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['member_data_id'].'_'.$v['member_journal_id'] }}" /></label>
                            </td>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['topic'] }}</td>
                            <td>
                                {!! ViewHelper::button('edit', ['id' => $v['member_data_id'].'_'.$v['member_journal_id']]) !!}
                                {!! ViewHelper::button('detail', ['id' => $v['member_data_id'].'_'.$v['member_journal_id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
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