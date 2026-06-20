<div class="bg-white border rounded p-3 table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Name</th><th>Slug</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td><td>{{ $item->slug }}</td><td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.'.$resource.'.edit', $item) }}">Edit</a>
                    <form class="d-inline" data-confirm="Delete this item?" method="POST" action="{{ route('admin.'.$resource.'.destroy', $item) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $items->links() }}
</div>
