@extends("layouts.admin")

@section("content")
    <div class="index-page">
        <h1>Users</h1>

        <a href="{{ route('admin.users.create') }}">New User</a>

        <table class="datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
            </thead>

            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{ route('admin.users.edit', $item->id) }}">{{ $item->id }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $item->id) }}">{{ $item->username }}</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $item->id) }}">{{ $item->email}}</a>
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
