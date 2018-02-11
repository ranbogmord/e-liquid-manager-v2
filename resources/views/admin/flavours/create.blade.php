@extends("layouts.admin")

@section("content")
    <div class="admin-edit-page">
        <div class="div">
            <h1>Create Flavour</h1>

            <form action="{{ route('admin.flavours.store') }}" method="post">
                {{ csrf_field() }}

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
                    'text' => 'Create',
                    'class' => 'btn primary'
                ])
            </form>
        </div>
    </div>
@endsection
