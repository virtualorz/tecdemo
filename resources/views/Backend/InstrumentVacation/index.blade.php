@extends('Backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">                                 
                <h3 class="panel-title">{{ trans('page.title.search') }}</h3>
                <ul class="panel-controls">
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                </ul>                                
            </div>
            <div class="panel-body">
                <form id="formq" method="get" action="{{ Sitemap::node()->getUrl(['id'=>Route::input('id')]) }}">
                    <table class="table datatable_simple nohead">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('validation.attributes.month') }}</td>
                                <td>
                                    <select name="year" id="data-year" class="form-control required" style="width:30%;display:inline">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @for($i=2010;$i<=date('Y')+5;$i++)
                                        <option value="{{$i}}" @if($i== $year) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                    年
                                    <select name="month" id="data-month" class="form-control required" style="width:30%;display:inline">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @for($i=1;$i<=12;$i++)
                                        <option value="{{$i}}" @if($i== $month) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                    月
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="submit" name="submit_search" value="1" class="btn btn-default btnSubmit">{{ trans('page.btn.search') }}</button>
                                    <button type="button" class="btn btn-default btnReset">{{ trans('page.btn.reset') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            
            </div>


            <div class="panel-body">
                <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="15%">{{ trans('validation.attributes.week_seven') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_one') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_two') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_three') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_four') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_five') }}</th>
                            <th width="15%">{{ trans('validation.attributes.week_six') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1;$i<=6;$i++)
                        <tr>
                            @for($j=0;$j<7;$j++)
                            <td>
                                @if($j+1+($i-1)*7-$week_first > 0 && $j+1+($i-1)*7-$week_first <= $last_day)
                                    {{ $j+1+($i-1)*7-$week_first }}<br>
                                    <select name="vacation_type[]" id="data-vacation_type" class="form-control">
                                        <option value="{{ $year.'-'.$month.'-'.($j+1+($i-1)*7-$week_first).'_' }}">{{trans('page.text.select_item')}}</option>
                                        @foreach($vacation_type as $k=>$v)
                                        <option value="{{ $year.'-'.$month.'-'.($j+1+($i-1)*7-$week_first).'_'.$k }}" 
                                            @if((date('w',strtotime($year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT))) == 0 || date('w',strtotime($year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT))) == 6) && $k==1)
                                             selected
                                            @endif
                                            @if(isset($listResult[$year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT)]) && $listResult[$year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT)]['vacation_type'] == $k) selected 
                                            @endif>
                                            {{$v}}
                                        </option>
                                        @endforeach
                                    </select>
                                    備註:
                                    <input type='text' name='remark[]' class="form-control" @if(isset($listResult[$year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT)])) value="{{ $listResult[$year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad(($j+1+($i-1)*7-$week_first),2,'0',STR_PAD_LEFT)]['remark'] }}" @endif>
                                @endif

                            </td>
                            @endfor
                        </tr>
                        @endfor
                    </tbody>
                </table>
                <table class="table">
                    <table>
                        <tr>
                            <th>&nbsp;</th>
                            <td> 
                                <input type="hidden" name="id" value="{{ $id }}" />
                                <input type="hidden" name="year" value="{{ $year }}" />
                                <input type="hidden" name="month" value="{{ $month }}" />
                                {!! ViewHelper::button('submit') !!}
                                {!! ViewHelper::button('cancel') !!}
                                @if(count($listResult) == 0) <span style="color:red;font-weight:bold">{{ trans('page.text.pre_data') }}</span>@endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                </form>
                
                @include('Backend.elements.pagination')
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">

    $(document).ready(function () {
        initValidation();
        urlBack = location.href;
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