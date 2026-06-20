@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h3">Banners</h1><a class="btn btn-dark" href="{{ route('admin.banners.create') }}">Add Banner</a></div>
<div class="bg-white border rounded p-3 table-responsive"><table class="table"><thead><tr><th>Title</th><th>Sort</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($banners as $banner)<tr><td>{{ $banner->title }}</td><td>{{ $banner->sort_order }}</td><td>{{ $banner->status ? 'Active' : 'Inactive' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.banners.edit', $banner) }}">Edit</a><form class="d-inline" data-confirm="Delete banner?" method="POST" action="{{ route('admin.banners.destroy', $banner) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach
</tbody></table>{{ $banners->links() }}</div>
@endsection
