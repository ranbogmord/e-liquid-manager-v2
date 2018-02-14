@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@append

@section('content')
    <div id="login-page">
        <div id="login-form">
            <h1>E-Liquid Manager</h1>
            <form action="{{ route('login') }}" method="post">
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
                    'label' => 'Password',
                    'type' => 'password'
                ])
                @include("partials.form.submit", [
                    'text' => 'Log in',
                    'class' => 'btn primary'
                ])
            </form>

            <a id="reset-password" href="{{ route('password.request') }}">Forgot password?</a>
        </div>
    </div>

    <div id="photo-credit">
        Photo by
        <a href="https://unsplash.com/photos/YAjsL1KrbNo?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Thomas Bjornstad</a>
        on
        <a href="https://unsplash.com/search/photos/vape?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>
    </div>
@endsection
