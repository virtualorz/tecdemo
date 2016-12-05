@extends('backend.layouts.master')
@expr(ViewHelper::plugin()->load('btseditor'))

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
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
                                <td>{{ date('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.title') }}</th>
                                <td>
                                    <input type="text" name="title" id="data-title" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.content') }}</th>
                                <td>
                                    <div name="content" id="content" class="btseditor" data-name="content"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="red">*</span>{{ trans('validation.attributes.enable') }}</td>
                                <td>
                                    <div>                  
                                        <label class="check"><input type="radio" name="enable" id="data_enable_1" class="iradio required" value="1" checked /> {{ trans('enum.enable.1') }}</label>
                                        <label class="check"><input type="radio" name="enable" id="data_enable_0" class="iradio required" value="0" /> {{ trans('enum.enable.0') }}</label> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ User::get('name', '') }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    {!! ViewHelper::button('submit') !!}
                                    {!! ViewHelper::button('cancel') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script_include')
{!! ViewHelper::plugin()->renderJs() !!}
@endsection


@section('script')
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
                jqfu_category: 'news',
                menu: ["pic", "text", "video"]
            })
        });
    }
</script>
@endsection