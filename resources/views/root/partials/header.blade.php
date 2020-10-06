<div class="page-header-top">
    <div class="container">
        <div class="page-logo">
            <a href="{{ route('admin.projects.index') }}">
                <img src="{{ asset('assets/admin/images/megaon.png') }}"
                     style="width: 200px; height: auto; margin-top: 4px;"
                     alt="logo"/>
            </a>
        </div>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user dropdown-dark">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                       data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="{{ asset(is_null($currentUser->image) ? 'assets/root/images/avatar1.jpg' : 'storage/images/avatars/'.$currentUser->image) }}">
                        <span class="username username-hide-mobile">{{ $currentUser->name }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{ route('admin.profile.index') }}">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-key"></i> Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
