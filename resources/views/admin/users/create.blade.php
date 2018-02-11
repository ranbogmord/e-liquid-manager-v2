@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div class="div">
            <h1>Create User</h1>

            <form action="{{ route('admin.users.store') }}" method="post">
                {{ csrf_field() }}

                @if($errors->any())
                    @include("partials.form.errors", [
                        'errors' => $errors->all()
                    ])
                @endif

                @include("admin.users.form", [
                    'user' => $user
                ])

                @include("partials.form.submit", [
                    'text' => 'Create',
                    'class' => 'btn primary'
                ])
            </form>
        </div>
    </div>
@endsection
