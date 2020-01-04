<table>
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>Category</th>
            <th>Title</th>
            <th>URL</th>
            <th>Type</th>
            <th>Description</th>
            <th>Sort</th>
            <th>Creater</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $list as $item )
            <tr>
                {{-- <td>{{ $item->id }}</td> --}}
                <td>{{ $item->category ? $item->category->title : config('beesoft.words.unknow') }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->url }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->sort }}</td>
                <td>{{ $item->creater }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
