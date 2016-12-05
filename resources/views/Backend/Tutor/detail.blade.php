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
                                <th>{{ trans('validation.attributes.tutor_time') }}</th>
                                <td>
                                    {{ $dataResult['date'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.school') }}</th>
                                <td>
                                    @foreach($twCity as $k=>$v)
                                    @if($k == $dataResult['city']) {{$v}} @endif
                                    @endforeach
                                    /
                                    @foreach($twTown[$dataResult['city']] as $k=>$v)
                                    @if($k == $dataResult['town']) {{$v[1]}} @endif
                                    @endforeach
                                    /
                                    {{$dataResult['school_name']}}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.tutor_member') }}</th>
                                <td>
                                    {{ $dataResult['member'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.tutor_content') }}</th>
                                <td>
                                    @if(isset($dataResult['content']))
                                    @include('backend.elements.btseditor', ['btseditorContent' => $dataResult['content']])
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.file') }}</th>
                                <td>
                                    @foreach($dataResult['file'] as $k=>$v)
                                    <a href="{{$v['url']}}" target="_blank">{{$v['name']}}</a><br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('validation.attributes.photo') }}</th>
                                <td>
                                    @foreach($dataResult['photo'] as $k=>$v)
                                    <img src="{{$v['url']}}" width="300px" /><span>{{$v['text']}}</span><br>
                                    @endforeach
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

    $(document).ready(function () {
        
    });
</script>
@endsection