@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@append

@section('content')
    <div id="login-page">
        <div id="login-form">
            <h1>Reset Password</h1>
            <form action="{{ route('password.request') }}" method="post">
                @if($errors->any())
                    @include('partials.form.errors', [
                        'errors' => $errors->all()
                    ])
                @endif

                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                @include("partials.form.input", [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => old('email')
                ])
                @include("partials.form.input", [
                    'label' => 'Password',
                    'type' => 'password',
                    'value' => ""
                ])
                @include("partials.form.input", [
                    'label' => 'Confirm Password',
                    'name' => 'password_confirmation',
                    'type' => 'password',
                    'value' => ""
                ])
                @include("partials.form.submit", [
                    'text' => 'Reset password',
                    'class' => 'btn primary'
                ])
            </form>

            <a id="login-link" href="{{ route('login') }}">Back to login</a>
        </div>
    </div>

    @include("partials.photo-credit")
@endsection
