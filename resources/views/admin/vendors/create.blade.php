@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div class="div">
            <h1>Create Vendor</h1>

            <form action="{{ route('admin.vendors.store') }}" method="post">
                {{ csrf_field() }}

                @if($errors->any())
                    @include("partials.form.errors", [
                        'errors' => $errors->all()
                    ])
                @endif

                @include("admin.vendors.form", [
                    "vendor" => $vendor
                ])

                @include("partials.form.submit", [
                    'text' => 'Create',
                    'class' => 'btn primary'
                ])
            </form>
        </div>
    </div>
@endsection
