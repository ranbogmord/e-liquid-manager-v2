@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@append

@section('content')
    <div id="login-page">
        <div id="login-form">
            <h1>Reset Password</h1>
            <form action="{{ route('password.email') }}" method="post">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    @include('partials.form.errors', [
                        'errors' => $errors->all()
                    ])
                @endif

                {{ csrf_field() }}
                @include("partials.form.input", [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => old('email')
                ])
                @include("partials.form.submit", [
                    'text' => 'Reset password',
                    'class' => 'btn primary'
                ])
            </form>

            <a id="login-link" href="{{ route('login') }}">Back to login</a>
        </div>
    </div>

    <div id="photo-credit">
        Photo by
        <a href="https://unsplash.com/photos/YAjsL1KrbNo?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Thomas Bjornstad</a>
        on
        <a href="https://unsplash.com/search/photos/vape?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>
    </div>
@endsection
