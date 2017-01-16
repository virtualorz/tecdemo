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
                <form id="formq" method="get" action="{{ Sitemap::node()->getUrl() }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('validation.attributes.admin-name') }}</td>
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
                                    <button type="button" class="btn btn-default btnReset">{{ trans('page.btn.reset') }}</button>
                                    <button type="submit" name="submit_search" value="1" class="btn btn-default btnSubmit">{{ trans('page.btn.search') }}</button>
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
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.reservation_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.name') }}</th>
                            <th width="15%">{{ trans('validation.attributes.email') }}</th>
                            <th width="15%">{{ trans('validation.attributes.enable') }}</th>
                            <th width="15%">{{ trans('validation.attributes.score_pass') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['activity_id'] }}_{{ $v['member_id'] }}" /></label>
                            </td>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ trans('enum.attend_status.'.$v['attend_status']) }}</td>
                            <td>
                                <input type='text' id="score_{{$v['activity_id'].'_'.$v['member_id']}}" value="{{ $v['score'] }}" @if($v['pass_status'] == 1) readOnly @endif>
                            </td>
                            <td>
                                @if($v['pass_status'] == 0)
                                {!! ViewHelper::button('pass', ['id' => $v['activity_id'].'_'.$v['member_id']]) !!}
                                @else
                                {!! ViewHelper::button('pass_cancel', ['id' => $v['activity_id'].'_'.$v['member_id']]) !!}
                                @endif
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
        $(".btnPass").click(function(){
            var ajaxProp = {
                url: $(this).attr('data-url'),
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'score':$('#score_'+$(this).attr('data-id')).val(),'_token':csrf_token},
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