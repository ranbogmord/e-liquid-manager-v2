@extends("layouts.app")

@section("styles")
    <link rel="stylesheet" href="{{ asset("css/profile.css") }}">
@append

@section("content")
    <div class="edit-page">
        <form action="{{ route('profile:update') }}" method="post">
            @if($errors->any())
                @include("partials.form.errors", [
                    'errors' => $errors->all()
                ])
            @endif

            {{ csrf_field() }}

            @include("partials.form.input", [
                'label' => 'Name',
                'name' => 'name',
                'value' => old('name', $user->name)
            ])
            @include("partials.form.input", [
                'label' => 'Username',
                'name' => 'username',
                'value' => old('username', $user->username)
            ])
            @include("partials.form.input", [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'value' => old('email', $user->email)
            ])
            @include("partials.form.input", [
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password'
            ])
            @include("partials.form.submit", [
                'text' => 'Update',
                'class' => 'btn primary'
            ])
        </form>
    </div>
@endsection
