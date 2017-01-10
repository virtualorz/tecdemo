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
                                <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                                <td>{{ $dataResult['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.instrument_type') }}</th>
                                <td>
                                    <select name="instrument_type_id" id="data-instrument_type_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($typeResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['instrument_type_id'] ) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.site_name') }}</th>
                                <td>
                                    <select name="instrument_site_id" id="data-instrument_site_id" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($siteResult as $k=>$v)
                                        <option value="{{$v['id']}}" @if($v['id'] == $dataResult['instrument_site_id'] ) selected @endif>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.instrument_id') }}</th>
                                <td>
                                    <input type="text" name="instrument_id" id="data-instrument_id" class="form-control required" value="{{ $dataResult['instrument_id'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.instrument_name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required" value="{{ $dataResult['name'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.instrument_function') }}</th>
                                <td>
                                    <textarea name="function" id="data-function" class="form-control required">{{ $dataResult['function'] }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.admin') }}</th>
                                <td>
                                    {{ trans('validation.attributes.admin-name') }}
                                    <input type="text" name="admin_name" id="data-admin_name" class="form-control">
                                    {{ trans('validation.attributes.admin-email') }}
                                    <input type="email" name="admin_email" id="data-admin_email" class="form-control">
                                    <input type="button" id="add_admin" class="btn btn-default" value="{{trans('page.btn.add_list')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.admin_add') }}</th>
                                <td>
                                    <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('validation.attributes.admin-name') }}</th>
                                                <th>{{ trans('validation.attributes.admin-email') }}</th>
                                                <th>{{ trans('page.text.function') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="admin_add">
                                        @foreach($adminResult as $k=>$v)
                                        <tr>
                                            <td>{{$v['name']}}<input type='hidden' class='admin_name' name='admin_name[]' value="{{$v['name']}}"></td>
                                            <td>{{$v['email']}}<input type='hidden' class='admin_email' name='admin_email[]' value="{{$v['email']}}"></td>
                                            <td><input type='button' class='btn btn-default del_admin' value='刪除'></td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.open_section') }}</th>
                                <td>
                                   <table class="table datatable_simple">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('enum.section.1') }}</th>
                                                <th>{{ trans('enum.section.2') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sectionResult as $k=>$v)
                                            <tr>
                                                <td>
                                                @if($v['1'] != '')
                                                    <label class="check"><input type="checkbox" name="open_section[]" class="icheckbox ckbItem_open_section" value="{{$v['1']['id']}}_{{$v['1']['section_type']}}" @if(in_array($v['1']['id'],$sectionSetResult)) checked @endif />{{$v['1']['start_time']}} - {{$v['1']['end_time']}}</label>
                                                @endif
                                                </td>
                                                <td>
                                                @if($v['2'] != '')
                                                    <label class="check"><input type="checkbox" name="open_section[]" class="icheckbox ckbItem_open_section" value="{{$v['2']['id']}}_{{$v['2']['section_type']}}" @if(in_array($v['2']['id'],$sectionSetResult)) checked @endif />{{$v['2']['start_time']}} - {{$v['2']['end_time']}}</label>
                                                @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.reservation_limit') }}</th>
                                <td>
                                    <input type="number" name="reservation_limit" id="data-reservation_limit" class="form-control required" value="{{ $dataResult['reservation_limit'] }}">
                                </td>
                            </tr>
                            <tr>
                                <td><span class="red">*</span>{{ trans('validation.attributes.reservation_notice') }}</td>
                                <td>
                                    <div>                  
                                        <label class="check"><input type="radio" name="notice" id="data_notice_1" class="iradio required" value="1" @if($dataResult['notice'] == 1) checked @endif /> {{ trans('enum.yn.1') }}</label>
                                        <label class="check"><input type="radio" name="notice" id="data_notice_0" class="iradio required" value="0" @if($dataResult['notice'] == 0) checked @endif /> {{ trans('enum.yn.0') }}</label> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.reservation_cancel_limit') }}</th>
                                <td>
                                    <select name="cancel_limit" id="data-cancel_limit" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @for($i = 1; $i <= 5; $i++)
                                        <option value="{{$i}}" @if($dataResult['cancel_limit'] == $i) selected @endif >{{$i}}{{trans('page.text.days')}}</option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.reservation_cancel_notice') }}</th>
                                <td>
                                    <div>                  
                                        <label class="check"><input type="radio" name="cancel_notice" id="data_cancel_notice_1" class="iradio required" value="1" @if($dataResult['cancel_notice'] == 1) checked @endif /> {{ trans('enum.yn.1') }}</label>
                                        <label class="check"><input type="radio" name="cancel_notice" id="data_cancel_notice_0" class="iradio required" value="0" @if($dataResult['cancel_notice'] == 0) checked @endif /> {{ trans('enum.yn.0') }}</label> 
                                    </div>
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
        $(document).on("click",".del_admin",function(){
            $(this).parent().parent().remove();
        });
        $("#add_admin").click(function(){
            if($("#data-admin_name").val() != "" && $("#data-admin_email").val() != "" && !$("#data-admin_email").hasClass("error"))
            {
                $("#DataTables_Table_1_wrapper").find(".dataTables_empty").parent().remove();
                var html="<tr><td>"+$("#data-admin_name").val()+"<input type='hidden' class='admin_name' name='admin_name[]' value='"+$("#data-admin_name").val()+"'></td><td>"+$("#data-admin_email").val()+"<input type='hidden' class='admin_email' name='admin_email[]' value='"+$("#data-admin_email").val()+"'></td><td><input type='button' class='btn btn-default del_admin' value='刪除'></td></tr>";

                $("#admin_add").append(html);
                $("#data-admin_name").val("");
                $("#data-admin_email").val("");
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