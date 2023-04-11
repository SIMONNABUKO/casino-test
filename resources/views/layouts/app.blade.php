<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('client/assets/css/custom.css')}}">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
        <a class="navbar-brand ml-2 font-weight-bold" href="/">CASINO</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor" aria-controls="navbarColor" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor">
            <ul class="navbar-nav">
                @guest
                <li class="nav-item"><a class="nav-link" href="/register">Register</a> </li>
                <li class="nav-item"><a class="nav-link" href="/login">Login</a> </li>
                @endguest
                @auth
                 <li class="nav-item "><a class="nav-link" href="#">{{auth()->user()->username}}</a> </li>
                <li class="nav-item "><a class="nav-link" href="#">Bal. {{auth()->user()->balance}}</a> </li> 
                @endauth
                
                <!-- <li class="nav-item "><a class="nav-link" href="#">Home</a> </li>
                <li class="nav-item "><a class="nav-link" href="#">Sale</a> </li>  -->
            </ul>
        </div>
        </div>
    </nav>


    <div id="app">
        @yield('content')
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    @vite('resources/js/app.js','/build')




</body>

</html>