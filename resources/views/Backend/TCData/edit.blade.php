@extends('Backend.layouts.master')
@expr(ViewHelper::plugin()->load('btseditor'))

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
                                <th>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required" value="{{ $dataResult['name'] }}">
                                </td>
                            </tr>   
                            <tr>
                                <th>{{ trans('validation.attributes.content') }}</th>
                                <td>
                                    <div name="content" id="content" class="btseditor" data-name="content" data-value="{{ $dataResult['content'] }}"></div>
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
        initBtsEditor();
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
    function initBtsEditor() {
        $('.btseditor').each(function () {
            new BtsEditor($(this).attr('id'), {
                jqfu_file_size: "10 MB",
                jqfu_category: 'tcdata',
                menu: ["pic", "text", "video"]
            })
        });
    }
</script>
@endsection