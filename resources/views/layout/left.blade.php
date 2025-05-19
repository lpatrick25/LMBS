<aside class="main-sidebar elevation-4 sidebar-dark-lime">
    <a href="#" class="brand-link">
        <img src="{{ asset('dist/img/acclogo.png') }}" alt="ACC Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">ACC Laboratory System</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image position-relative">
                <img src="{{ asset('dist/img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                <!-- Online Status Icon -->
                <span class="position-absolute status-icon bg-success border border-white rounded-circle"
                    style="width: 10px; height: 10px; bottom: 0; right: 0;"></span>
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block font-weight-bold">{{ Session::get('fullname', 'Guest') }}</a>
                <span class="d-block text-muted text-lime">{{ Session::get('user_role', 'N/A') }}</span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-collapse-hide-child"
                data-widget="treeview" role="menu" data-accordion="false">

                @if (Session::get('user_role') === 'Admin')
                    <li class="nav-item">
                        <a href="{{ route('viewAdminDashboard') }}" class="nav-link @yield('active-dashboard')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaff') }}" class="nav-link @yield('active-labStaff')">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>Laboratory Staff</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewEmployee') }}" class="nav-link @yield('active-employees')">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Employee</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewBorrower') }}" class="nav-link @yield('active-borrowers')">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Borrowers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewUser') }}" class="nav-link @yield('active-users')">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    <li class="nav-item @yield('active-items-open')">
                        <a href="#" class="nav-link @yield('active-items')">
                            <i class="nav-icon fas fa-suitcase"></i>
                            <p>
                                Items
                                <i class="right fas fa-chevron-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('viewCategory') }}" class="nav-link @yield('active-items-category')">
                                    <i class="nav-icon fas fa-tag"></i>
                                    <p>Item Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('viewItem') }}" class="nav-link @yield('active-items-list')">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>Item Lists</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewTransactions') }}" class="nav-link @yield('active-transactions')">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewInventory') }}" class="nav-link @yield('active-inventories')">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Inventory</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewPenalties') }}" class="nav-link @yield('active-penalties')">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Penalties</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewReport') }}" class="nav-link @yield('active-reports')">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Report</p>
                        </a>
                    </li>
                @endif

                @if (Session::get('user_role') === 'Laboratory In-charge' || Session::get('user_role') === 'Laboratory Head')
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaffDashboard') }}" class="nav-link @yield('active-dashboard')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endif

                @if (Session::get('user_role') === 'Laboratory In-charge')
                    <li class="nav-item @yield('active-items-open')">
                        <a href="#" class="nav-link @yield('active-items')">
                            <i class="nav-icon fas fa-suitcase"></i>
                            <p>
                                Items
                                <i class="right fas fa-chevron-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('viewLabStaffCategory') }}" class="nav-link @yield('active-items-category')">
                                    <i class="nav-icon fas fa-tag"></i>
                                    <p>Item Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('viewLabStaffItem') }}" class="nav-link @yield('active-items-list')">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>Item Lists</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Session::get('user_role') === 'Laboratory In-charge' || Session::get('user_role') === 'Laboratory Head')
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaffTransactions') }}" class="nav-link @yield('active-transactions')">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaffInventory') }}" class="nav-link @yield('active-inventories')">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Inventory</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaffPenalties') }}" class="nav-link @yield('active-penalties')">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Penalties</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewLabStaffReport') }}" class="nav-link @yield('active-reports')">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Report</p>
                        </a>
                    </li>
                @endif

                @if (Session::get('user_role') === 'Employee')
                    <li class="nav-item">
                        <a href="{{ route('viewEmployeeDashboard') }}" class="nav-link @yield('active-dashboard')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewEmployeeItems') }}" class="nav-link @yield('active-items')">
                            <i class="nav-icon fas fa-suitcase"></i>
                            <p>Items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewEmployeeTransactions') }}" class="nav-link @yield('active-transactions')">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                @endif

                @if (Session::get('user_role') === 'Borrower')
                    <li class="nav-item">
                        <a href="{{ route('viewBorrowerDashboard') }}" class="nav-link @yield('active-dashboard')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewBorrowerItems') }}" class="nav-link @yield('active-items')">
                            <i class="nav-icon fas fa-suitcase"></i>
                            <p>Items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('viewBorrowerTransactions') }}" class="nav-link @yield('active-transactions')">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
