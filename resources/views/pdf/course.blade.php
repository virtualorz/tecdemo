@expr(ViewHelper::plugin()->load('jquery'))
@expr(ViewHelper::plugin()->load('jqueryui'))
@expr(ViewHelper::plugin()->load('json'))
@expr(ViewHelper::plugin()->load('bootstrap'))
<!DOCTYPE html>
<html lang="zh-TW">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title>{{ $_pageTitle == '' ? '' : $_pageTitle . ' - ' }}{{ trans('app.name') }}</title>      
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />  

        {!! ViewHelper::plugin()->renderCss() !!}
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/bootstrap.min.css') }}" />
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/modern-business.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/combine.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/page.css') }}" />
        <!-- Custom Fonts -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/font-awesome/css/font-awesome.min.css') }}" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- InstanceBeginEditable name="head" -->
        <!-- InstanceEndEditable -->
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/btseditor/btseditor_content.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/fix.css') }}" />

    </head>
    <body>
        <div class="pageCon clearfix">
            <article class="container tpcontent">
                <div class="row">
                    <section>
                        <h1>{{ Sitemap::getName('Official.course') }}</h1>
                        <h2>{{ $_pageTitle }}</h2>

                        <div class="spacer-15"></div>

                        @if(count($data_course) > 0)
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-hover courseinfo"> 
                                <tbody> 
                                    <tr>
                                        <th scope="row" width="15%">{{ trans('validation.attributes.course-code') }}</th>
                                        <td>{{ $data_course['code'] }}</td>
                                    </tr> 
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-name_cht') }}</th>
                                        <td>{{ $data_course['name_cht'] }}</td> 
                                    </tr> 
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-name_eng') }}</th>
                                        <td>{{ $data_course['name_eng'] }}</td> 
                                    </tr> 
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-duration') }}</th>
                                        <td>
                                            {{ $data_course['duration'] }}
                                            {{ trans('enum.course-duration_type.' .  $data_course['duration_type']) }}
                                        </td> 
                                    </tr> 
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-class_time') }}</th>
                                        <td>{{ $data_course['class_time'] }}</td> 
                                    </tr> 
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-fee') }}</th>
                                        <td>{{ number_format($data_course['fee']) }}</td> 
                                    </tr>
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-point') }}</th>
                                        <td>{{ $data_course['point'] }}</td> 
                                    </tr>
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-teaching') }}</th>
                                        <td>{{ $data_course['teaching'] }}</td> 
                                    </tr>
                                    <tr> 
                                        <th scope="row">{{ trans('validation.attributes.course-test_code') }}</th>
                                        <td>{{ $data_course['test_code'] }}</td> 
                                    </tr> 
                                </tbody> 
                            </table> 
                        </div>

                        <div class="coursebox">
                            <h3>{{ trans('validation.attributes.course-courset_class') }}</h3>
                            <div class="coursecontent">
                                @if(count($list_course_class) > 0)
                                @foreach($list_course_class as $k => $v)
                                <p>                        
                                    @if($v['date_unlimited'] == 1)
                                    {{ trans('validation.attributes.course_class-date_unlimited') }}
                                    @else
                                    {{ $v['date_start'] }} ~ {{ $v['date_end'] }}
                                    @endif 
                                    {{ $v['course_class_locale_name'] }}
                                    {{ trans('enum.course_class-class_time.' .  $v['class_time']) }}
                                    @if($v['suspend'] != '')
                                    <br />
                                    <strong>
                                        {{ trans('validation.attributes.course_class-date_suspend') }}：
                                        {{ $v['suspend'] }}
                                    </strong>
                                    @endif
                                </p>                    
                                <br />
                                @endforeach
                                @else
                                {{ trans('message.info.nodata') }}
                                @endif
                            </div>

                            <h3>{{ trans('validation.attributes.course-goal') }}</h3>
                            <div class="coursecontent">
                                @include('Official.elements.btseditor', ['btseditorContent' => $data_course['goal']])
                            </div>

                            <h3>{{ trans('validation.attributes.course-target') }}</h3>
                            <div class="coursecontent">
                                @include('Official.elements.btseditor', ['btseditorContent' => $data_course['target']])
                            </div>


                            <h3>{{ trans('validation.attributes.course-content') }}</h3>
                            <div class="coursecontent">
                                @include('Official.elements.btseditor', ['btseditorContent' => $data_course['content']])
                            </div>

                            <h3>{{ trans('validation.attributes.course-basic') }}</h3>
                            <div class="coursecontent">
                                @include('Official.elements.btseditor', ['btseditorContent' => $data_course['basic']])
                            </div>
                        </div>
                        @else
                        @endif
                    </section>
                </div>
            </article>
        </div>
        {!! ViewHelper::plugin()->renderJs() !!}
        <script type="text/javascript" src="{{ asset('assets/official/js/global.js') }}"></script>
    </body>
</html>
