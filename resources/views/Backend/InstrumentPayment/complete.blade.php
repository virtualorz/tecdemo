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
                                <th width="15%">{{ trans('validation.attributes.month') }}</th>
                                <td>{{ $dataResult['pay_year'] }}/{{ $dataResult['pay_month'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ $dataResult['uid'] }}-{{ $dataResult['salt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pay_total') }}</th>
                                <td>
                                    {{ $dataResult['total'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.department') }}</th>
                                <td>
                                    {{ $dataResult['organize_name'] }}/{{ $dataResult['department_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.payment') }}</th>
                                <td>
                                    <input type="number" name="payment" id="data-payment" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.receive') }}</th>
                                <td>
                                    <input type="text" name="receive" id="data-receive" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ User::get('name', '') }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" value="{{ $dataResult['pi_list_id'].'_'.$dataResult['pay_year'].'_'.$dataResult['pay_month'] }}" />
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