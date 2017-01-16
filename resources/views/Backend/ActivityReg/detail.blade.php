@extends('Backend.layouts.master')

@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                    {{trans('page.title.activity_content')}}
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
                                <th>{{ trans('validation.attributes.activity_date') }}</th>
                                <td>{{ $dataResult['start_dt'] }} - {{ $dataResult['end_dt'] }}</td>
                            </tr>   
                            <tr>
                                <th>{{ trans('validation.attributes.apply_name') }}</th>
                                <td>{{ $dataResult['activity_name'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.level') }}</th>
                                <td>{{ $dataResult['level'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.time') }}</th>
                                <td>{{ $dataResult['time'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.score') }}</th>
                                <td>{{ $dataResult['score'] }}</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                    {{trans('page.title.reg_log')}}
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
                                <th width="15%">{{ trans('validation.attributes.apply_name') }}</th>
                                <td>{{ $dataResult['name'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.email') }}</th>
                                <td>{{ $dataResult['email'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.apply_reason') }}</th>
                                <td>{{ $dataResult['reason'] }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    @if($dataResult['is_pass'] == 0)
                                    <input type="hidden" name="id" value="{{ $dataResult['id'] }}" />
                                    <input type="hidden" name="is_pass" id="is_pass" value="1" />
                                    <button type="submit" class="btn btn-default btnSubmit" id="pass">{{ trans('page.btn.pass') }}</button>
                                    <button type="submit" class="btn btn-default btnSubmit" id="unpass">{{ trans('page.btn.unpass') }}</button>
                                    {!! ViewHelper::button('cancel') !!}
                                    @else
                                     {!! ViewHelper::button('back') !!}
                                    @endif
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
        $("#pass").click(function(){
            $("#is_pass").val("1");
        });
        $("#unpass").click(function(){
            $("#is_pass").val("0");
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