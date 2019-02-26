@extends('nightbot::layout')

@section('body')


    <header class="header text-white h-fullscreen pb-8" style="background-image: linear-gradient(0deg, #222222 0%, #472d75 100%);">
        <canvas class="overlay opacity-95" data-granim="#222222,#472d75"></canvas>
        <canvas class="constellation" data-color="rgba(255,255,255,1)"></canvas>
        <div class="container text-center position-static">

            <div class="row h-100">
                <div class="col-md-10 col-xl-10 mx-auto align-self-center mt-6">

                    <p class="lead-3 col-xl-9 col-md-9 mx-auto mt-6 mb-7">
                        Спасибо, вы успешно активировали бота!
                    </p>
                    {{--<a href="{{ $url }}" style="background-color: #613f9f; color: white;" class="btn btn-xl fs-20 fw-500 w-350 shadow-3 hidden-sm-down border-glass-2" >--}}
                        {{--@lang('nightbot::auth.button')--}}
                    {{--</a>--}}

                </div>

                <div class="col-lg-8 mx-auto align-self-center pt-2">

                    {{--<a class="font-size-18" href="mail:{{ $email }}">--}}
                        {{--{{ $email }}--}}
                    {{--</a>--}}

                </div>
            </div>

        </div>
    </header>

    <!-- Scripts -->
    <script src="{{ asset('/') }}landing/js/page.min.js"></script>
    <script src="{{ asset('/') }}landing/js/script.js"></script>

@endsection