        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">

                <div class="sidebar-brand-text mx-3">CarWorkshop</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">



            @if (auth()->user()->role == 'admin')
                <!-- Heading -->
                <div class="sidebar-heading">
                    Master Data
                </div>
                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('owner') }}">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Car Owner</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('mechanic') }}">
                        <i class="fas fa-fw fa-user-cog"></i>
                        <span>Car Mechanic</span></a>
                </li>
                <!-- Nav Item - Charts -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('service') }}">
                        <i class="fas fa-fw fa-cogs"></i>
                        <span>Service</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('status') }}">
                        <i class="fas fa-fw fa-clipboard-check"></i>
                        <span>Status</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <div class="sidebar-heading">
                    Transaction
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('car-repair') }}">
                        <i class="fas fa-fw fa-car-crash"></i>
                        <span>Car Repair</span></a>
                </li>
            @endif
            @if (auth()->user()->role == 'owner')
                <!-- Heading -->
                <div class="sidebar-heading">
                    Transaction
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('owner-car') }}">
                        <i class="fas fa-fw fa-car-crash"></i>
                        <span>Car Repair</span></a>
                </li>
            @endif
            @if (auth()->user()->role == 'mechanic')
                <!-- Heading -->
                <div class="sidebar-heading">
                    Work Order
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('mechanic-repair') }}">
                        <i class="fas fa-fw fa-car-crash"></i>
                        <span>Car Repair</span></a>
                </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
        <!-- End of Sidebar -->
