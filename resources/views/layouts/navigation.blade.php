<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="pro-user-name ms-1">
                                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                                </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <a href="{{ route('user.edit', auth()->user()->id) }}" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" class="dropdown-item notify-item"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="fe-log-out"></i>
                            <span>{{ __('Log Out') }}</span>
                        </x-responsive-nav-link>
                    </form>


                </div>
            </li>


        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="{{ route('dashboard') }}" class="logo text-center">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.jpeg') }}" alt="" height="60">
                </span>
            </a>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<div class="left-side-menu">

    <div class="h-100" data-simplebar>


        <div id="sidebar-menu">

            <ul id="side-menu">

                @can('viewAny', \App\Models\Organization::class)

                    <li>
                        <a href="{{ route('organization.index') }}">
                            <i class="mdi mdi-domain"></i>
                            <span> {{ __('Organizations') }} </span>
                        </a>
                    </li>
                @endcan
                @can('viewAny', \App\Models\Group::class)
                    <li>
                        <a href="{{ route('group.index') }}">
                            <i class="mdi mdi-account-group"></i>
                            <span> {{ __('Groups') }} </span>
                        </a>
                    </li>
                @endcan
                @can('viewAny', \App\Models\User::class)
                    <li>
                        <a href="{{ route('user.index') }}">
                            <i class="mdi mdi-account"></i>
                            <span> {{ __('Users') }} </span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\ActivityLog::class)
                    <li>
                        <a href="{{ route('activity.logs.index') }}">
                            <i class="mdi mdi-note"></i>
                            <span> {{ __('Activity logs') }} </span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="clearfix"></div>

    </div>

</div>
