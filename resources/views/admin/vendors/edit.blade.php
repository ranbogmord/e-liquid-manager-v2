@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div>
            <h1>Edit Vendor</h1>

            <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                @if($errors->any())
                    @include("partials.form.errors", [
                        'errors' => $errors->all()
                    ])
                @endif

                @include("admin.vendors.form", [
                    "vendor" => $vendor
                ])

                @include("partials.form.submit", [
                    'text' => 'Update',
                    'class' => 'btn primary'
                ])
            </form>

            <div class="delete-form">
                <h2>Delete</h2>
                <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}

                    <input type="submit" class="btn danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
@endsection
