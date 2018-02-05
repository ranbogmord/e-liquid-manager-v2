@extends("layouts.app")

@section("content")
    <form action="{{ route('password.create-reset') }}" method="post">
        {{ csrf_field() }}
        @include("partials.form.input", [
            'label' => 'Email',
            'type' => 'email'
        ])
        @include("partials.form.submit", [
            'text' => 'Reset password'
        ])
    </form>
@endsection
