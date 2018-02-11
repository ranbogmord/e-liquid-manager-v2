@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div>
            <h1>Edit Flavour</h1>

            <form action="{{ route('admin.flavours.update', $flavour->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field("PUT") }}

                @if($errors->any())
                    @include("partials.form.errors", [
                        'errors' => $errors->all()
                    ])
                @endif

                @include("admin.flavours.form", [
                    'flavour' => $flavour,
                    'vendors' => $vendors
                ])

                @include("partials.form.submit", [
                    'text' => 'Update',
                    'class' => 'btn primary'
                ])
            </form>

            <div class="delete-form">
                <h2>Delete</h2>
                <form action="{{ route('admin.vendors.destroy', $flavour->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}

                    <input type="submit" class="btn danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
@endsection
