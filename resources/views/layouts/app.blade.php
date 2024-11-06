<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('/assets/lib/jquery-toast/jquery.toast.css')}}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('/assets/js/jquery-3.6.0.min.js')}}"></script>

    <!-- jQuery validation -->
    <script src="{{ asset('/assets/js/jquery.validate.min.js')}}"></script>

    <!-- Vite Scripts -->
    @vite(['resources/js/app.js'])


    <!-- Build/Assets/JS -->
    <script src="{{asset("/assets/js/app.js")}}"></script>
    <script src="{{asset("/assets/lib/jquery-toast/jquery.toast.js")}}"></script>

    <!-- Build/Assets/SCSS -->
    <link href="{{asset('/assets/css/app.css')}}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-0 m-0">
            <div class="container">
                <a class="navbar-brand d-flex flex-column align-items-end" href="{{ url('/') }}">
                    <!-- <img src="{{ url('/assets/images/logo.webp') }}" alt="logo img"
                        style="width: 160px; height: 60px; object-fit: cover;"> -->
                    <img src="{{ asset('/assets/images/logo.webp') }}" alt="logo img"
                        style="width: 160px; height: 60px; object-fit: cover;">
                    <span class="d-flex justify-content-center align-items-center gap-1">
                        <span>
                            <i class="fa-regular fa-address-card fs-3"></i>
                        </span>
                        <span class="fw-bold">会員管理</span>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse align-self-end" id="navbarSupportedContent">
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
                        <li class="nav-item dropdown d-none d-sm-flex bg-light px-3 py-4">
                            <div id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" v-pre>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-1">
                                        <img class="rounded-circle" src="{{url('/assets/images/default-user.png')}}"
                                            alt="user-img" style="width: 45px">
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="text-muted fw-light" style="font-size: .67rem">ようこそ</span>
                                        <span class="nav-link px-0 fw-bold fs-5">
                                            {{ Auth::user()->name }} ユーザー
                                        </span>
                                    </div>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-user-pen pe-1"></i>
                                        {{ __('プロフィール編集') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket pe-1"></i>
                                        {{ __('ログアウト') }}
                                    </a>

                                    <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>

                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Mobile Menu -->
        <div class="collapse navbar-collapse align-self-end" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto gap-4 p-3">
                <li><a class="nav-link text-decoration-none text-muted" href="{{route('manager.list')}}">管理者</a></li>
                <li><a class="nav-link text-decoration-none text-muted" href="#">契約ユーザー</a></li>
                <li><a class="nav-link text-decoration-none text-muted" href="">フィールド管理</a></li>
                <li>
                    <a class="btn btn-danger w-100" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        {{ __('ログアウト') }}
                    </a>

                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- End Mobile Menu-->

        <!-- Desktop Menu -->
        <div class="d-none d-md-block" style="background-color: #8ca5b4">
            <div class="container">
                <ul class="d-flex gap-4 p-3 text-white" style="list-style-type: none">
                    <li>
                        <a class="nav-link text-decoration-none" href="{{route('manager.list')}}">
                            <i class="fa-solid fa-user-group fs-8"></i>
                            <span>管理者</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" style="opacity: .5" href="">
                            <i class="fa-solid fa-users fs-8"></i>
                            <span>契約ユーザー</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" style="opacity: .5" href=""><i
                                class="fa-solid fa-table-list fs-8"></i>
                            <span>フィールド管理</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End Desktop Menu -->

        <main>
            <div class="container">
                <div class="mb-3">
                    @yield('breadcrumb')
                </div>
                <div class="bg-white p-3">
                    @yield('content')
                </div>
            </div>
        </main>
        <footer class="position-fixed bottom-0 w-100 bg-white">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <div>2024 &copy; CUSC</div>
                    <div>Powered by CUSC - ITO</div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>