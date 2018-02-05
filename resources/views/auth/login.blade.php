@extends('layouts.app')

@section('content')
    <div id="login-page">
        <div id="login-form">
            <form action="{{ route('login') }}" method="post">
                {{ csrf_field() }}
                @include("partials.form.input", [
                    'label' => 'Username',
                    'value' => old('username')
                ])
                @include("partials.form.input", [
                    'label' => 'Password',
                    'type' => 'password'
                ])
                @include("partials.form.submit", [
                    'text' => 'Log in'
                ])
            </form>

            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>
    </div>
@endsection
