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
    @yield("styles")

    @if(config('services.analytics.key'))
        <script src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics.key') }}" async></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments)};
          gtag('js', new Date());

          gtag('config', '{{ config('services.analytics.key') }}');
        </script>
    @endif
</head>
<body>
    @if(auth()->check())
        @include("partials.nav")
    @endif
    @include("partials.messages")

    @yield("content")

    @if(config('sentry.dsn'))
        <script src="https://cdn.ravenjs.com/3.23.3/raven.min.js" crossorigin="anonymous"></script>
        @if(auth()->user())
        <script>
            Raven.setUserContext({
              email: '{{ auth()->user()->email }}',
              id: '{{ auth()->id() }}'
            })
        </script>
        @endif
    @endif
    @yield("scripts")
</body>
</html>