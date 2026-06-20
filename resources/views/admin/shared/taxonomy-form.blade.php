<h1 class="h3 mb-3">{{ $model->exists ? 'Edit' : 'Create' }} {{ ucfirst(\Illuminate\Support\Str::singular($resource)) }}</h1>
<form method="POST" action="{{ $model->exists ? route('admin.'.$resource.'.update', $model) : route('admin.'.$resource.'.store') }}" class="bg-white border rounded p-4" @if($resource === 'categories') enctype="multipart/form-data" @endif>
    @csrf @if($model->exists) @method('PUT') @endif
    <label class="form-label">Name</label><input class="form-control mb-3" name="name" value="{{ old('name', $model->name) }}" required>
    <label class="form-label">Slug</label><input class="form-control mb-3" name="slug" value="{{ old('slug', $model->slug) }}">
    @if($resource === 'categories')
        <label class="form-label">Description</label><textarea class="form-control mb-3" name="description">{{ old('description', $model->description) }}</textarea>
        <label class="form-label">Category Image</label>
        <input class="form-control mb-3" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if($model->image)
            <div class="mb-3">
                <img src="{{ $model->image }}" class="rounded border object-fit-cover" style="width: 120px; height: 90px;" alt="{{ $model->name }}">
            </div>
        @endif
    @else
        <label class="form-label">Logo URL</label><input class="form-control mb-3" name="logo" value="{{ old('logo', $model->logo) }}">
    @endif
    <label class="form-check mb-3"><input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $model->status ?? true))> Active</label>
    <button class="btn btn-dark">Save</button>
</form>
