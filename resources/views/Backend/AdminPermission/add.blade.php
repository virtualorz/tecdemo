@extends('Backend.layouts.master')
@expr(ViewHelper::plugin()->load('jstree'))

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
                                <th><span class="red">*</span>{{ trans('validation.attributes.name') }}</th>
                                <td>
                                    <input type="text" name="name" id="data-name" class="form-control required">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.permission') }}</th>
                                <td>
                                    <input type="hidden" name="content" id="data-content"  />
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
    var treePermissionJson = {!! $treePermissionJson !!};

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
                    $con.jstree('open_node', id);
                    if (node.parents.length < 2) {
                        $.each(node.children, function (kk, vv) {
                            tmpFunc($con, vv);
                        });
                    }
                };
                var $this = $(this);
                var root = data.instance.get_node('#');
                $.each(root.children, function (kk, vv) {
                    tmpFunc($this, vv);
                });
            }).on('changed.jstree', function (e, data) {
                var tmpFuncAdd = function ($con, id) {
                    var node = $con.jstree('get_node', id);
                    tmpFuncDoAdd(node.original.permission);
                    $.each(node.children, function (kk, vv) {
                        tmpFuncAdd($con, vv);
                    });
                };
                var tmpFuncDel = function ($con, id) {
                    var node = $con.jstree('get_node', id);
                    tmpFuncDoDel(node.original.permission);
                    $.each(node.children, function (kk, vv) {
                        tmpFuncDel($con, vv);
                    });
                };
                var tmpFuncDoAdd = function (permission) {
                    $.each(permission, function (kk, vv) {
                        if ($.inArray(vv, $divCon.data('value')) === -1) {
                            $divCon.data('value').push(vv);
                        }
                    });
                };
                var tmpFuncDoDel = function (permission) {
                    $.each(permission, function (kk, vv) {
                        var pos = $.inArray(vv, $divCon.data('value'));
                        if (pos !== -1) {
                            $divCon.data('value').splice(pos, 1);
                        }
                    });
                };

                var $this = $(this);
                var parents = data.node.parents.slice()
                parents.splice(parents.indexOf('#'), 1);
                if (data.node.state.selected) {
                    tmpFuncAdd($this, data.node.id);
                    $.each(parents, function (kk, vv) {
                        var node = $this.jstree('get_node', vv);
                        tmpFuncDoAdd(node.original.permission);
                    });
                    if(parents.length > 0){
                        var node = $this.jstree('get_node', parents[0] + '_index');
                        if(node !== false){
                            $this.jstree('select_node', node);
                        }
                    }
                } else {
                    tmpFuncDel($this, data.node.id);
                    $.each(parents, function (kk, vv) {
                        if (!$this.jstree('is_selected', vv) && !$this.jstree('is_undetermined', vv)) {
                            var node = $this.jstree('get_node', vv);
                            tmpFuncDoDel(node.original.permission);
                        }
                    });
                    if (parents.length > 0) {
                        if (data.node.id == parents[0] + '_index') {
                            var node = $this.jstree('get_node', parents[0]);
                            if (node !== false) {
                                $this.jstree('select_node', node);
                                $this.jstree('deselect_node', node);
                            }
                        }
                    }
                }

                $('#' + eleId).val($divCon.data('value').join());
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