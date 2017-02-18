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
                                <th width="15%">{{ trans('validation.attributes.month') }}</th>
                                <td>{{ $dataResult['pay_year'] }}/{{ $dataResult['pay_month'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.page_id') }}</th>
                                <td>
                                    {{ date('ym',strtotime($dataResult['pay_year'].'-'.$dataResult['pay_month'].'-01')) }}{{ $dataResult['salt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.pay_content') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.pay_code') }}</th>
                                                <th>{{ trans('validation.attributes.section') }}</th>
                                                <th>{{ trans('validation.attributes.member') }}</th>
                                                <th>{{ trans('validation.attributes.instrument') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                                <th>{{ trans('validation.attributes.discount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservationlogResult as $k=>$v)
                                            <tr>
                                                <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                                <td>{{ date('Y/m/d H:i',strtotime($v['use_dt_start'])) }} <br> - <br> {{ date('Y/m/d H:i',strtotime($v['use_dt_end'])) }}</td>
                                                <td>{{ $v['member_name'] }}</td>
                                                <td>{{ $v['instrument_name'] }}</td>
                                                <td id="pay_{{$k}}">{{ $v['pay'] }}</td>
                                                <td>
                                                     <select name="discount[]" id="data-discount_{{$k}}" data-id="{{$k}}" class="form-control discount">
                                                        <option value="">{{trans('page.text.select_item')}}</option>
                                                        @foreach($discount_type as $k1=>$v1)
                                                        <option value="{{ $v['payment_reservation_log_id'] }}-{{ $v['pi_list_id'] }}-{{ $v['pay_year'] }}-{{ $v['pay_month'] }}_{{$k1}}" >{{$v1}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="number" name="discount_number[]" id="data-discount_number_{{$k}}" class="form-control discount_number">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.pay_code') }}</th>
                                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                                <th>{{ trans('validation.attributes.item_count') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplies_add">
                                            @foreach($reservationlogResult as $k=>$v)
                                                @foreach($v['supplies_JOSN'] as $k1=>$v1)
                                                <tr>
                                                    <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                                    <td>{{ $v1['name'] }}</td>
                                                    <td>{{ $v1['count'] }}</td>
                                                    <td class="supplies_pay">{{ $v1['total'] }}</td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.pay_total') }}</th>
                                <td>
                                    <span id="pay_total"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    <textarea name="remark" id="data-remark" class="form-control"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.department') }}</th>
                                <td>
                                    {{ $dataResult['organize_name'] }}/{{ $dataResult['department_name'] }}
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
        $(document).on("click",".del_sipplies",function(){
            $(this).parent().parent().remove();
            cal_total();
        });

        $(".discount").change(function(){
            if($("#data-discount_number_"+$(this).attr('data-id')).val() != "" && $(this).val() != "")
            {
                cal_total();
            }
        });

        $(".discount_number").change(function(){
            if($("#data-discount_"+$(this).attr('data-id')).val() != "" && $(this).val() != "")
            {
                cal_total();
            }
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

    function cal_total(){
        var total = 0;
        $(".discount").each(function(){
            $tmp = $(this).val().split("_");
            if($tmp[1] == "1")
            {
                total += parseFloat($("#pay_"+$(this).attr('data-id')).html()) * (parseFloat((100 - parseInt($("#data-discount_number_"+$(this).attr('data-id')).val())))/100);
            }
            else if($tmp[1] == "2")
            {console.log(parseInt($("#pay_"+$(this).attr('data-id')).html()));console.log(parseInt($("#data-discount_number_"+$(this).attr('data-id')).val()));
                total += parseInt($("#pay_"+$(this).attr('data-id')).html()) - parseInt($("#data-discount_number_"+$(this).attr('data-id')).val());
            }
        });

        $(".supplies_pay").each(function(){
            total += parseFloat($(this).html());
        });

        $("#pay_total").html(total);
    }

</script>
@endsection