<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-Liquid Manager</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield("styles")
</head>
<body>
@include("partials.nav")
@include("partials.messages")

<main id="admin-section">
    <aside class="navbar">
        <ul>
            <li>
                <a href="{{ route('admin.flavours.index') }}">Flavours</a>
            </li>
            <li>
                <a href="{{ route('admin.vendors.index') }}">Vendors</a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}">Users</a>
            </li>
        </ul>
    </aside>

    <div class="edit-area">
        @yield("content")
    </div>
</main>


<script src="{{ asset('js/admin.js') }}"></script>
@yield("scripts")
</body>
</html>