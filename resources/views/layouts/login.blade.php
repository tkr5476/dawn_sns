<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/top">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">{{--特にいじっていないがloginControkkersに記載あり--}}
                                    @csrf
                                </form>
                                <form action="/loginUser/profile" method="get">
                                    @csrf
                                    <button type="submit" class="btn">プロフィール編集</button>
                                </form>
                                <form action="/top" method="get">
                                    @csrf
                                    <button type="submit" class="btn">HOME</button>
                                </form>

                            </div>


                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex justify-center items-center gap-3">
            <div>
                @yield('content')
            </div>
            <div class="w-1/2">
            <nav class="pull-right submit-btn">
                <ul>
                    <li>
                        <p>フォロー数：{{ $followCounts }}</p>
                        <span><a href="/followList/index" class="btn btn-primary">フォローリスト</a></span>
                    </li>
                    <li>
                        <p>フォロワー数：{{ $followerCounts }}</p>
                        <span><a href="/followerList/index" class="btn btn-primary">フォロワーリスト</a></span>
                    </li>
                    <li><a href="/user/search" class="btn btn-primary">ユーザー検索</a></li>

                </ul>
            </nav>
        </main>



    </div>
</body>

</html>
