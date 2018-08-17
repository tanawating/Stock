<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Stock Dev.</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- styles -->
    <link href="{{ asset('assets/css/master_styles.css') }}" rel="stylesheet">
    <!-- fontawesome -->
    <link href="{{ asset('assets/font_awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.10/sweetalert2.min.css">

    @yield('style')

</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top" style="background: #008fed">
        <div class="container">
            <div class="navbar-header" >

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                <font color="#FFFFFF"><i class="fa fa-database" aria-hidden="true"></i>  Stock Dev.</font>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}"><font color="#FFFFFF">Login</font></a></li>
                        <li><a href="{{ url('/register') }}"><font color="#FFFFFF">Register</font></a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            
                                <font size="2" color="#FFFFFF">
                                   <i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ Auth::user()->email }} <span class="caret"></span>
                                </font>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="sidebar content-box" style="display: block;">
                    <ul class="nav">

                        @permission('read-stock')
                        <li><a href="{{ URL::to('main/stock') }}"><i class="glyphicon glyphicon-home"></i> Stock </a></li>
                        @endpermission

                        @permission('add-stock')
                        <li><a href="{{ URL::to('main/import') }}"><i class="glyphicon glyphicon-indent-left"></i> Add Stock </a></li>
                        @endpermission

                        @permission('cut-stock')
                        <li><a href="{{ URL::to('main/export') }}"><i class="glyphicon glyphicon-indent-right"></i> Cut Stock </a></li>
                        @endpermission

                        @permission('search-product')
                        <li><a href="{{ URL::to('main/search') }}"><i class="glyphicon glyphicon-search"></i> ค้นหาสินค้า </a></li>
                        @endpermission

                        @permission('read-user')
                        <li class="submenu">
                             <a href="#">
                                <i class="glyphicon glyphicon-user"></i> ผู้ใช้
                                <span class="caret pull-right"></span>
                             </a>
                             <ul>
                                <li><a href="{{ URL::to('main/user') }}"><i class="fa fa-minus-square-o" aria-hidden="true"></i> ผู้ใช้งาน</a></li>
                                <li><a href="{{ URL::to('main/role') }}"><i class="fa fa-minus-square-o" aria-hidden="true"></i> Role</a></li>
                            </ul>
                        </li>
                        @endpermission

                        @permission('read-master-data')
                        <li class="submenu">
                             <a href="#">
                                <i class="glyphicon glyphicon-list"></i> Master Data
                                <span class="caret pull-right"></span>
                             </a>
                             <ul>
                                <li><a href="{{ URL::to('masterdata/type_product') }}"><i class="fa fa-minus-square-o" aria-hidden="true"></i> ผลิตภัณฑ์</a></li>
                                <li><a href="{{ URL::to('masterdata/treasury') }}"><i class="fa fa-minus-square-o" aria-hidden="true"></i> คลัง</a></li>
                            </ul>
                        </li>
                        @endpermission

                    </ul>
                 </div>
            </div>

            @yield('content')

        </div>
    </div>

    <!-- JavaScripts -->
    
    <script src="{{ asset('assets\js\jquery.min.js') }}"></script>
    <script src="{{ asset('assets\bootstrap\js\bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.10/sweetalert2.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    @yield('script')
    
</body>
</html>
