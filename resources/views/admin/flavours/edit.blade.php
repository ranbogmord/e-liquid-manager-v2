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

            <div class="merge-form">
                <h2>Merge</h2>
                <p>Merge this flavour into:</p>
                <form method="post" action="{{ route('admin.flavours.merge') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="from" value="{{ $flavour->id }}">
                    @include("partials.form.select", [
                        'label' => 'Flavour',
                        'name' => 'to',
                        'options' => $flavours,
                        'value' => old('to'),
                        'default' => 'Select'
                    ])

                    <input type="submit" class="btn primary" value="Merge">
                </form>
            </div>

            <div class="delete-form">
                <h2>Delete</h2>
                <form action="{{ route('admin.flavours.destroy', $flavour->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}

                    <input type="submit" class="btn danger" value="Delete">
                </form>
            </div>
        </div>
    </div>
@endsection
