<li class="nav-item dropdown dropdown-user">
    {{-- User Dropdown Trigger --}}
    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        {{-- User Info (Name) --}}
        <div class="user-nav d-sm-flex d-none">
            <span class="user-name font-weight-bolder mb-0">
                {{ auth()->user()->name }}
            </span>
        </div>

        {{-- User Avatar --}}
        <span class="avatar">
            <img class="round"
                 src="{{ auth()->user()->logo_path ?? asset('assets/dashboard/app-assets/images/portrait/small/avatar.png') }}"
                 alt="User Avatar" height="40" width="40">
        </span>
    </a>

    {{-- Dropdown Menu --}}
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
        {{-- Profile Link --}}
        <a class="dropdown-item" href="{{ route('dashboard.profile.show') }}">
            <i class="mr-50" data-feather="user"></i> Profile
        </a>

        {{-- Optional Settings (disabled for now) --}}
        {{--
        <a class="dropdown-item" href="{{ route('dashboard.profile.settings') }}">
            <i class="mr-50" data-feather="settings"></i> Settings
        </a>
        --}}

        <div class="dropdown-divider"></div>

        {{-- Logout Link --}}
        <a class="dropdown-item" href="{{ route('dashboard.logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mr-50" data-feather="power"></i> Logout
        </a>

        {{-- Hidden Logout Form --}}
        <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>
