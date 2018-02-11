<header id="main-header">
    <h1 id="brand">
        <a href="{{ route('app') }}">E-Liquid Manager</a>
    </h1>
    <a id="mobile-menu-toggle" href="#"><span>menu</span></a>
    <ul id="menu">
        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
        @if(auth()->user() && auth()->user()->role === "admin")
            <li>
                <a href="{{ route('admin.index') }}">Admin</a>
            </li>
        @endif
        <li><a href="/logout">Logout</a></li>
    </ul>
</header>
