@extends("layouts.admin")

@section("content")
    <div class="index-page">
        <h1>Vendors</h1>

        <a href="{{ route('admin.vendors.create') }}">New Vendor</a>

        <table class="datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Abbreviation</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
            </thead>

            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{ route('admin.vendors.edit', $item->id) }}">{{ $item->id }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.vendors.edit', $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.vendors.edit', $item->id) }}">{{ $item->abbr }}</a>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
