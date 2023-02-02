<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
        
        <script src="https://use.fontawesome.com/41c6567e56.js"></script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
            <!-- Just an image -->
            <nav class="navbar navbar-dark bg-primary navbar-expand-lg navbar-dark shadow-5-strong">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        <img src="{{ URL::asset('assets/images/logo-white.png') }}" alt="{{ config('app.name') }}" loading="lazy" class="block h-9 w-auto fill-current text-gray-800">
                    </a>

                    <!-- Toggle button -->
                    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Collapsible wrapper -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="{{ route('index') }}" class="nav-link"><i class="fa fa-home"></i> Home</a>
                        </li>

                        @if(!Auth::check())
                            <li class="nav-item dropdown">
                                <a href="{{ route('login') }}" class="nav-link"><i class="fa fa-user"></i> Log in</a>
                            </li>
    
                            @if (Route::has('register'))
                            <li class="nav-item dropdown">
                                <a href="{{ route('register') }}" class="nav-link"><i class="fa fa-user-plus"></i> Register</a>
                            </li>
                            @endif
                        @endauth
                    </ul>
                    <!-- Collapsible wrapper -->
                </div>
            </nav>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

            <div>
                <a href="{{ route('index') }}">
                    <img src="{{ URL::asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
