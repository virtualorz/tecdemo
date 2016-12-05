@extends('backend.layouts.master')
@expr(ViewHelper::plugin()->load('jstree'))

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(count($dataResult) > 0)
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
                                <th><span class="red">*</span>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    {{$dataResult['name']}}
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.permission') }}</th>
                                <td>
                                    <input type="hidden" name="content" id="data-content"  />
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.enable') }}</th>
                                <td>
                                    {{trans('enum.enable.'.$dataResult['enable'])}}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.create_admin_id') }}</th>
                                <td>{{ $dataResult['created_admin_name'] }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                     {!! ViewHelper::button('back') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
var treePermissionJson = {!! $treePermissionJson !!};
var treePermissionSelectedJson = {!! $treePermissionSelectedJson !!};
            

    $(document).ready(function () {
        initValidation();
        initPermissionTree('data-content');
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
    function initPermissionTree(eleId) {
        var $divCon = $('<div></div>').attr('id', eleId + '_JtCon');
        $('#' + eleId).after($divCon);
        $divCon.data('value', []);

        $.each(treePermissionJson, function (k, v) {
            var id = eleId + '_jt_' + k;
            var $div = $('<div></div>').attr('id', id).addClass('col-lg-3 col-md-6').appendTo($divCon);
            $div.on('ready.jstree', function (e, data) {
                var tmpFunc = function ($con, id) {
                    var node = $con.jstree('get_node', id);
                    if ($con.jstree('is_leaf', id) && $.inArray(node.original.path, treePermissionSelectedJson) !== -1) {
                        $con.jstree('select_node', id, false, true);
                    }
                    if (node.parents.length <= 2) {
                        $con.jstree('open_node', id);
                    } else {
                        $con.jstree('close_node', id);
                    }
                    $con.jstree('disable_node', id);
                    $.each(node.children, function (kk, vv) {
                        tmpFunc($con, vv);
                    });
                };
                var $this = $(this);
                var root = data.instance.get_node('#');
                $.each(root.children, function (kk, vv) {
                    tmpFunc($this, vv);
                });
            }).jstree({
                core: {
                    data: v,
                    animation: false
                },
                plugins: ["checkbox"]
            });
        });
    }
</script>
@endsection