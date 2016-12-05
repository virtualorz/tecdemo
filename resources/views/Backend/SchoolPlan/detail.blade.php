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
                                <th>{{ trans('validation.attributes.school') }}</th>
                                <td>
                                    {{ $dataResult['school_name'] }}
                                </td>
                            </tr>   
                            <tr>
                                <th>{{ trans('validation.attributes.plan-topic') }}</th>
                                <td>
                                    {{ $dataResult['topic'] }}
                                </td>
                            </tr>  
                            <tr>
                                <th>{{ trans('validation.attributes.plan-idea') }}</th>
                                <td>
                                    {{ $dataResult['idea'] }}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.plan-class') }}</th>
                                <td>
                                    @if(isset($dataResult['plan']))
                                    @include('backend.elements.btseditor', ['btseditorContent' => $dataResult['plan']])
                                    @endif
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.plan-file') }}</th>
                                <td>
                                    @foreach($dataResult['file'] as $k=>$v)
                                    <a href="{{$v['url']}}" target="_blank">{{$v['name']}}</a><br>
                                    @endforeach
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.related_group') }}</th>
                                <td>
                                    @foreach($dataResult['related_group'] as $k=>$v)
                                    {{$v}}<br>
                                    @endforeach
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.contact') }}</th>
                                <td>
                                    聯絡人 : {{$dataResult['contact_name']}}<br>
                                    信箱 : {{$dataResult['contact_tel']}}<br>
                                    電話 : {{$dataResult['contact_email']}}
                                </td>
                            </tr> 
                            <tr>
                                <th>{{ trans('validation.attributes.related_web') }}</th>
                                <td>
                                    @foreach($dataResult['related_url'] as $k=>$v)
                                    {{$v['name']}} <a href="{{$v['url']}}" target="_blank">{{$v['url']}}</a><br>
                                    @endforeach
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