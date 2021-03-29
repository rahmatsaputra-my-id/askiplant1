<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    {{-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/datatable.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
    @yield('style')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>@yield('title')</title>
  </head>
  <body>
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: rgb(252, 252, 252)">
            <div class="containers">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <div id="main">
                                    <button class="openbtn" onclick="openNav()">&#9776;</button>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="#">ASKI PLANT 1</a>
            </div>
        </nav>

        <div id="mySidebar" class="sidebar" style="background-color: rgb(252, 252, 252)">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="{{route('report.achievement')}}">Achievement</a>
            <a href="#">Linestop</a>
            <a href="{{route('report.rejection')}}">Rejection</a>
            <a href="{{route('report.realtime')}}">Realtime Dashboard</a>
        </div>

        @yield('container')


    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script type="text/javascript">
        /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
        function openNav() {
        document.getElementById("mySidebar").style.width = "200px";
        document.getElementById("main").style.marginLeft = "0px";
        }

        /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
        function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        }
    </script>
    <script src="{{asset('js/jquery-2.js')}}"></script>
    {{-- <script src="{{asset('js/jquery.js')}}"></script> --}}
    <script src="{{asset('js/popper.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/datatable.fixheader.min.js')}}"></script>
    <script src="{{asset('/js/datatable.min.js')}}"></script>
    <script src="{{asset('/js/collapse.js')}}"></script>
    @yield('js')
    @yield('jspagination')
</body>
</html>
