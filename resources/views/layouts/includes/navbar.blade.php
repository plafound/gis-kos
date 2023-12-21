<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        @yield('navbar-left-item')

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @auth
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&size=160&bold=true" class="user-image img-circle" alt="User Image">
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                @else
                    <img src="{{asset('dist')}}/img/logo/user_logo.png" class="user-image img-circle" alt="User Image">
                    <span class="d-none d-md-inline">Akun</span>
                @endauth
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    @auth
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&size=160&bold=true" class="img-circle" alt="User Image">
                        <p>
                            {{ auth()->user()->name }}
                            <small>Member sejak {{date_format(auth()->user()->created_at, "M. Y")}}</small>
                        </p>
                    @else
                        <img src="{{asset('dist')}}/img/logo/user_logo_white.png" class="img-circle mt-3" alt="User Image">
                        <p>
                            Silahkan Pilih Salah Satu
                        </p>
                    @endauth
                </li>

                <!-- Menu Footer-->
                @auth
                    <li class="user-footer">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-default btn-flat">Dashboard</a>
                        <a onclick="$('#menu-logout').submit(); return false;" class="btn btn-default btn-flat float-right" style="cursor:pointer;">Logout</a>
                    </li>
                @else
                    <li class="user-footer">
                        <a href="{{ route('login') }}" class="btn btn-default btn-flat">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-default btn-flat float-right">Daftar</a>
                    </li>
                @endauth
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>