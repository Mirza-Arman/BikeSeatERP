<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">No Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                <i class="fas fa-user-circle ml-1"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-primary">
                    <p>
                        {{ Auth::user()->name ?? 'User' }}
                        <small>{{ Auth::user()->email ?? '' }}</small>
                    </p>
                </li>
                <li class="user-footer">
                    <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline float-right">
                        @csrf
                        <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
