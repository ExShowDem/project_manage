<div class="page-header-menu">
    <div class="container">
        <div class="hor-menu">
            <ul class="nav navbar-nav root-menu">
                <li class="menu-dropdown classic-menu-dropdown">
                    <a href="{{ route('admin.projects.index') }}">
                        Danh sách dự án
                    </a>
                </li>
                @if (auth()->user()->can('roles.read'))
                <li class="menu-dropdown classic-menu-dropdown">
                    <a href="{{ route('admin.roles.index') }}">
                        Vai trò/Chức vụ
                    </a>
                </li>
                @endif
                @if (auth()->user()->can('users.read'))
                <li class="menu-dropdown classic-menu-dropdown">
                    <a href="{{ route('admin.users.index') }}">
                        Tài khoản
                    </a>
                </li>
                @endif
                @if (auth()->user()->can('report.read'))
                <li class="menu-dropdown classic-menu-dropdown">
                    <a href="{{ route('admin.report.all') }}">
                        Báo cáo
                    </a>
                </li>
                @endif
                @if (auth()->user()->hasRole('admin'))
                <!--
                <li class="menu-dropdown classic-menu-dropdown">
                    <a href="{{ route('admin.action_log.index') }}">
                        Lịch sử
                    </a>
                </li>
                -->
                @endif
            </ul>
        </div>
    </div>
</div>
