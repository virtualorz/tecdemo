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
                                <th width="15%">{{ trans('validation.attributes.reservation_at') }}</th>
                                <td>{{ $dataResult['reservation_dt_formate'] }}</td>
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
                                <th>{{ trans('validation.attributes.system-organize') }}</th>
                                <td>
                                    {{ $dataResult['organize_name'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.system-department') }}</th>
                                <td>
                                    {{ $dataResult['department_name'] }}
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
                                    {{ $dataResult['reservation_dt_formate'] }} {{ $dataResult['start_time'] }} - {{ $dataResult['end_time'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.start_time') }}</th>
                                <td>
                                    <input type="datetime-local" name="use_dt_start" id="data-use_dt_start" class="form-control required" value="{{ $dataResult['reservation_dt'] }}T{{ $dataResult['start_time'] }}">
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.end_time') }}</th>
                                <td>
                                    <input type="datetime-local" name="use_dt_end" id="data-use_dt_end" class="form-control required" value="{{ $dataResult['reservation_dt'] }}T{{ $dataResult['end_time'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                <td>
                                    <table class="table nohead">
                                        <tr>
                                            <th>{{ trans('validation.attributes.supplies') }}</th>
                                            <td>
                                                <select name="supplies_set" id="data-supplies" class="form-control supplies_change">
                                                    <option value="">{{trans('page.text.select_item')}}</option>
                                                    @foreach($suppliesResult as $k=>$v)
                                                    <option value="{{ $v['id'] }}" data-rate1="{{ $v['rate1'] }}" data-rate2="{{ $v['rate2'] }}" data-rate3="{{ $v['rate3'] }}" data-rate4="{{ $v['rate4'] }}">{{ $v['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('validation.attributes.item_count') }}</th>
                                            <td>
                                                <input type="number" name="count_set" id="data-count" class="form-control supplies_change">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                            <td>
                                                <span id="pay_supplies"></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="button" id="add_supplies" class="btn btn-default" value="{{trans('page.btn.add_list')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.supplies_add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.supplies') }}</th>
                                                <th>{{ trans('validation.attributes.item_count') }}</th>
                                                <th>{{ trans('validation.attributes.apply_item-fee') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplies_add">
                                        </tbody>
                                    </table>
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
                                    <input type="hidden" name="member_type" value="{{ $dataResult['member_type'] }}" />
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
        var member_type = "{{ $dataResult['member_type'] }}";

        $(document).on("click",".del_sipplies",function(){
            $(this).parent().parent().remove();
        });
        $("#add_supplies").click(function(){
            if($("#data-supplies").val() != "" && $("#data-count").val() != "" && parseInt($("#data-count").val()) >0)
            {
                $("#supplies_add").find(".dataTables_empty").parent().remove();
                var html ="<tr><td>"+$("#data-supplies :selected").text()+"<input type='hidden' class='supplies' name='supplies[]' value='"+$("#data-supplies").val()+"'></td>";
                html +="<td>"+$("#data-count").val()+"<input type='hidden' class='count' name='count[]' value='"+$("#data-count").val()+"'></td>";
                html +="<td class='supplies_pay'>"+$("#data-supplies :selected").attr('data-rate'+member_type)*$("#data-count").val().toString()+"</td>";
                html +="<td><input type='button' class='btn btn-default del_sipplies' value='刪除'></td></tr>";

                $("#supplies_add").append(html);
                $("#data-supplies").val("");
                $("#data-count").val("");
                $("#pay_supplies").html("");
            }
        });

        $(".supplies_change").change(function(){
            if($("#data-supplies").val() != "" && $("#data-count").val() != "")
            {
                if(parseInt($("#data-count").val()) <=0)
                {
                    $("#pay_supplies").html("{{ trans('message.error.wrong_number') }}");
                }
                else
                {
                    $("#pay_supplies").html($("#data-supplies :selected").attr('data-rate'+member_type)*$("#data-count").val().toString());
                }
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
</script>
@endsection