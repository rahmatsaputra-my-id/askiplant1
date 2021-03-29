<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">

    <title>@yield('title')</title>
  </head>
  <body>
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: rgba(33, 124, 199, 0.781)">
            <div class="container">
                <a class="navbar-brand" href="#">BPR ASKI</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="navbar-nav">
                        <a class="nav-link active" href="{{ url('/chart') }}" style="margin-top: 10px">Chart</a>
                        <a class="nav-link active" href="{{ url('/all') }}" style="margin-top: 10px">All Order</a>
                        <a class="nav-link active" href="{{ url('/price') }}" style="margin-top: 10px">Price</a>
                        @if(isset(Auth::user()->name))
                            <a class="nav-link active" href="{{ url('/logout') }}" style="margin-top: 10px">Logout</a>
                        @else
                            <script>window.location = "/";</script>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        @yield('mainbody')
        @yield('container')


    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/popper.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/datatable.fixheader.min.js')}}"></script>
    <script src="{{asset('js/datatable.min.js')}}"></script>
    @yield('js')
    @yield('jspagination')
</body>
</html>
