<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                {{ Session::get('fullname') }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                @if (Session::get('user_role') !== 'Admin')
                    <a class="dropdown-item" href="#" id="myProfile">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                @endif
                <a class="dropdown-item" href="#" id="changePass">
                    <i class="fas fa-user"></i> Change Password
                </a>
                <a class="dropdown-item" href="#" id="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
