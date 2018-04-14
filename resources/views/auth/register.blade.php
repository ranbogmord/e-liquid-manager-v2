@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@append

@section('content')
    <div id="login-page">
        <div id="login-form">
            <h1>E-Liquid Manager</h1>
            <form action="{{ route('register') }}" method="post">
                @if($errors->any())
                    @include('partials.form.errors', [
                        'errors' => $errors->all()
                    ])
                @endif
                {{ csrf_field() }}
                @include("partials.form.input", [
                    'label' => 'Username',
                    'value' => old('username')
                ])
                @include("partials.form.input", [
                    'label' => 'Email',
                    'value' => old('email')
                ])
                @include("partials.form.input", [
                    'label' => 'Password',
                    'type' => 'password'
                ])
                @include("partials.form.input", [
                    'label' => 'Password again',
                    'type' => 'password',
                    'name' => 'password_confirmation'
                ])
                @include("partials.form.submit", [
                    'text' => 'Register',
                    'class' => 'btn primary'
                ])
            </form>

            <a id="reset-password" href="{{ route('login') }}">Have an account? Log in.</a>
        </div>
    </div>

    @include("partials.photo-credit")
@endsection
