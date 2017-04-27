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
                                <th>{{ trans('validation.attributes.journal_type') }}</th>
                                <td>
                                    {{ $journal[$dataResult['type']] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.release_dt') }}</th>
                                <td>
                                    {{ $dataResult['release_dt'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.topic') }}</th>
                                <td>
                                    <a href="{{ $dataResult['url'] }}" target="_blank">{{ $dataResult['topic'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.journal_topic') }}</th>
                                <td>
                                    <a href="{{ $dataResult['url'] }}" target="_blank">{{ $dataResult['journal'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.author') }}</th>
                                <td>
                                    {{ $dataResult['author'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.remark') }}</th>
                                <td>
                                    {{ $dataResult['remark'] }}
                                </td>
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

    $(document).ready(function () {
        
    });
</script>
@endsection