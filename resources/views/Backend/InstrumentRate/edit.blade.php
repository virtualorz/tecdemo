@extends('backend.layouts.master')

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
                                <th width="15%">{{ trans('validation.attributes.update_at') }}</th>
                                <td>{{ date('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.start_dt') }}</th>
                                <td>
                                    <input type="text" name="start_dt" id="data-start_dt" class="form-control required datepicker">
                                </td>
                            </tr>  
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate_type') }}</th>
                                <td>
                                    <label class="check"><input type="radio" name="rate_type" id="data_rate_type_1" class="iradio required" value="1" @if($dataResult['rate_type'] == 1) checked @endif /> {{ trans('enum.rate_type.1') }}</label>
                                    <label class="check"><input type="radio" name="rate_type" id="data_rate_type_2" class="iradio required" value="2" @if($dataResult['rate_type'] == 2) checked @endif /> {{ trans('enum.rate_type.2') }}</label> 
                                    <label class="check"><input type="radio" name="rate_type" id="data_rate_type_3" class="iradio required" value="3" @if($dataResult['rate_type'] == 3) checked @endif /> {{ trans('enum.rate_type.3') }}</label> 
                                </td>
                            </tr>  
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate1') }}</th>
                                <td>
                                    <input type="number" name="member_1" id="data-member_1" class="form-control required" value="{{ $dataResult['member_1'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate2') }}</th>
                                <td>
                                    <input type="number" name="member_2" id="data-member_2" class="form-control required" value="{{ $dataResult['member_2'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate3') }}</th>
                                <td>
                                    <input type="number" name="member_3" id="data-member_3" class="form-control required" value="{{ $dataResult['member_3'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate4') }}</th>
                                <td>
                                    <input type="number" name="member_4" id="data-member_4" class="form-control required" value="{{ $dataResult['member_4'] }}">
                                </td>
                            </tr>  
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.rate_multi') }}</th>
                                <td>
                                    <input type="number" name="rate" id="data-rate" class="form-control required" value="{{ $dataResult['rate'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    <textarea name="remark" id="data-remark" class="form-control"></textarea>
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.update_admin_id') }}</th>
                                <td>{{ User::get('name', '') }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" value="{{ $dataResult['instrument_rate_id'] }}_{{ $dataResult['instrument_data_id'] }}" />
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