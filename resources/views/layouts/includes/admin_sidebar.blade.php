<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('front.home') }}" class="brand-link">
        <img src="{{asset('dist')}}/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist')}}/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!--  <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @role('superadmin')
                <li class="nav-item">
                    <a href="{{route('admin.user_manager.list')}}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Admin Manager</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.facilities_manager.list')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-heart"></i>
                        <p>Fasilitas</p>
                    </a>
                </li>
                @endrole

                <li class="nav-item">
                    <a href="{{route('admin.kos_manager.list')}}" class="nav-link">
                        <i class="nav-icon fas fa-hotel"></i>
                        <p>Rumah Kos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form id="menu-logout" method="POST" action="{{ route('logout') }}">
                        @csrf
                    </form>
                    <a onclick="$('#menu-logout').submit(); return false;" class="nav-link" style="cursor:pointer;">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>