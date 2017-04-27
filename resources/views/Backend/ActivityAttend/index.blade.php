@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">                                 
                <h3 class="panel-title">{{ trans('page.title.search') }}</h3>
                <ul class="panel-controls">
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                </ul>                                
            </div>
            <div class="panel-body">
                <form id="formq" method="get" action="{{ Sitemap::node()->getUrl(['id'=>Route::input('id')]) }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('validation.attributes.user') }}</td>
                                <td>
                                    <input type="text" name="name" id="data-namedate" class="form-control" value="{{ Request::input('name', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.card_id_number') }}</td>
                                <td>
                                    <input type="text" name="card_id_number" id="data-card_id_number" class="form-control" value="{{ Request::input('card_id_number', '') }}">
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('validation.attributes.id_type') }}</td>
                                <td>
                                    <select name="id_type" id="data-id_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($id_type as $k=>$v)
                                        <option value="{{$k}}" @if($k == Request::input('id_type', '')) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="submit" name="submit_search" value="1" class="btn btn-default btnSubmit">{{ trans('page.btn.search') }}</button>
                                    <button type="button" class="btn btn-default btnReset">{{ trans('page.btn.reset') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>

                            <th width="15%">{{ trans('validation.attributes.reservation_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.user') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-organize') }}</th>
                            <th width="15%">{{ trans('validation.attributes.system-department') }}</th>
                            <th width="15%">{{ trans('validation.attributes.pi') }}</th>
                            <th width="15%">{{ trans('validation.attributes.email') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>

                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['organize_name'] }}</td>
                            <td>{{ $v['department_name'] }}</td>
                            <td>{{ $v['pi_name'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>
                                @if($v['attend_status'] == 0)
                                {!! ViewHelper::button('attendv2', ['id' => $v['activity_id'].'_'.$v['member_id'].'_'.$v['created_at_org']]) !!}
                                @else
                                {!! ViewHelper::button('attend_cancel', ['id' => $v['activity_id'].'_'.$v['member_id'].'_'.$v['created_at_org']]) !!}
                                @endif
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
        $(".btnAttend").click(function(){
            var ajaxProp = {
                url: $(this).attr('data-url'),
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                        
                },
                success: function (response) {
                    location.reload();
                        
                }
            }
            $.ajax(ajaxProp);
        });
    });

</script>
@endsection