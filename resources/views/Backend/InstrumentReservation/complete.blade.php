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
                                <th width="15%">{{ trans('validation.attributes.reservation_at') }}</th>
                                <td>{{ $dataResult['reservation_dt'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ $dataResult['create_date'] }}_{{ $dataResult['instrument_reservation_data_id'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.instrument_id') }}</th>
                                <td>
                                    {{ $dataResult['instrument_id'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.instrument_name') }}</th>
                                <td>
                                    {{ $dataResult['name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.user') }}</th>
                                <td>
                                    {{ $dataResult['member_name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.pi') }}</th>
                                <td>
                                    {{ $dataResult['pi_name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.reservation_section') }}</th>
                                <td>
                                    {{ $dataResult['start_time'] }} - {{ $dataResult['end_time'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.start_time') }}</th>
                                <td>
                                    <input type="time" name="use_dt_start" id="data-use_dt_start" class="form-control required" value="{{ $dataResult['start_time'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.end_time') }}</th>
                                <td>
                                    <input type="time" name="use_dt_end" id="data-use_dt_end" class="form-control required" value="{{ $dataResult['end_time'] }}">
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
                                    <input type="hidden" name="id" value="{{ $dataResult['instrument_reservation_data_id'] }}_{{ $dataResult['create_date'] }}" />
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