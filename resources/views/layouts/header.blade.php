<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=0.3,minimum-scale=0.3 maximum-scale=1.5">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css">
    <link href="https://vjs.zencdn.net/7.1.0/video-js.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <!-- Script -->
    <script  src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>
    <header id="header">
        <div class="container">
            <div class="row logo-name">
                <div class="col-md-2 col-2"><a href="{{ url('/') }}"><p>Soccer</p><span>Times</span></a></div>
                <div class="col-md-10 col-10"></div>
            </div>
            <div class="row">
                <div class="col-md-8 col-8">
                    <nav>
                        <ul>
                            <li><a class="{{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
                            <li><a class="{{ Request::is('news') ? 'active' : '' }}" href="{{ url('news') }}">Новости</a></li>
                            <li><a class="{{ Request::is('results') ? 'active' : '' }}" href="{{ url('results') }}">Результаты</a></li>
                            <li><a class="{{ Request::is('livescore') ? 'active' : '' }}" href="{{ url('livescore') }}">Live Score</a></li>
                            <li><a class="{{ Request::is('video') ? 'active' : '' }}" href="{{ url('video') }}">Видео</a></li>
                            <li><a class="{{ Request::is('gallery') ? 'active' : '' }}" href="{{ url('gallery') }}">Фотогалерея</a></li>
                            <li><a class="{{ Request::is('transfers') ? 'active' : '' }}" href="{{ url('transfers') }}">Трансферы</a></li>
                            <li><a class="{{ Request::is('authors') ? 'active' : '' }}" href="{{ url('authors') }}">Автора</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-4 col-4 auth">
                    @if (Auth::guard('web')->check())
                        <div>
                            <div class="dropdown loged">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{asset('images/uploads/users/w250')}}/{{Auth::guard('web')->user()->image}}">
                                    <p>{{ Auth::guard('web')->user()->name }}</p> <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" role="menu">
                                    <a href="{{ action('User\Office\HomeController@index' , [Auth::guard('web')->user()->name]) }}">Личный кабинет</a>
                                    
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выйти
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    
                                </div>
                            </div>
                        </div>
                    @elseif (Auth::guard('admin')->check())
                        <div>
                            <div class="dropdown loged">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{asset('images/uploads/users/w250')}}/{{Auth::guard('admin')->user()->image}}">
                                    <p>{{ Auth::guard('admin')->user()->name }}</p> <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" role="menu">
                                    <a href="{{ action('Admin\Office\HomeController@index') }}">Личный кабинет</a>
                                    
                                    <a href="{{ route('logout_admin') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Выйти
                                    </a>
                                    <form id="logout-form" action="{{ route('logout_admin') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <button class="auth-butt"><span><img src="{{ asset('images/log-in.png') }}" alt="">Войти</span><span> | </span><span><img src="{{ asset('images/reg-in.jpg') }}" alt="">Регистрация</span></button>
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="cc">
                    @foreach ($league as $league)
                        <div>
                            <a href="{{route('cc-livescore', ['id'=>$league->id, 'name'=>str_slug($league->name)])}}?league={{$league->id}}">{{$league->name}}</a>
                        </div>
                    @endforeach
                    @foreach ($cc as $cc)
                        <div>
                            <a href="{{route('cc-livescore', ['id'=>$cc->id, 'name'=>str_slug($cc->name)])}}">{{$cc->name}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </header>