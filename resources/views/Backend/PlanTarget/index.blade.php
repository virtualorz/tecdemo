@extends('backend.layouts.master')


@section('head')
@endsection



@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
            {!! ViewHelper::button('add') !!}
            {!! ViewHelper::button('delete') !!}
            {!! ViewHelper::button('orderv2',['class'=>'order']) !!}
            </div>


            <div class="panel-body">
                <table class="table datatable_simple table_responsive">
                    <thead>
                        <tr>
                            <th width="5%" nowrap style="color:#fff;">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItemAll" /> {{ trans('page.btn.select_all') }}</label>
                            </th>
                            <th width="15%">{{ trans('validation.attributes.created_at') }}</th>
                            <th width="15%">{{ trans('validation.attributes.name-target') }}</th>
                            <th width="15%">{{ trans('validation.attributes.dt-start') }}</th>
                            <th width="15%">{{ trans('validation.attributes.dt-end') }}</th>
                            <th width="25%">{{ trans('page.text.function') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listResult as $k => $v)
                        <tr>
                            <td data-headname="{{ trans('page.btn.select') }}">
                                <label class="check"><input type="checkbox" class="icheckbox ckbItem" value="{{ $v['id'] }}" /></label>
                            </td>
                            <td>{{ $v['created_at'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>{{ $v['start_dt'] }}</td>
                            <td>{{ $v['end_dt'] }}</td>
                            <td>
                                {!! ViewHelper::button('edit', ['id' => $v['id']]) !!}
                                {!! ViewHelper::button('detail', ['id' => $v['id']]) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('backend.elements.pagination')
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default" id="show_order" style="display:none">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('validation.attributes.order') }}</h3>
    </div>
    <form id="formorder" method="post" action="{{ Sitemap::node()->getChildren('set_order')->getUrl() }}">
    <div class="panel-body">
        <ul class="list-group border-bottom" id="sortable">
           
        </ul>                                
    </div>
    <div class="panel-heading">
        {!! ViewHelper::button('submit') !!}
        {!! ViewHelper::button('cancel') !!}
    </div>
    </form>
</div>
@endsection


@section('script')
<script src="{{ asset('assets/official/js/jquery.blockUI.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function () {
        initValidation();
        $( "#sortable" ).sortable({
          placeholder: "ui-state-highlight"
        });
        $( "#sortable" ).disableSelection();

        $(".btnSubmit").click(function(){
            $("#data_sort_result").val($( "#sortable" ).sortable( "toArray" ));
        });

        $('.order').click(function() { 
            $.blockUI({ 
                message: $('#show_order'), 
                css: { top: '20%',cursor: 'pointer' } 
            }); 

            ajaxRequest.request({
                url: $(this).attr('data-url'),
                senderSelector: $(this),
                resultOk: function (response) {
                    $("#sortable").html("");
                    var html = "";
                    for(var i =0;i<response.data.length;i++)
                    {
                        html += '<li class="list-group-item" id="'+response.data[i]['id']+'">';
                        html += response.data[i]['name'];
                        html += '</li>';
                    }
                    html +="<input type='hidden' name='sort_result' id='data_sort_result'>";
                    $("#sortable").append(html);
                }
            });

        }); 

        $('.btnCancel,.btnBack').each(function () {
            $(this).attr('data-url', urlBack);
        });
    });

    function initValidation() {
        $('#formorder').validate({
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