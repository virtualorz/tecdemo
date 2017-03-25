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
                            <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_date') }}</th>
                            <th width="15%">{{ trans('validation.attributes.activity_name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.level') }}</th>
                            <th width="15%">{{ trans('validation.attributes.time') }}</th>
                            <th width="15%">{{ trans('validation.attributes.attend_count') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($listResult) > 0 && $listResult[0]['start_dt'] != '')
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                            <td>{{ $v['activity_name'] }}</td>
                            <td>{{ $v['level'] }}</td>
                            <td>{{ $v['time'] }}</td>
                            <td>{{ $v['reservation_count'] }}</td>
                            <td>
                                {!! ViewHelper::button('list', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                        @endif
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
        $("#data-city").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_town')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-city").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+key+"'>"+response[key][1]+"</option>";
                    }
                    $("#data-town").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $("#data-town").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_school')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'city':$("#data-city").val(),'town':$("#data-town").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['school_name']+"</option>";
                    }
                    $("#data-school_id").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });
    });

</script>
@endsection