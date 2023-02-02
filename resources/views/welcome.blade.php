<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>
        
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <script src="https://use.fontawesome.com/41c6567e56.js"></script>
        
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="{{ URL::asset('assets/js/jquery-3.6.3.js') }}"></script>
        
        @yield('script')
    </head>
    <body class="font-sans antialiased" cz-shortcut-listen="true">
        <div class="min-h-screen bg-gray-100">
            <!-- Just an image -->
            <nav class="navbar navbar-dark bg-primary navbar-expand-lg navbar-dark shadow-5-strong">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('index') }}">
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

                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item dropdown">
                                    <a href="{{ url('/dashboard') }}" class="nav-link"><i class="fa fa-dashboard"></i> Dashboard</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" style="a{color:#fff}">
                                        @csrf

                                        <button type="submit" class="nav-link" title="Restore post"><i class="fa fa-sign-out"></i> Logout</button>
                                    </form>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a href="{{ route('login') }}" class="nav-link"><i class="fa fa-user"></i> Log in</a>
                                </li>
        
                                @if (Route::has('register'))
                                <li class="nav-item dropdown">
                                    <a href="{{ route('register') }}" class="nav-link"><i class="fa fa-user-plus"></i> Register</a>
                                </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                    <!-- Collapsible wrapper -->
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 text-center">
                                <b class="h2">Welcome the biggest <b>blog</b> community</b>
                            </div>
                            
                            <hr>

                            <div class="container px-4 px-lg-5 mt-5 mb-5">
                                @if(count($posts) > 0)
                                <div class="row">
                                    <div class="col mb-5 d-flex">
                                        {!! $posts->links() !!}
                                    </div>
                                </div>
                                @endif
                                            
                                <div class="alert message" role="alert" style="display: none;"></div>            
                                    <div class="row">
                                        @if(count($posts) > 0)
                                            @foreach($posts as $post)
                                            <div class="col-sm article">
                                                <!-- Product image-->
                                                <a href="{{ $post->image }}" target="_blank">
                                                    <img class="card-img-top" src="{{ $post->thumbnail }}" alt="" title="{{ $post->title }}">
                                                </a>
        
                                                <!-- Product details-->
                                                <div class="card-body p-4">
                                                    <div class="text-center mb-2">
                                                        <!-- Product name-->
                                                        <h5 class="fw-bolder">{{ $post->title }}</h5>
                                                        <!-- Product price-->
                                                        
                                                        <p>{{ Str::limit($post->excerpt, 100) }}</p>
                                                    </div>

                                                    <hr>

                                                    <small>Publish date : {{ $post->publish_date }}</small>

                                                    <a href="{{ route('posts.view', $post->slug) }}" class="btn btn-primary">View</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else

                                        @endif
                                    </div>
                                                                
                                @if(count($posts) > 0)
                                <div class="row">
                                    <div class="col mb-5 d-flex">
                                        {!! $posts->links() !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
