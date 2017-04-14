@extends('Backend.layouts.master')

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(count($dataResult) > 0)
                <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.section_type') }}</th>
                                <td>
                                    <select name="section_type" id="data-section_type" class="form-control">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($section_type as $k=>$v)
                                        <option value="{{$k}}" @if($dataResult['section_type'] == $k) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.start_time') }}</th>
                                <td>
                                    <input type="text" name="start_time" id="data-start_time" class="form-control required timepicker" value="{{ $dataResult['start_time'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.end_time') }}</th>
                                <td>
                                    <input type="text" name="end_time" id="data-end_time" class="form-control required timepicker" value="{{ $dataResult['end_time'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.enable') }}</th>
                                <td>
                                    <label class="check"><input type="radio" name="enable" id="data_enable_1" class="iradio required" value="1" @if($dataResult['enable'] == 1) checked @endif /> {{ trans('enum.enable.1') }}</label>
                                    <label class="check"><input type="radio" name="enable" id="data_enable_0" class="iradio required" value="0" @if($dataResult['enable'] == 0) checked @endif /> {{ trans('enum.enable.0') }}</label> 
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ $dataResult['created_admin_name'] }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" value="{{ $dataResult['id'] }}" />
                                    {!! ViewHelper::button('submit') !!}
                                    {!! ViewHelper::button('cancel') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                @else
                <div align="center">{{ trans('message.info.norecord') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('script_include')
{!! ViewHelper::plugin()->renderJs() !!}
@endsection


@section('script')
<script src="{{ asset('assets/official/js/jquery.blockUI.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function () {
        initValidation();
        $(".timepicker").timepicker({
			'timeFormat': 'H:i',
			'step': 10
		});
    });
    
    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                }) === false) {
                    return false;
                }
            }
        });
    }
</script>
@endsection