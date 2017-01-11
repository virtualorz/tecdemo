@extends('backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
            {!! ViewHelper::button('reminderadd',['id' => $id]) !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.reminder_dt') }}</th>
                            <th width="15%">{{ trans('validation.attributes.email') }}</th>
                            <th width="15%">{{ trans('validation.attributes.reminder_name') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ $v['create_admin_name'] }}</td>
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