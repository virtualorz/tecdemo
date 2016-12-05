<div id="school-heading">
        <header class="intro-header" style="background-image : url({{$dataResult[0]['school_photo']}})">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <img src="{{ asset('assets/official/img/schoolimg.png') }}" width="100" height="70" alt=""/>
                            <h1>{{ $twCity[$dataResult[0]['city']] }} {{ $dataResult[0]['school_name'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>