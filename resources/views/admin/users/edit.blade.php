@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div>
            <h1>Edit User</h1>

            <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field("PUT") }}

                @if($errors->any())
                    @include("partials.form.errors", [
                        'errors' => $errors->all()
                    ])
                @endif

                @include("admin.users.form", [
                    'user' => $user
                ])

                @include("partials.form.submit", [
                    'text' => 'Update',
                    'class' => 'btn primary'
                ])
            </form>

            <div class="delete-form">
                <h2>Delete</h2>
                <form action="{{ route('admin.vendors.destroy', $user->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}

                    <input type="submit" class="btn danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
@endsection
