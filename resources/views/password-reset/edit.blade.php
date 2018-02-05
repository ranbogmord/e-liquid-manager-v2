@extends("layouts.app")

@section("content")
    <form action="{{ route('password.reset', $token) }}" method="post">
        {{ csrf_field() }}

        @include("partials.form.input", [
            "label" => 'Email',
            'type' => 'Email',
            'value' => old('email', $email)
        ])
        @include("partials.form.input", [
            "label" => 'Password',
            'type' => 'password'
        ])
        @include("partials.form.input", [
            'label' => 'Password Confirmation',
            'name' => 'password_confirmation',
            'type' => 'password'
        ])
        @include("partials.form.submit", [
            'text' => 'Reset password'
        ])
    </form>
@endsection
