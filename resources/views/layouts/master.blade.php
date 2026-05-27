<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" />

</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ route('admin.dashboard') }}" aria-label="adminHMD dashboard">
                    <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">admin</span>
                        <span class="brand-subtitle">Admin Template</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.dashboard') ? 'aria-current=page' : '' }} href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
                @if (auth()->user()->role_id == 1)
                    <a class="nav-link {{ request()->routeIs('employee-list') ? 'active' : '' }}"
                    {{ request()->routeIs('employee-list') ? 'aria-current=page' : '' }} href="{{ route('employee-list') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Employees</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('leave.requests') ? 'active' : '' }}"
                    {{ request()->routeIs('leave.requests') ? 'aria-current=page' : '' }} href="{{ route('leave.requests') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Leave  Request List </span>
                    </a>
                @elseif(auth()->user()->role_id == 2)
                    <a class="nav-link {{ request()->routeIs('team-list') ? 'active' : '' }}"
                    {{ request()->routeIs('team-list') ? 'aria-current=page' : '' }} href="{{ route('team-list') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Team Members</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('leave.requests') ? 'active' : '' }}"
                    {{ request()->routeIs('leave.requests') ? 'aria-current=page' : '' }} href="{{ route('leave.requests') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Leave Requests</span>
                    </a>
                @endif
                <a class="nav-link {{ request()->routeIs('holiday.list') ? 'active' : '' }}"
                    {{ request()->routeIs('holiday.list') ? 'aria-current=page' : '' }} href="{{ route('holiday.list') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Company Holidays</span>
                    </a>
                @if (auth()->user()->role_id == 1)
                    <a class="nav-link {{ request()->routeIs('team.list') ? 'active' : '' }}"
                    {{ request()->routeIs('team.list') ? 'aria-current=page' : '' }} href="{{ route('team.list') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Teams</span>
                    </a>
                @endif
                    <a class="nav-link {{ request()->routeIs('leave.type') ? 'active' : '' }}"
                    {{ request()->routeIs('leave.type') ? 'aria-current=page' : '' }} href="{{ route('leave.type') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Leave Types</span>
                    </a>
                @if (auth()->user()->role_id == 3)
                    <a class="nav-link {{ request()->routeIs('leave.requests') ? 'active' : '' }}"
                    {{ request()->routeIs('leave.requests') ? 'aria-current=page' : '' }} href="{{ route('leave.requests') }}">
                        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="nav-text">Leave Requests</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-user">
                <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('images/avatar/avatar-5.jpg') }}"
                    alt="avatar">
                <h6>{{ auth()->user()->full_name }}</h6>
            </div>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">System running smoothly</span>
            </div>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                        aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                        <input class="form-control search-input" type="search"
                            placeholder="Search users, orders, reports" aria-label="Search">
                    </form>

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle
                            aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>
                        <div class="dropdown">
                            <button class="icon-button" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false" aria-label="Notifications">
                                <span class="notification-dot"></span>
                                <i class="bi bi-bell" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <div class="dropdown-header fw-bold text-body">Notifications</div>
                                <a class="dropdown-item" href="users.html">
                                    <span class="notification-title">New user registered</span>
                                    <span class="notification-time">4 minutes ago</span>
                                </a>
                                <a class="dropdown-item" href="charts.html">
                                    <span class="notification-title">Revenue target reached</span>
                                    <span class="notification-time">32 minutes ago</span>
                                </a>
                                <a class="dropdown-item" href="settings.html">
                                    <span class="notification-title">Security review completed</span>
                                    <span class="notification-time">1 hour ago</span>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="avatar-img avatar-sm" src="{{ asset('images/avatar/avatar-5.jpg') }}"
                                    alt="avatar">
                                <span>{{ auth()->user()->full_name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.view.employee', auth()->user()->id) }}">Profile</a></li>
                                <li><a class="dropdown-item" href="settings.html">Account settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            @yield('content')
            <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('js/main.js') }}"></script>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>
            <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
            @if (session('error'))
                <script>
                    toastr.error("{{ session('error') }}");
                </script>
            @elseif(session('success'))
                <script>
                    toastr.success("{{ session('success') }}")
                </script>
            @endif
            @stack('styles')
            @stack('scripts')
            <script>
                window.authUser = {
                    name: @json(auth()->user()->full_name),
                    avatar: @json(asset('images/avatar/avatar-5.jpg'))
                };
            </script>

            {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
</body>

</html>
