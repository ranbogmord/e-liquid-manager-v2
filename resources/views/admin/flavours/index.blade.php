@extends("layouts.admin")

@section("content")
    <div class="index-page">
        <h1>Flavours</h1>

        <a href="{{ route('admin.flavours.create') }}">New Flavour</a>

        <table class="datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Vendor</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
            </thead>

            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{ route('admin.flavours.edit', $item->id) }}">{{ $item->id }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.flavours.edit', $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.flavours.edit', $item->id) }}">{{ array_get($item, 'vendor.name', 'Other') }}</a>
                    </td>
                    <td>
                        {{ $item->created_at }}
                    </td>
                    <td>
                        {{ $item->updated_at }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
