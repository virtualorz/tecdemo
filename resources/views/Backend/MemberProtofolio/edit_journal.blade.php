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
                                <th><span class="red">*</span>{{ trans('validation.attributes.journal_type') }}</th>
                                <td>
                                    <select name="journal_type" id="data-journal_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($journal as $k=>$v)
                                        <option value="{{$k}}" @if($k == $dataResult['type']) selected @endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.release_dt') }}</th>
                                <td>
                                    <input type="text" name="release_dt" id="data-release_dt" class="form-control datepicker required" value="{{ $dataResult['release_dt'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.topic') }}</th>
                                <td>
                                    <input type="text" name="topic" id="data-topic" class="form-control required" value="{{ $dataResult['topic'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.journal_topic') }}</th>
                                <td>
                                    <input type="text" name="journal" id="data-journal" class="form-control required" value="{{ $dataResult['journal'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.author') }}</th>
                                <td>
                                    <input type="text" name="author" id="data-author" class="form-control required" value="{{ $dataResult['author'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.url') }}</th>
                                <td>
                                    <input type="text" name="url" id="data-url" class="form-control required" value="{{ $dataResult['url'] }}">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="red">*</span>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    <textarea name="remark" id="data-remark" rows="4" cols="50">{{ $dataResult['remark'] }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td> 
                                    <input type="hidden" name="id" value="{{ $dataResult['member_data_id'] }}_{{ $dataResult['member_journal_id'] }}" />
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